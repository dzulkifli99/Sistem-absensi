<?php
/**
 * c:\xampp\htdocs\Sistem-absensi\auto_worker.php
 * 
 * Script ini dirancang untuk dijalankan otomatis oleh Windows Task Scheduler.
 * Fungsinya:
 * 1. Tarik data dari mesin fingerprint (Sync).
 * 2. Tandai Alpa secara otomatis jika sudah melewati jam batas.
 * 3. Kirim semua antrean pesan WA (Notify).
 */

// Agar tidak timeout jika data banyak
set_time_limit(0);
date_default_timezone_set('Asia/Jakarta');

// Lokasi absolut file (penting untuk Task Scheduler)
$dir = __DIR__;
require_once "$dir/koneksi.php";
require_once "$dir/zklibrary.php";
require_once "$dir/helper_wa.php";

// Matikan error reporting yang mengganggu output CLI
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);

echo "[" . date('Y-m-d H:i:s') . "] Memulai proses otomatis...\n";

// --- 1. CEK HARI KERJA ---
$hari_inggris = date('l');
$daftar_hari = [
    'Monday'    => 'Senin',
    'Tuesday'   => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday'  => 'Kamis',
    'Friday'    => 'Jumat',
];

if (!array_key_exists($hari_inggris, $daftar_hari)) {
    echo "Hari ini libur (" . $hari_inggris . "). Skip sinkronisasi.\n";
} else {
    $hari_sekarang = $daftar_hari[$hari_inggris];
    $query_set = mysqli_query($koneksi, "SELECT * FROM setting WHERE hari = '$hari_sekarang'");
    $data_setting = mysqli_fetch_assoc($query_set);

    if ($data_setting) {
        $jam_masuk      = !empty($data_setting['jam_masuk'])    ? $data_setting['jam_masuk']    : '07:00';
        $batas_masuk    = !empty($data_setting['batas_masuk'])   ? $data_setting['batas_masuk']   : '07:15';
        $toleransi      = isset($data_setting['toleransi_terlambat']) ? (int)$data_setting['toleransi_terlambat'] : 15;
        $jam_pulang_min = !empty($data_setting['jam_pulang'])    ? $data_setting['jam_pulang']    : '14:00';
        
        $batas_terlambat    = date('H:i', strtotime(date('Y-m-d') . ' ' . $batas_masuk . " +$toleransi minutes"));
        $waktu_alpa_trigger = date('H:i', strtotime(date('Y-m-d') . ' ' . $batas_terlambat . ' +1 minute'));
        $waktu_sekarang     = date('H:i');

        // --- 2. AUTO ALPA ---
        if ($waktu_sekarang >= $waktu_alpa_trigger) {
            echo "Memproses Auto-Alpa...\n";
            $q_alpa = mysqli_query($koneksi, "SELECT id_siswa, nama, no_hp FROM data WHERE id_siswa NOT IN (SELECT id_siswa FROM absensi WHERE tanggal = CURDATE())");
            while ($siswa_alpa = mysqli_fetch_assoc($q_alpa)) {
                $nis_alpa = $siswa_alpa['id_siswa'];
                $nama_alpa = $siswa_alpa['nama'];
                $no_hp_alpa = $siswa_alpa['no_hp'];
                
                if (mysqli_query($koneksi, "INSERT INTO absensi (id_siswa, tanggal, status) VALUES ('$nis_alpa', CURDATE(), 'Alpa')")) {
                    if (!empty($no_hp_alpa)) {
                        $pesan_alpa = "Assalamu'alaikum Wr.Wb\n\nPemberitahuan Absensi:\nKami informasikan bahwa Ananda {$nama_alpa} belum hadir di sekolah hingga pukul {$waktu_alpa_trigger}.\nStatus: Alpa\n\n— [SMK AL-MALIKI]";
                        queueWA($no_hp_alpa, $pesan_alpa);
                    }
                }
            }
        }

        // --- 3. SYNC MESIN ---
        $devices = [
            ['ip' => '192.168.1.201', 'port' => 4370],
            ['ip' => '192.168.1.202', 'port' => 4370]
        ];

        foreach ($devices as $dev) {
            echo "Menghubungi mesin {$dev['ip']}...";
            $zk = new ZKLibrary($dev['ip'], $dev['port']);
            if ($zk->connect()) {
                echo " Terhubung. Menarik data...\n";
                $zk->disableDevice();
                $logs = $zk->getAttendance();
                if ($logs) {
                    foreach ($logs as $log) {
                        $nis     = $log[1];
                        $waktu   = $log[3];
                        $tanggal = date('Y-m-d', strtotime($waktu));
                        $jam     = date('H:i:s', strtotime($waktu));

                        if ($tanggal != date('Y-m-d')) continue;
                        if (strtotime($jam) < strtotime(date('Y-m-d') . ' ' . $jam_masuk)) continue;

                        $cek_scan = mysqli_query($koneksi, "SELECT id FROM absensi WHERE id_siswa='$nis' AND last_scan='$waktu'");
                        if (mysqli_num_rows($cek_scan) > 0) continue;

                        $q_siswa = mysqli_query($koneksi, "SELECT nama, no_hp FROM data WHERE id_siswa='$nis'");
                        $siswa   = mysqli_fetch_assoc($q_siswa);
                        if (!$siswa) continue;

                        $cek_absensi = mysqli_query($koneksi, "SELECT * FROM absensi WHERE id_siswa='$nis' AND tanggal='$tanggal'");
                        $data_absen  = mysqli_fetch_assoc($cek_absensi);
                        $is_waktu_pulang = strtotime($jam) >= strtotime(date('Y-m-d') . ' ' . $jam_pulang_min);

                        if (!$is_waktu_pulang) {
                            if (!$data_absen || ($data_absen['status'] === 'Alpa' && empty($data_absen['jam_datang']))) {
                                $status = (strtotime($jam) <= strtotime(date('Y-m-d') . ' ' . $batas_masuk)) ? 'Hadir' : 'Terlambat';
                                if ($data_absen) {
                                    mysqli_query($koneksi, "UPDATE absensi SET jam_datang='$jam', last_scan='$waktu', status='$status' WHERE id_siswa='$nis' AND tanggal='$tanggal'");
                                } else {
                                    mysqli_query($koneksi, "INSERT INTO absensi (id_siswa, tanggal, jam_datang, last_scan, status) VALUES ('$nis', '$tanggal', '$jam', '$waktu', '$status')");
                                }
                                // Kirim Notif Datang
                                if (!empty($siswa['no_hp'])) {
                                    $msg = "Pemberitahuan Absensi:\nAnanda {$siswa['nama']} telah hadir di sekolah pada pukul $jam.\nStatus: $status\n\n— [SMK AL-MALIKI]";
                                    queueWA($siswa['no_hp'], $msg);
                                }
                            }
                        } else {
                            // SCAN PULANG
                            if ($data_absen) {
                                mysqli_query($koneksi, "UPDATE absensi SET jam_pulang='$jam', last_scan='$waktu' WHERE id_siswa='$nis' AND tanggal='$tanggal'");
                                // Kirim Notif Pulang (Opsional, jika ingin aktifkan)
                            }
                        }
                    }
                }
                $zk->enableDevice();
                $zk->disconnect();
            } else {
                echo " Gagal terhubung.\n";
            }
        }
    }
}

// --- 4. KIRIM SEMUA ANTREAN WA --
echo "Memproses antrean WhatsApp...\n";
$q_wa = mysqli_query($koneksi, "SELECT * FROM wa_queue WHERE status = 'pending' ORDER BY id ASC");
$count_sent = 0;
while ($row_wa = mysqli_fetch_assoc($q_wa)) {
    $success = sendWADirectly($row_wa['nomor'], $row_wa['pesan']);
    $status_baru = $success ? 'sent' : 'failed';
    mysqli_query($koneksi, "UPDATE wa_queue SET status = '$status_baru' WHERE id = {$row_wa['id']}");
    if ($success) $count_sent++;
    // Jeda 2 detik antar pesan agar tidak diblokir Fonnte
    sleep(2);
}
echo "Berhasil mengirim $count_sent pesan.\n";

// Simpan waktu terakhir jalan
file_put_contents("$dir/last_sync.log", date('Y-m-d H:i:s'));
echo "[" . date('Y-m-d H:i:s') . "] Selesai.\n";

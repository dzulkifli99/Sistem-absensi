<?php
header('Content-Type: application/json');

require 'koneksi.php';
require 'zklibrary.php';
require 'notifikasi.php';

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');

// --- 1. AMBIL SETTING HARI INI ---
$hari_inggris = date('l');
$daftar_hari = [
    'Monday'    => 'Senin',
    'Tuesday'   => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday'  => 'Kamis',
    'Friday'    => 'Jumat',
];

$is_hari_kerja = array_key_exists($hari_inggris, $daftar_hari);

if (!$is_hari_kerja) {
    $nama_hari_libur = ($hari_inggris == 'Saturday') ? 'Sabtu' : 'Minggu';
    echo json_encode([
        'status'  => 'warning',
        'title'   => 'Hari Libur',
        'message' => "Hari ini hari {$nama_hari_libur}. Sistem tidak melakukan sinkronisasi."
    ]);
    exit();
}

$hari_sekarang = $daftar_hari[$hari_inggris];
$query_set = mysqli_query($koneksi, "SELECT * FROM setting WHERE hari = '$hari_sekarang'");
$data_setting = mysqli_fetch_assoc($query_set);

if (!$data_setting) {
    echo json_encode([
        'status'  => 'warning',
        'title'   => 'Hari Libur',
        'message' => "Hari $hari_sekarang tidak ditemukan di pengaturan jam. Sistem tidak ditarik."
    ]);
    exit();
}

// Gunakan !empty() agar empty string dari DB tetap fallback ke nilai default
$jam_masuk      = !empty($data_setting['jam_masuk'])    ? $data_setting['jam_masuk']    : '07:00';
$batas_masuk    = !empty($data_setting['batas_masuk'])   ? $data_setting['batas_masuk']   : '07:15';
$jam_pulang_min = !empty($data_setting['jam_pulang'])    ? $data_setting['jam_pulang']    : '14:00';
$batas_pulang   = !empty($data_setting['batas_pulang'])  ? $data_setting['batas_pulang']  : '14:30';

// --- HITUNG WAKTU TURUNAN ---
$batas_terlambat    = date('H:i', strtotime(date('Y-m-d') . ' ' . $batas_masuk . ' +15 minutes'));
$waktu_alpa_trigger = date('H:i', strtotime(date('Y-m-d') . ' ' . $batas_terlambat . ' +1 minute'));
$waktu_sekarang     = date('H:i');

// Counter untuk laporan
$count_datang   = 0;
$count_pulang   = 0;
$count_alpa     = 0;
$count_skip     = 0;

// --- 2. LOGIKA AUTO ALPA ---
if ($waktu_sekarang >= $waktu_alpa_trigger) {
    $q_alpa = mysqli_query($koneksi, "
        SELECT NIS, nama, no_hp 
        FROM data 
        WHERE NIS NOT IN (
            SELECT NIS FROM absensi WHERE tanggal = CURDATE()
        )
    ");

    while ($siswa_alpa = mysqli_fetch_assoc($q_alpa)) {
        $nis_alpa   = $siswa_alpa['NIS'];
        $nama_alpa  = $siswa_alpa['nama'];
        $no_hp_alpa = $siswa_alpa['no_hp'];

        $insert_ok = mysqli_query($koneksi, "INSERT INTO absensi (NIS, tanggal, status) VALUES ('$nis_alpa', CURDATE(), 'Alpa')");

        if ($insert_ok && mysqli_affected_rows($koneksi) > 0) {
            $count_alpa++;
            if (!empty($no_hp_alpa)) {
                $pesan_alpa = "Assalamu'alaikum Wr.Wb\n\nPemberitahuan Absensi:\nKami informasikan bahwa Ananda {$nama_alpa} belum hadir di sekolah hingga pukul {$waktu_alpa_trigger}.\nStatus: Alpa\n\nMohon konfirmasi keterangannya kepada wali kelas.\n\n— [SMK AL-MALIKI]";
                kirimWA($no_hp_alpa, $pesan_alpa);
            }
        }
    }
}

// --- 3. AMBIL LOG DARI MESIN FINGERPRINT ---
// Konfigurasi IP Mesin Fingerprint
$devices = [
    ['ip' => '192.168.1.201', 'port' => 4370],
    ['ip' => '192.168.1.202', 'port' => 4370] // Tambahkan IP mesin kedua di sini
];

$all_logs = [];
$connected_devices = 0;
$failed_devices = [];

foreach ($devices as $dev) {
    $zk = new ZKLibrary($dev['ip'], $dev['port']);
    if ($zk->connect()) {
        $zk->disableDevice();
        $logs = $zk->getAttendance();
        if (is_array($logs) && !empty($logs)) {
            $all_logs = array_merge($all_logs, $logs);
        }
        $zk->enableDevice();
        $zk->disconnect();
        $connected_devices++;
    } else {
        $failed_devices[] = $dev['ip'];
    }
}

if ($connected_devices > 0) {
    // Urutkan log berdasarkan waktu agar pemrosesan berurutan
    usort($all_logs, function($a, $b) {
        return strtotime($a[3]) - strtotime($b[3]);
    });

    // --- 4. PROSES LOG FINGERPRINT ---
    foreach ($all_logs as $log) {
        $nis     = $log[1];
        $waktu   = $log[3];
        $tanggal = date('Y-m-d', strtotime($waktu));
        $jam     = date('H:i:s', strtotime($waktu));

        if ($tanggal != date('Y-m-d')) { $count_skip++; continue; }

        // Gate: scan sebelum jam_masuk diabaikan
        if (strtotime($jam) < strtotime(date('Y-m-d') . ' ' . $jam_masuk)) { $count_skip++; continue; }

        // Skip jika scan ini sudah pernah diproses
        $cek_scan = mysqli_query($koneksi, "SELECT id FROM absensi WHERE NIS='$nis' AND last_scan='$waktu'");
        if (mysqli_num_rows($cek_scan) > 0) { $count_skip++; continue; }

        $q_siswa = mysqli_query($koneksi, "SELECT nama, no_hp FROM data WHERE NIS='$nis'");
        $siswa   = mysqli_fetch_assoc($q_siswa);
        if (!$siswa) { $count_skip++; continue; }

        $cek_absensi = mysqli_query($koneksi, "SELECT * FROM absensi WHERE NIS='$nis' AND tanggal='$tanggal'");
        $data_absen  = mysqli_fetch_assoc($cek_absensi);

        $is_waktu_pulang = strtotime($jam) >= strtotime(date('Y-m-d') . ' ' . $jam_pulang_min);

        if (!$is_waktu_pulang) {
            // SCAN DATANG (PAGI)
            $sudah_alpa_tanpa_datang = ($data_absen && $data_absen['status'] === 'Alpa' && empty($data_absen['jam_datang']));

            if (!$data_absen || $sudah_alpa_tanpa_datang) {
                $status = (strtotime($jam) <= strtotime(date('Y-m-d') . ' ' . $batas_masuk)) ? 'Hadir' : 'Terlambat';

                if ($data_absen) {
                    mysqli_query($koneksi, "UPDATE absensi SET jam_datang='$jam', last_scan='$waktu', status='$status' WHERE NIS='$nis' AND tanggal='$tanggal'");
                } else {
                    mysqli_query($koneksi, "INSERT INTO absensi (NIS, tanggal, jam_datang, last_scan, status) VALUES ('$nis', '$tanggal', '$jam', '$waktu', '$status')");
                }

                $jam_fmt = date('H:i', strtotime($jam));
                $pesan = "Assalamu'alaikum Wr.Wb\n\nPemberitahuan Absensi:\nAlhamdulillah, Ananda {$siswa['nama']} telah tiba di sekolah pada pukul {$jam_fmt}\nStatus: {$status}\n\n— [SMK AL-MALIKI]";
                kirimWA($siswa['no_hp'], $pesan);
                $count_datang++;
            } else {
                $count_skip++;
            }
        } else {
            // SCAN PULANG (SORE)
            if (!$data_absen) {
                mysqli_query($koneksi, "INSERT INTO absensi (NIS, tanggal, jam_pulang, last_scan, status) VALUES ('$nis', '$tanggal', '$jam', '$waktu', 'Alpa')");
                $jam_fmt = date('H:i', strtotime($jam));
                $pesan = "Assalamu'alaikum Wr.Wb\n\nAnanda {$siswa['nama']} telah pulang pada pukul {$jam_fmt} namun tidak melakukan absen kehadiran di pagi hari.\n\n— [SMK AL-MALIKI]";
                kirimWA($siswa['no_hp'], $pesan);
                $count_pulang++;
            } elseif (empty($data_absen['jam_pulang'])) {
                mysqli_query($koneksi, "UPDATE absensi SET jam_pulang='$jam', last_scan='$waktu' WHERE NIS='$nis' AND tanggal='$tanggal'");
                $jam_fmt = date('H:i', strtotime($jam));
                $pesan = "Assalamu'alaikum Wr.Wb\n\nAnanda {$siswa['nama']} telah pulang pada pukul {$jam_fmt}.\nSemoga selamat sampai rumah.\n\n— [SMK AL-MALIKI]";
                kirimWA($siswa['no_hp'], $pesan);
                $count_pulang++;
            } else {
                $count_skip++;
            }
        }
    }

    $mesin_info = "Data ditarik dari $connected_devices mesin.";
    if (count($failed_devices) > 0) {
        $mesin_info .= " Gagal terhubung ke: " . implode(", ", $failed_devices);
    }

    echo json_encode([
        'status'  => 'success',
        'title'   => 'Sinkronisasi Berhasil',
        'message' => $mesin_info,
        'detail'  => [
            'hari'          => $hari_sekarang,
            'jam_masuk'     => $jam_masuk,
            'batas_masuk'   => $batas_masuk,
            'batas_terlambat' => $batas_terlambat,
            'alpa_trigger'  => $waktu_alpa_trigger,
            'sekarang'      => $waktu_sekarang,
            'datang'        => $count_datang,
            'pulang'        => $count_pulang,
            'alpa_baru'     => $count_alpa,
            'dilewati'      => $count_skip,
            'mesin_terhubung' => $connected_devices,
            'mesin_gagal'   => count($failed_devices)
        ]
    ]);

} else {
    echo json_encode([
        'status'  => 'error',
        'title'   => 'Koneksi Gagal',
        'message' => 'Tidak dapat terhubung ke semua mesin fingerprint (' . implode(", ", $failed_devices) . '). Pastikan mesin menyala dan terhubung ke jaringan.'
    ]);
}

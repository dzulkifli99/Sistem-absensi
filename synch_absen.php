<?php
require 'koneksi.php';
require 'zklibrary.php';
require 'notifikasi.php';

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');

$zk = new ZKLibrary('192.168.1.202', 4370);

if ($zk->connect()) {
    $zk->disableDevice();
    $logs = $zk->getAttendance();

    // --- 1. AMBIL SETTING HARI INI ---
    $hari_inggris = date('l');
    $daftar_hari = [
        'Monday'    => 'Senin',
        'Tuesday'   => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday'  => 'Kamis',
        'Friday'    => 'Jumat',
    ];

    // Cek apakah hari ini hari kerja
    $is_hari_kerja = array_key_exists($hari_inggris, $daftar_hari);

    if ($is_hari_kerja) {
        $hari_sekarang = $daftar_hari[$hari_inggris];
        $query_set = mysqli_query($koneksi, "SELECT * FROM setting WHERE hari = '$hari_sekarang'");
        $data_setting = mysqli_fetch_assoc($query_set);

        // Jika tabel setting kosong / sekolah diliburkan di setting (tidak ada hari ini), 
        // kita hentikan proses agar siswa tidak mendadak dapat Alpa karena auto-alpa
        if (!$data_setting) {
            echo "Hari ini disetting sebagai hari libur ($hari_sekarang tidak ada di pengaturan jam). Sistem tidak ditarik.";
            $zk->enableDevice();
            $zk->disconnect();
            exit();
        }

        $jam_masuk      = $data_setting['jam_masuk'] ?? "07:00";
        $batas_masuk    = $data_setting['batas_masuk'] ?? "07:15";
        $jam_pulang_min = $data_setting['jam_pulang'] ?? "14:00";

        // --- 2. LOGIKA AUTO ALPA (HANYA JALAN DI HARI KERJA & BILA SETTING ADA) ---
        $waktu_alpa_trigger = date('H:i', strtotime($jam_masuk . ' +1 hour'));
        if (date('H:i') >= $waktu_alpa_trigger) {
            $q_alpa = mysqli_query($koneksi, "
                SELECT NIS, nama, no_hp 
                FROM data 
                WHERE NIS NOT IN (SELECT NIS FROM absensi WHERE tanggal=CURDATE())
            ");
            
            while ($siswa_alpa = mysqli_fetch_assoc($q_alpa)) {
                $nis_alpa = $siswa_alpa['NIS'];
                $nama_alpa = $siswa_alpa['nama'];
                $no_hp_alpa = $siswa_alpa['no_hp'];

                // Insert ke database
                mysqli_query($koneksi, "INSERT IGNORE INTO absensi (NIS, tanggal, status) VALUES ('$nis_alpa', CURDATE(), 'Alpa')");

                // Pastikan insert berhasil (bukan data yang sudah ada karena race condition)
                if (mysqli_affected_rows($koneksi) > 0) {
                    // Kirim WA
                    if (!empty($no_hp_alpa)) {
                        $pesan_alpa = "Assalamu’alaikum Wr.Wb\n\nPemberitahuan Absensi:\nKami informasikan bahwa Ananda {$nama_alpa} belum hadir di sekolah hingga 1 jam setelah jam masuk.\nStatus: Alpa\n\nMohon konfirmasi keterangannya kepada wali kelas.\n\n— [SMK AL-MALIKI]";
                        kirimWA($no_hp_alpa, $pesan_alpa);
                    }
                }
            }
        }

        // --- 3. PROSES LOG FINGERPRINT (HANYA JALAN DI HARI KERJA) ---
        foreach ($logs as $log) {
            $nis     = $log[1];
            $waktu   = $log[3];
            $tanggal = date('Y-m-d', strtotime($waktu));
            $jam     = date('H:i:s', strtotime($waktu));

            // Pastikan log yang diambil HANYA log tanggal hari ini agar tidak memproses data kadaluarsa
            if ($tanggal != date('Y-m-d')) continue;

            $cek_scan = mysqli_query($koneksi, "SELECT id FROM absensi WHERE NIS='$nis' AND last_scan='$waktu'");
            if (mysqli_num_rows($cek_scan) > 0) continue;

            $q_siswa = mysqli_query($koneksi, "SELECT nama, no_hp FROM data WHERE NIS='$nis'");
            $siswa   = mysqli_fetch_assoc($q_siswa);
            if (!$siswa) continue;

            $cek_absensi = mysqli_query($koneksi, "SELECT * FROM absensi WHERE NIS='$nis' AND tanggal='$tanggal'");
            $data_absen  = mysqli_fetch_assoc($cek_absensi);

            $is_waktu_pulang = strtotime($jam) >= strtotime($jam_pulang_min);

            if (!$is_waktu_pulang) {
                // --- PROSES SCAN PAGI (DATANG) ---
                // Hanya dieksekusi jika belum absen, ATAU dia kena auto-alpa tapi belum scan datang
                if (!$data_absen || ($data_absen['status'] == 'Alpa' && $data_absen['jam_datang'] == NULL)) {
                    $status = (strtotime($jam) <= strtotime($batas_masuk)) ? "Hadir" : "Terlambat";
                    if ($data_absen) {
                        mysqli_query($koneksi, "UPDATE absensi SET jam_datang='$jam', last_scan='$waktu', status='$status' WHERE NIS='$nis' AND tanggal='$tanggal'");
                    } else {
                        mysqli_query($koneksi, "INSERT INTO absensi (NIS, tanggal, jam_datang, last_scan, status) VALUES ('$nis', '$tanggal', '$jam', '$waktu', '$status')");
                    }

                    // Kirim WA Datang
                    $pesan = "Assalamu’alaikum Wr.Wb\n\nPemberitahuan Absensi:\nAlhamdulillah, Ananda {$siswa['nama']} telah tiba di sekolah pada pukul " . date('H:i', strtotime($jam)) . "\nStatus: $status\n\n— [SMK AL-MALIKI]";
                    kirimWA($siswa['no_hp'], $pesan);
                }
            } else {
                // --- PROSES SCAN SORE (PULANG) ---
                if (!$data_absen) {
                    // Baru nongol scan pada jam pulang, tanpa absen datang (bolos pagi)
                    // Atur status menjadi 'Alpa', tapi jam pulang tetap terisi
                    mysqli_query($koneksi, "INSERT INTO absensi (NIS, tanggal, jam_pulang, last_scan, status) VALUES ('$nis', '$tanggal', '$jam', '$waktu', 'Alpa')");
                    
                    // Kirim WA Pulang (Tanpa Datang)
                    $pesan = "Assalamu’alaikum Wr.Wb\n\nAnanda {$siswa['nama']} telah pulang pada pukul " . date('H:i', strtotime($jam)) . " namun tanpa melakukan absen kehadiran di pagi hari.\n\n— [SMK AL-MALIKI]";
                    kirimWA($siswa['no_hp'], $pesan);
                    
                } else if ($data_absen['jam_pulang'] == NULL) {
                    // Siswa punya record absen pagi (Hadir/Terlambat/Alpa), sekarang waktunya update jam pulang
                    mysqli_query($koneksi, "UPDATE absensi SET jam_pulang='$jam', last_scan='$waktu' WHERE NIS='$nis' AND tanggal='$tanggal'");

                    // Kirim WA Pulang
                    $pesan = "Assalamu’alaikum Wr.Wb\n\nAnanda {$siswa['nama']} telah pulang pada pukul " . date('H:i', strtotime($jam)) . ".\nSemoga selamat sampai rumah.\n\n— [SMK AL-MALIKI]";
                    kirimWA($siswa['no_hp'], $pesan);
                }
            }
        }
        echo "Sync sukses (Hari Kerja)!";
    } else {
        // JIKA HARI SABTU/MINGGU
        echo "Hari ini hari libur (" . (($hari_inggris == 'Saturday') ? 'Sabtu' : 'Minggu') . "). Sistem tidak melakukan sinkronisasi.";
    }

    $zk->enableDevice();
    $zk->disconnect();
} else {
    echo "Gagal koneksi ke mesin.";
}

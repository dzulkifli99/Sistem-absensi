<?php
require 'koneksi.php';
require 'zklibrary.php';
require 'notifikasi.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Jakarta');

$zk = new ZKLibrary('192.168.1.201', 4370);

if ($zk->connect()) {

    $zk->disableDevice();
    $logs = $zk->getAttendance();

    foreach ($logs as $log) {

        $nis = $log[1];
        $waktu = $log[3];
        $jam_masuk = "07:00:00";
        $batas_telat = "07:15:00"; // lewat ini = terlambat
        $jam_pulang_min = "14:00:00"; // boleh scan pulang mulai sini


        // cek apakah scan ini sudah pernah disimpan
        $cek_scan = mysqli_query(
            $koneksi,
            "SELECT * FROM absensi 
     WHERE NIS='$nis' 
     AND last_scan='$waktu'"
        );

        if (mysqli_num_rows($cek_scan) > 0) {
            continue; // sudah diproses, skip
        }

        $tanggal = date('Y-m-d', strtotime($waktu));
        $jam = date('H:i:s', strtotime($waktu));

        // cek siswa ada
        $cek_siswa = mysqli_query(
            $koneksi,
            "SELECT * FROM data WHERE NIS='$nis'"
        );

        if (mysqli_num_rows($cek_siswa) == 0) continue;

        // cek absensi hari ini
        $cek = mysqli_query(
            $koneksi,
            "SELECT * FROM absensi
         WHERE NIS='$nis' AND tanggal='$tanggal'"
        );

        if (mysqli_num_rows($cek) == 0) {

            // scan pertama = datang
            $status = "Hadir";

            if ($jam > $batas_telat) {
                $status = "Terlambat";
            }

            mysqli_query(
                $koneksi,
                "INSERT INTO absensi
                (NIS,tanggal,jam_datang,jam_pulang,last_scan,status)
                VALUES ('$nis','$tanggal','$jam',NULL,'$waktu','$status')"
            );

            $siswa = mysqli_fetch_assoc($cek_siswa);
            if (!$siswa) continue;

            if ($tanggal != date('Y-m-d')) continue;

            $nama = $siswa['nama'];
            $no_ortu = $siswa['no_hp'];

            $pesan = "Assalamu’alaikum Warahmatullahi Wabarakatuh\n\n" .
                "Pemberitahuan Absensi:\n" .
                "Alhamdulillah, Ananda $nama telah tiba di sekolah dengan selamat pada pukul $jam\n" .
                "Status: Hadir (Tepat Waktu)\n" .
                "Terima kasih atas kedisiplinannya. Semoga kegiatan belajar hari ini lancar.\n\n" .
                "— [SMK AL-MALIKI]";


            echo kirimWA($no_ortu, $pesan); // debug
        } else {

            $row = mysqli_fetch_assoc($cek);

            // kalau sudah ada datang tapi belum pulang
            if (
                $row['jam_pulang'] == NULL
                && $jam >= $jam_pulang_min
            ) {

                mysqli_query(
                    $koneksi,
                    "UPDATE absensi
                    SET jam_pulang='$jam',
                        last_scan='$waktu'
                    WHERE NIS='$nis'
                    AND tanggal='$tanggal'"
                );

                // kirim notif pulang
                $siswa = mysqli_fetch_assoc($cek_siswa);
                if ($siswa) {
                    $nama = $siswa['nama'];
                    $no_ortu = $siswa['no_hp'];

                    $pesan = "Assalamu’alaikum Warahmatullahi Wabarakatuh\n\n" .
                        "Pemberitahuan Absensi:\n" .
                        "Ananda $nama telah pulang pada pukul $jam.\n" .
                        "Semoga selamat sampai rumah.\n\n" .
                        "— [SMK AL-MALIKI]";

                    echo kirimWA($no_ortu, $pesan);
                }
            }
        }
    }

    $zk->enableDevice();
    $zk->disconnect();

    echo "Sync sukses";
} else {
    echo "Gagal koneksi";
}

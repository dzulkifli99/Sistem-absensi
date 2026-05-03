<?php
header('Content-Type: application/json');

require 'koneksi.php';
require 'zklibrary.php';

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);

// Konfigurasi IP Mesin Fingerprint (Sama seperti di synch_absen.php)
$devices = [
    ['ip' => '192.168.1.201', 'port' => 4370],
    ['ip' => '192.168.1.202', 'port' => 4370] // Tambahkan IP mesin kedua di sini
];

// Ambil semua data siswa dari database
$query = mysqli_query($koneksi, "SELECT * FROM data");
$siswa_data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $siswa_data[] = $row;
}

$connected_devices = 0;
$failed_devices = [];

foreach ($devices as $dev) {
    $zk = new ZKLibrary($dev['ip'], $dev['port']);
    if ($zk->connect()) {
        $zk->disableDevice();

        // Kirim data tiap siswa ke mesin
        foreach ($siswa_data as $row) {
            $nis = $row['id_siswa'];
            $nama = $row['nama'];
            
            // setUser(uid, userid, name, password, role)
            $zk->setUser($nis, $nis, $nama, "", 0);
        }

        $zk->enableDevice();
        $zk->disconnect();
        $connected_devices++;
    } else {
        $failed_devices[] = $dev['ip'];
    }
}

if ($connected_devices > 0) {
    $pesan = "Berhasil mengirim " . count($siswa_data) . " data siswa ke $connected_devices mesin.";
    if (count($failed_devices) > 0) {
        $pesan .= " Gagal terhubung ke: " . implode(", ", $failed_devices);
    }

    echo json_encode([
        'status'  => 'success',
        'title'   => 'Berhasil Terkirim',
        'message' => $pesan
    ]);
} else {
    echo json_encode([
        'status'  => 'error',
        'title'   => 'Koneksi Gagal',
        'message' => 'Tidak dapat terhubung ke semua mesin fingerprint (' . implode(", ", $failed_devices) . '). Pastikan mesin menyala dan terhubung ke jaringan.'
    ]);
}

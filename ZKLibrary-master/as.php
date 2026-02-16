<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'zklibrary.php';

$ip = '192.168.1.202';
$zk = new ZKLibrary($ip, 4370);

echo "<h3>Menghubungkan ke Mesin Fingerprint...</h3>";

if ($zk->connect()) {
    echo "<p style='color:green'>Koneksi Berhasil!</p>";

    // Coba ambil versi OS mesin sebagai test
    $version = $zk->getVersion();
    echo "Versi Mesin: " . $version . "<br>";

    $zk->disconnect();
} else {
    echo "<p style='color:red'>Koneksi Gagal! Periksa kabel LAN atau IP Mesin.</p>";
}

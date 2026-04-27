<?php
require 'zklibrary.php';
include "koneksi.php";

// // koneksi database
// $conn = mysqli_connect("localhost", "root", "", "siswa");

// koneksi mesin fingerprint
$ip = "192.168.1.201";
$zk = new ZKLibrary($ip, 4370);

// ngambil semua data dari database
if ($zk->connect()) {

    echo "Koneksi ke mesin berhasil<br>";

    $zk->disableDevice();

    // ambil semua siswa dari database
    $query = mysqli_query($koneksi, "SELECT * FROM data");

    while ($row = mysqli_fetch_assoc($query)) {

        $nis = $row['NIS'];
        $nama = $row['nama'];

        // kirim ke mesin
        $result = $zk->setUser($nis, $nis, $nama, "", 0);

        echo "Kirim user: $nama ($nis) <br>";
    }

    $zk->enableDevice();
    $zk->disconnect();

    echo "<br>Semua data berhasil dikirim!";
}

// jika tambah 1 data
// if ($zk->connect()) {

//     $zk->disableDevice();

//     $result = $zk->setUser(12, "12345", "TEST", "", 0);
//     var_dump($result);

//     $users = $zk->getUser();
//     print_r($users);

//     $zk->enableDevice();
//     $zk->disconnect();

// } else {
//     echo "Koneksi gagal";
// }

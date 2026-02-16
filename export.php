<?php
include "zklibrary.php";
include "koneksi.php";


$zk = new ZKLibrary('192.168.1.202', 4370);

if (!$zk->connect()) {
    die("Gagal konek fingerprint!");
}

// ambil semua siswa dari database
$query = mysqli_query($koneksi, "SELECT * FROM data");

while ($row = mysqli_fetch_assoc($query)) {

    $uid = null; // biarkan mesin auto assign
    $userid = $row['NIS'];
    $name = $row['nama'];
    $password = '';
    $role = 0; // 0 = user biasa

    $zk->setUser($uid, $userid, $name, $password, $role);

    echo "Export: {$row['nama']} berhasil<br>";
}

echo "<br>Semua siswa berhasil dikirim!";

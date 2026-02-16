<?php
require 'zklibrary.php';
include "koneksi.php";

// // koneksi database
// $conn = mysqli_connect("localhost", "root", "", "siswa");

// koneksi fingerprint
$ip = "192.168.1.201";
$zk = new ZKLibrary($ip, 4370);

if ($zk->connect()) {

  $zk->disableDevice();

  $attendance = $zk->getAttendance();

  echo "<table border='1'>
    <tr>
        <th>NIS</th>
        <th>Nama</th>
        <th>Kelas</th>
        <th>Jam Absen</th>
    </tr>";

  foreach ($attendance as $dat) {

    $nis = $dat[1];
    $jam = $dat[3];

    // ambil data siswa dari database
    $q = mysqli_query(
      $koneksi,
      "SELECT nama, kelas 
             FROM data 
             WHERE nis='$nis'"
    );

    $siswa = mysqli_fetch_assoc($q);

    if ($dat) {

      echo "<tr>
                <td>$nis</td>
                <td>{$siswa['nama']}</td>
                <td>{$siswa['kelas']}</td>
                <td>$jam</td>
            </tr>";
    }
  }

  echo "</table>";

  $zk->enableDevice();
  $zk->disconnect();
}

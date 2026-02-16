<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// require 'zklibrary.php';

// // Ganti dengan IP mesin fingerprint kamu
// $ip = '192.168.1.202';
// $zk = new ZKLibrary($ip, 4370);

// echo "Menghubungkan ke $ip...<br>";

// if ($zk->connect()) {
//   echo "Koneksi Berhasil!<br>";
//   $zk->disableDevice(); // Nonaktifkan mesin sebentar saat ambil data

//   $attendance = $zk->getAttendance(); // Ambil data absen

//   echo "<table border='1'>
//             <tr>
//                 <th>UID</th>
//                 <th>ID Siswa (NIS)</th>
//                 <th>Waktu Scan</th>
//             </tr>";

//   foreach ($attendance as $data) {
//     echo "<tr>
//                 <td>{$data[0]}</td>
//                 <td>{$data[1]}</td>
//                 <td>{$data[3]}</td>
//               </tr>";
//   }
//   echo "</table>";

//   // Set timezone agar sesuai dengan lokasi kita (WIB)
//   date_default_timezone_set('Asia/Jakarta');

//   $sekarang = date('H:i'); // Mengambil jam:menit sekarang, misal "19:15"
//   $batas_hadir = "19:30";

//   if ($sekarang < $batas_hadir) {
//     $status = "Hadir";
//     $keterangan = "Tepat Waktu";
//   } else {
//     $status = "Hadir";
//     $keterangan = "Terlambat";
//   }

//   echo "Waktu Scan: $sekarang | Status: $status ($keterangan)";


//   $zk->enableDevice();
//   $zk->disconnect();
// } else {
//   echo "Koneksi Gagal! Pastikan IP benar dan mesin menyala.";
// }

include "zklibrary.php";
include "koneksi.php";

$zk = new ZKLibrary('192.168.1.201', 4370);
$zk->connect();

$data = $zk->getAttendance();

foreach ($data as $absen) {

  $nis = $absen['id'];
  $datetime = $absen['timestamp'];

  $tanggal = date("Y-m-d", strtotime($datetime));
  $jam = date("H:i:s", strtotime($datetime));

  $cek = mysqli_query(
    $conn,
    "SELECT * FROM absensi 
         WHERE nis='$nis' AND tanggal='$tanggal'"
  );

  if (mysqli_num_rows($cek) == 0) {
    // absen datang
    mysqli_query(
      $conn,
      "INSERT INTO absensi 
            (nis, tanggal, jam_datang, status)
            VALUES ('$nis','$tanggal','$jam','hadir')"
    );
  } else {
    // update jam pulang
    mysqli_query(
      $conn,
      "UPDATE absensi 
             SET jam_pulang='$jam' 
             WHERE nis='$nis' 
             AND tanggal='$tanggal'"
    );
  }
}

<?php
include "koneksi.php";
$tgl = date('Y-m-d');

if (isset($_POST['nis']) && isset($_POST['status'])) {
    $nis = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);

    // Cek apakah data absensi hari ini sudah ada atau belum
    $cek = mysqli_query($koneksi, "SELECT * FROM absensi WHERE NIS='$nis' AND tanggal='$tgl'");

    if (mysqli_num_rows($cek) > 0) {
        // Jika sudah ada, kita UPDATE
        $sql = "UPDATE absensi SET status = '$status' WHERE NIS = '$nis' AND tanggal = '$tgl'";
    } else {
        // Jika belum ada (misal dari Belum Absen jadi Izin), kita INSERT
        $sql = "INSERT INTO absensi (NIS, tanggal, status) VALUES ('$nis', '$tgl', '$status')";
    }

    if (mysqli_query($koneksi, $sql)) {
        echo "success";
    } else {
        echo "error";
    }
}

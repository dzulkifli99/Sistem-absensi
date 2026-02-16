<?php

$koneksi = mysqli_connect("localhost", "root", "", "siswa");

// Hanya jalankan kode ini JIKA tombol login sudah diklik
if (isset($_POST['login'])) {
    // Sekarang PHP tidak akan protes karena data sudah dikirim melalui POST
    $username = mysqli_real_escape_string($koneksi, $_POST['username']) ?? '';
    $password = mysqli_real_escape_string($koneksi, $_POST['password']) ?? '';

    $q = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = mysqli_query($koneksi, $q);

    if (mysqli_num_rows($result) > 0) {

        header("Location: dashboard.php");
        exit();
    } else {
        echo "<script>alert('Gagal!');</script>";
    }
}

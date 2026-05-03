<?php
session_start();
include "koneksi.php";

header('Content-Type: application/json');

if (!isset($_SESSION['is_login'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hari = mysqli_real_escape_string($koneksi, $_POST['hari']);
    $jam_masuk = mysqli_real_escape_string($koneksi, $_POST['jam_masuk']);
    $batas_masuk = mysqli_real_escape_string($koneksi, $_POST['batas_masuk']);
    $toleransi_terlambat = (int)$_POST['toleransi_terlambat'];
    $jam_pulang = mysqli_real_escape_string($koneksi, $_POST['jam_pulang']);
    $batas_pulang = mysqli_real_escape_string($koneksi, $_POST['batas_pulang']);

    // Cek apakah hari sudah ada
    $cek = mysqli_query($koneksi, "SELECT id FROM setting WHERE hari='$hari'");
    if (mysqli_num_rows($cek) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Hari tersebut sudah ada di pengaturan!']);
        exit();
    }

    $sql = "INSERT INTO setting (hari, jam_masuk, batas_masuk, toleransi_terlambat, jam_pulang, batas_pulang) 
            VALUES ('$hari', '$jam_masuk', '$batas_masuk', $toleransi_terlambat, '$jam_pulang', '$batas_pulang')";
    
    if (mysqli_query($koneksi, $sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Hari kerja baru berhasil ditambahkan!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan: ' . mysqli_error($koneksi)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
}
?>

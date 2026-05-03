<?php
session_start();
include "koneksi.php";

header('Content-Type: application/json');

if (!isset($_SESSION['is_login'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];

    $sql = "DELETE FROM setting WHERE id = $id";
    if (mysqli_query($koneksi, $sql)) {
        if (mysqli_affected_rows($koneksi) > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Hari kerja berhasil dihapus dari pengaturan!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus: ' . mysqli_error($koneksi)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
}
?>

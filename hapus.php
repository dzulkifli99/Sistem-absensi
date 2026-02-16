<?php
include "koneksi.php";
if (isset($_GET['id']) && $_GET['proses'] == 'hapus') {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    $query = mysqli_query($koneksi, "DELETE FROM data WHERE NIS='$id'");

    if ($query) {
        // Kirim respon sukses ke JavaScript
        echo json_encode(['status' => 'success']);
    } else {
        // Kirim respon gagal
        echo json_encode(['status' => 'error', 'message' => mysqli_error($koneksi)]);
    }
    exit;
}

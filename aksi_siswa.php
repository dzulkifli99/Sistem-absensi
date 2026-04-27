<?php
include "koneksi.php";

// PROSES HAPUS (Menerima AJAX request dari JavaScript)
if (isset($_GET['id']) && isset($_GET['proses']) && $_GET['proses'] == 'hapus') {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = mysqli_query($koneksi, "DELETE FROM data WHERE NIS='$id'");
    
    if ($query) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($koneksi)]);
    }
    exit;
}

// PROSES TAMBAH 
if (isset($_POST['tambah'])) {
    $nis = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $hp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);

    // Cek duplikasi NIS jika perlu
    $cek = mysqli_query($koneksi, "SELECT NIS FROM data WHERE NIS='$nis'");
    if(mysqli_num_rows($cek) > 0){
        echo "<script>alert('Gagal! NIS sudah terdaftar.'); window.location='data_siswa.php';</script>";
        exit;
    }

    $query = mysqli_query($koneksi, "INSERT INTO data (NIS, nama, kelas, no_hp) VALUES ('$nis', '$nama', '$kelas', '$hp')");
    
    if ($query) {
        echo "<script>alert('Data siswa berhasil ditambahkan!'); window.location='data_siswa.php';</script>";
    } else {
        echo "<script>alert('Gagal menambah data: " . mysqli_error($koneksi) . "'); window.location='data_siswa.php';</script>";
    }
    exit;
}

// PROSES EDIT
if (isset($_POST['edit'])) {
    $nis_lama = mysqli_real_escape_string($koneksi, $_POST['nis_lama']);
    $nis = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $hp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);

    $query = mysqli_query($koneksi, "UPDATE data SET NIS='$nis', nama='$nama', kelas='$kelas', no_hp='$hp' WHERE NIS='$nis_lama'");
    
    if ($query) {
        echo "<script>alert('Data siswa berhasil diubah!'); window.location='data_siswa.php';</script>";
    } else {
        echo "<script>alert('Gagal mengubah data: " . mysqli_error($koneksi) . "'); window.location='data_siswa.php';</script>";
    }
    exit;
}
?>

<?php
include "koneksi.php";
header('Content-Type: application/json');

// ─── HAPUS (AJAX GET) ─────────────────────────────────────────────────────────
if (isset($_GET['proses']) && $_GET['proses'] === 'hapus' && isset($_GET['id'])) {
    $id    = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = mysqli_query($koneksi, "DELETE FROM data WHERE NIS='$id'");

    if ($query) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($koneksi)]);
    }
    exit;
}

// ─── TAMBAH (AJAX POST) ───────────────────────────────────────────────────────
if (isset($_POST['tambah'])) {
    $nis   = mysqli_real_escape_string($koneksi, trim($_POST['nis']));
    $nama  = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $kelas = mysqli_real_escape_string($koneksi, trim($_POST['kelas']));
    $hp    = mysqli_real_escape_string($koneksi, trim($_POST['no_hp']));

    // Cek duplikasi NIS
    $cek = mysqli_query($koneksi, "SELECT NIS FROM data WHERE NIS='$nis'");
    if (mysqli_num_rows($cek) > 0) {
        echo json_encode(['status' => 'error', 'message' => "NIS $nis sudah terdaftar di sistem!"]);
        exit;
    }

    $query = mysqli_query($koneksi, "INSERT INTO data (NIS, nama, kelas, no_hp) VALUES ('$nis', '$nama', '$kelas', '$hp')");

    if ($query) {
        echo json_encode(['status' => 'success', 'message' => "Data siswa <b>$nama</b> berhasil ditambahkan!"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan: ' . mysqli_error($koneksi)]);
    }
    exit;
}

// ─── EDIT (AJAX POST) ─────────────────────────────────────────────────────────
if (isset($_POST['edit'])) {
    $nis_lama = mysqli_real_escape_string($koneksi, trim($_POST['nis_lama']));
    $nis      = mysqli_real_escape_string($koneksi, trim($_POST['nis']));
    $nama     = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $kelas    = mysqli_real_escape_string($koneksi, trim($_POST['kelas']));
    $hp       = mysqli_real_escape_string($koneksi, trim($_POST['no_hp']));

    // Jika NIS diubah, cek apakah NIS baru sudah dipakai siswa lain
    if ($nis !== $nis_lama) {
        $cek = mysqli_query($koneksi, "SELECT NIS FROM data WHERE NIS='$nis'");
        if (mysqli_num_rows($cek) > 0) {
            echo json_encode(['status' => 'error', 'message' => "NIS $nis sudah digunakan siswa lain!"]);
            exit;
        }
    }

    $query = mysqli_query($koneksi, "UPDATE data SET NIS='$nis', nama='$nama', kelas='$kelas', no_hp='$hp' WHERE NIS='$nis_lama'");

    if ($query) {
        echo json_encode(['status' => 'success', 'message' => "Data siswa <b>$nama</b> berhasil diperbarui!"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui: ' . mysqli_error($koneksi)]);
    }
    exit;
}

// Jika tidak ada proses yang cocok
echo json_encode(['status' => 'error', 'message' => 'Aksi tidak dikenali.']);

<?php
include "koneksi.php";
header('Content-Type: application/json');

// ─── HAPUS (AJAX GET) ─────────────────────────────────────────────────────────
if (isset($_GET['proses']) && $_GET['proses'] === 'hapus' && isset($_GET['id'])) {
    $id    = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = mysqli_query($koneksi, "DELETE FROM data WHERE id_siswa='$id'");

    if ($query) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($koneksi)]);
    }
    exit;
}

// ─── TAMBAH (AJAX POST) ───────────────────────────────────────────────────────
if (isset($_POST['tambah'])) {
    $id_siswa       = mysqli_real_escape_string($koneksi, trim($_POST['id_siswa']));
    $nis            = mysqli_real_escape_string($koneksi, trim($_POST['nis'] ?? ''));
    $nisn           = mysqli_real_escape_string($koneksi, trim($_POST['nisn'] ?? ''));
    $nama           = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $ttl            = mysqli_real_escape_string($koneksi, trim($_POST['tempat_tgl_lahir'] ?? ''));
    $nik            = mysqli_real_escape_string($koneksi, trim($_POST['nik'] ?? ''));
    $alamat         = mysqli_real_escape_string($koneksi, trim($_POST['alamat'] ?? ''));
    $kelas          = mysqli_real_escape_string($koneksi, trim($_POST['kelas']));
    $hp             = mysqli_real_escape_string($koneksi, trim($_POST['no_hp']));

    // Cek duplikasi id_siswa
    $cek = mysqli_query($koneksi, "SELECT id_siswa FROM data WHERE id_siswa='$id_siswa'");
    if (mysqli_num_rows($cek) > 0) {
        echo json_encode(['status' => 'error', 'message' => "ID Sistem <b>$id_siswa</b> sudah terdaftar di sistem!"]);
        exit;
    }

    $query = mysqli_query($koneksi, "INSERT INTO data (id_siswa, nis, nisn, nama, tempat_tgl_lahir, nik, alamat, kelas, no_hp)
        VALUES ('$id_siswa', '$nis', '$nisn', '$nama', '$ttl', '$nik', '$alamat', '$kelas', '$hp')");

    if ($query) {
        echo json_encode(['status' => 'success', 'message' => "Data siswa <b>$nama</b> berhasil ditambahkan!"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan: ' . mysqli_error($koneksi)]);
    }
    exit;
}

// ─── EDIT (AJAX POST) ─────────────────────────────────────────────────────────
if (isset($_POST['edit'])) {
    $nis_lama       = mysqli_real_escape_string($koneksi, trim($_POST['nis_lama']));
    $id_siswa       = mysqli_real_escape_string($koneksi, trim($_POST['id_siswa']));
    $nis            = mysqli_real_escape_string($koneksi, trim($_POST['nis'] ?? ''));
    $nisn           = mysqli_real_escape_string($koneksi, trim($_POST['nisn'] ?? ''));
    $nama           = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $ttl            = mysqli_real_escape_string($koneksi, trim($_POST['tempat_tgl_lahir'] ?? ''));
    $nik            = mysqli_real_escape_string($koneksi, trim($_POST['nik'] ?? ''));
    $alamat         = mysqli_real_escape_string($koneksi, trim($_POST['alamat'] ?? ''));
    $kelas          = mysqli_real_escape_string($koneksi, trim($_POST['kelas']));
    $hp             = mysqli_real_escape_string($koneksi, trim($_POST['no_hp']));

    // Jika id_siswa diubah, cek apakah id_siswa baru sudah dipakai siswa lain
    if ($id_siswa !== $nis_lama) {
        $cek = mysqli_query($koneksi, "SELECT id_siswa FROM data WHERE id_siswa='$id_siswa'");
        if (mysqli_num_rows($cek) > 0) {
            echo json_encode(['status' => 'error', 'message' => "ID Sistem <b>$id_siswa</b> sudah digunakan siswa lain!"]);
            exit;
        }
    }

    $query = mysqli_query($koneksi, "UPDATE data SET
        id_siswa='$id_siswa', nis='$nis', nisn='$nisn', nama='$nama',
        tempat_tgl_lahir='$ttl', nik='$nik', alamat='$alamat',
        kelas='$kelas', no_hp='$hp'
        WHERE id_siswa='$nis_lama'");

    if ($query) {
        echo json_encode(['status' => 'success', 'message' => "Data siswa <b>$nama</b> berhasil diperbarui!"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui: ' . mysqli_error($koneksi)]);
    }
    exit;
}

// ─── INPUT IZIN/SAKIT (AJAX POST) ─────────────────────────────────────────────
if (isset($_POST['proses']) && $_POST['proses'] === 'input_izin') {
    $nis    = mysqli_real_escape_string($koneksi, trim($_POST['nis']));
    $status = mysqli_real_escape_string($koneksi, trim($_POST['status'])); // 'Izin' atau 'Sakit'
    $tanggal = date('Y-m-d');
    $jam     = date('H:i:s');

    // Pastikan status valid
    if ($status !== 'Izin' && $status !== 'Sakit') {
        echo json_encode(['status' => 'error', 'message' => 'Status tidak valid!']);
        exit;
    }

    // Cek apakah hari ini sudah diabsen
    $cek = mysqli_query($koneksi, "SELECT id FROM absensi WHERE id_siswa='$nis' AND tanggal='$tanggal'");
    if (mysqli_num_rows($cek) > 0) {
        // Update jika sudah ada record
        $query = mysqli_query($koneksi, "UPDATE absensi SET status='$status', jam_datang='$jam' WHERE id_siswa='$nis' AND tanggal='$tanggal'");
    } else {
        // Insert jika belum ada
        $query = mysqli_query($koneksi, "INSERT INTO absensi (id_siswa, tanggal, jam_datang, status) VALUES ('$nis', '$tanggal', '$jam', '$status')");
    }

    if ($query) {
        echo json_encode(['status' => 'success', 'message' => "Keterangan $status berhasil disimpan untuk hari ini!"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan keterangan: ' . mysqli_error($koneksi)]);
    }
    exit;
}

// Jika tidak ada proses yang cocok
echo json_encode(['status' => 'error', 'message' => 'Aksi tidak dikenali.']);

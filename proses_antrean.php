<?php
// c:\xampp\htdocs\Sistem-absensi\proses_antrean.php
include "koneksi.php";
include "helper_wa.php";

header('Content-Type: application/json');

// Jika request adalah bersihkan riwayat
if (isset($_GET['action']) && $_GET['action'] == 'clean') {
    mysqli_query($koneksi, "DELETE FROM wa_queue WHERE status IN ('sent', 'failed')");
    echo json_encode(['status' => 'success']);
    exit();
}

// 1. Ambil 1 pesan pending yang paling lama (FIFO)
$q = mysqli_query($koneksi, "SELECT * FROM wa_queue WHERE status = 'pending' ORDER BY id ASC LIMIT 1");

if (mysqli_num_rows($q) == 0) {
    echo json_encode([
        'status' => 'empty',
        'message' => 'Tidak ada antrean.'
    ]);
    exit();
}

$row = mysqli_fetch_assoc($q);
$id = $row['id'];
$nomor = $row['nomor'];
$pesan = $row['pesan'];

// 2. Eksekusi kirim
$kirim_sukses = sendWADirectly($nomor, $pesan);

// 3. Update status
$status_baru = $kirim_sukses ? 'sent' : 'failed';
mysqli_query($koneksi, "UPDATE wa_queue SET status = '$status_baru' WHERE id = $id");

if ($kirim_sukses) {
    echo json_encode([
        'status' => 'success',
        'message' => "Pesan ke $nomor berhasil dikirim."
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => "Gagal mengirim ke $nomor. Pastikan token Fonnte aktif."
    ]);
}
?>

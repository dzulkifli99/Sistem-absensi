<?php
session_start();
include "koneksi.php";
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Selalu return JSON
header('Content-Type: application/json');

// ─── Validasi: pastikan ada file yang diupload ───────────────────────────────
if (!isset($_FILES['file_excel']) || $_FILES['file_excel']['error'] !== UPLOAD_ERR_OK) {
    $kode = $_FILES['file_excel']['error'] ?? 99;
    echo json_encode([
        'status'  => 'error',
        'message' => "Upload gagal! Kode error: $kode. Pastikan file dipilih dan ukurannya tidak terlalu besar."
    ]);
    exit;
}

$namaFile = $_FILES['file_excel']['name'];
$tmpFile  = $_FILES['file_excel']['tmp_name'];
$ekstensi = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

// ─── Validasi ekstensi ────────────────────────────────────────────────────────
$ekstensiDiizinkan = ['xlsx', 'xls', 'csv'];
if (!in_array($ekstensi, $ekstensiDiizinkan)) {
    echo json_encode([
        'status'  => 'error',
        'message' => "Format file tidak didukung! File yang diterima: .xlsx, .xls, .csv. File Anda: .$ekstensi"
    ]);
    exit;
}

// ─── Baca spreadsheet dengan PhpSpreadsheet ───────────────────────────────────
try {
    if ($ekstensi === 'csv') {
        $reader = IOFactory::createReader('Csv');
        $reader->setDelimiter(',');
        $reader->setEnclosure('"');
        $reader->setSheetIndex(0);
    } else {
        $reader = IOFactory::createReaderForFile($tmpFile);
    }

    $reader->setReadDataOnly(true);
    $spreadsheet = $reader->load($tmpFile);
    $sheet       = $spreadsheet->getActiveSheet();
    $highestRow  = $sheet->getHighestRow();

} catch (\Exception $e) {
    echo json_encode([
        'status'  => 'error',
        'message' => "Gagal membaca file: " . $e->getMessage()
    ]);
    exit;
}

// ─── Proses setiap baris (baris 1 = header, mulai dari baris 2) ──────────────
$berhasil   = 0;
$diperbarui = 0;
$dilewati   = 0;
$errors     = [];

for ($baris = 2; $baris <= $highestRow; $baris++) {

    $nis   = trim((string) $sheet->getCell('A' . $baris)->getValue());
    $nama  = trim((string) $sheet->getCell('B' . $baris)->getValue());
    $kelas = trim((string) $sheet->getCell('C' . $baris)->getValue());
    $no_hp = trim((string) $sheet->getCell('D' . $baris)->getValue());

    // Lewati baris yang NIS-nya kosong
    if ($nis === '') {
        $dilewati++;
        continue;
    }

    $nis   = mysqli_real_escape_string($koneksi, $nis);
    $nama  = mysqli_real_escape_string($koneksi, $nama);
    $kelas = mysqli_real_escape_string($koneksi, $kelas);
    $no_hp = mysqli_real_escape_string($koneksi, $no_hp);

    $sql = "INSERT INTO data (NIS, nama, kelas, no_hp)
            VALUES ('$nis', '$nama', '$kelas', '$no_hp')
            ON DUPLICATE KEY UPDATE
                nama  = VALUES(nama),
                kelas = VALUES(kelas),
                no_hp = VALUES(no_hp)";

    if (mysqli_query($koneksi, $sql)) {
        $affected = mysqli_affected_rows($koneksi);
        if ($affected == 2) {
            $diperbarui++;
        } else {
            $berhasil++;
        }
    } else {
        $errors[] = "Baris $baris (NIS: $nis): " . mysqli_error($koneksi);
    }
}

// ─── Return JSON hasil ────────────────────────────────────────────────────────
echo json_encode([
    'status'     => 'success',
    'nama_file'  => $namaFile,
    'berhasil'   => $berhasil,
    'diperbarui' => $diperbarui,
    'dilewati'   => $dilewati,
    'errors'     => $errors,
]);
exit;

<?php
include "koneksi.php";
require 'vendor/autoload.php'; // Panggil library PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_FILES['file_excel']['name'])) {
    $path = $_FILES['file_excel']['tmp_name'];

    try {
        $reader = IOFactory::createReaderForFile($path);
        $spreadsheet = $reader->load($path);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        $berhasil = 0;
        // Kita mulai dari index 1 (baris ke-2) untuk melewati Header Excel
        for ($i = 1; $i < count($sheetData); $i++) {
            $nis      = $sheetData[$i][0]; // Kolom A
            $nama     = $sheetData[$i][1]; // Kolom B
            $kelas    = $sheetData[$i][2]; // Kolom C
            $jk       = $sheetData[$i][3]; // Kolom D

            // Pastikan NIS tidak kosong
            if (!empty($nis)) {
                $sql = "INSERT INTO data (nis, nama, kelas, jk) 
                        VALUES ('$nis', '$nama', '$kelas', '$jk')
                        ON DUPLICATE KEY UPDATE nama='$nama', kelas='$kelas', jk='$jk'";

                if (mysqli_query($koneksi, $sql)) {
                    $berhasil++;
                }
            }
        }

        echo "<script>
                alert('Berhasil mengimport $berhasil data siswa!');
                window.location.href = 'dashboard.php';
              </script>";
    } catch (Exception $e) {
        die('Error loading file: ' . $e->getMessage());
    }
} else {
    echo "Tidak ada file yang dipilih.";
}

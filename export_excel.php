<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['is_login'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Data Siswa');

// Header Styles
$styleHeader = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'],
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '0D6EFD']
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
];

// Set Headers
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'ID Sistem');
$sheet->setCellValue('C1', 'NIS Sekolah');
$sheet->setCellValue('D1', 'NISN');
$sheet->setCellValue('E1', 'Nama Lengkap');
$sheet->setCellValue('F1', 'Tempat, Tgl Lahir');
$sheet->setCellValue('G1', 'NIK');
$sheet->setCellValue('H1', 'Alamat');
$sheet->setCellValue('I1', 'Kelas');
$sheet->setCellValue('J1', 'No HP');

$sheet->getStyle('A1:J1')->applyFromArray($styleHeader);

// Auto-size columns
foreach (range('A', 'J') as $colID) {
    $sheet->getColumnDimension($colID)->setAutoSize(true);
}

// Fetch Data
$sql = "SELECT * FROM data ORDER BY kelas ASC, nama ASC";
$query = mysqli_query($koneksi, $sql);

$row = 2;
$no = 1;
while ($data = mysqli_fetch_assoc($query)) {
    $sheet->setCellValue('A' . $row, $no++);
    $sheet->setCellValueExplicit('B' . $row, $data['id_siswa'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet->setCellValueExplicit('C' . $row, $data['nis'],      \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet->setCellValueExplicit('D' . $row, $data['nisn'],     \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet->setCellValue('E' . $row, $data['nama']);
    $sheet->setCellValue('F' . $row, $data['tempat_tgl_lahir']);
    $sheet->setCellValueExplicit('G' . $row, $data['nik'],   \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet->setCellValue('H' . $row, $data['alamat']);
    $sheet->setCellValue('I' . $row, $data['kelas']);
    $sheet->setCellValueExplicit('J' . $row, $data['no_hp'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

    // Border for data rows
    $sheet->getStyle('A' . $row . ':J' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    
    $row++;
}

// Download
$filename = "Data_Siswa_" . date('Ymd_His') . ".xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();

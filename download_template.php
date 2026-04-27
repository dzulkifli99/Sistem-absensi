<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

$spreadsheet = new Spreadsheet();
$sheet       = $spreadsheet->getActiveSheet();
$sheet->setTitle('Data Siswa');

// ─── HEADER KOLOM (Baris 1) ───────────────────────────────────────────────────
$headers = ['NIS', 'Nama', 'Kelas', 'No HP'];
foreach ($headers as $col => $judul) {
    $kolom = chr(65 + $col); // A, B, C, D
    $sheet->setCellValue($kolom . '1', $judul);
}

// Style header: background hijau, teks putih, bold, center
$styleHeader = [
    'font' => [
        'bold'  => true,
        'color' => ['argb' => 'FFFFFFFF'],
        'size'  => 12,
    ],
    'fill' => [
        'fillType'   => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FF1D6F42'],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical'   => Alignment::VERTICAL_CENTER,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color'       => ['argb' => 'FF9E9E9E'],
        ],
    ],
];
$sheet->getStyle('A1:D1')->applyFromArray($styleHeader);
$sheet->getRowDimension(1)->setRowHeight(25);

// ─── DATA CONTOH (Baris 2–6) ─────────────────────────────────────────────────
$contohData = [
    ['10001', 'Ahmad Fauzi',     '10 TKJ 1', '081234567890'],
    ['10002', 'Budi Santoso',    '10 TKJ 1', '082345678901'],
    ['10003', 'Citra Dewi',      '10 TKJ 2', '083456789012'],
    ['10004', 'Dina Rahmawati',  '11 RPL 1', '084567890123'],
    ['10005', 'Eko Prasetyo',    '11 RPL 1', ''],
];

$baris = 2;
foreach ($contohData as $row) {
    $sheet->setCellValue('A' . $baris, $row[0]);
    $sheet->setCellValue('B' . $baris, $row[1]);
    $sheet->setCellValue('C' . $baris, $row[2]);
    $sheet->setCellValue('D' . $baris, $row[3]);

    // Style baris data: warna selang-seling
    $bgWarna = ($baris % 2 === 0) ? 'FFF0FFF0' : 'FFFFFFFF';
    $sheet->getStyle("A$baris:D$baris")->applyFromArray([
        'fill' => [
            'fillType'   => Fill::FILL_SOLID,
            'startColor' => ['argb' => $bgWarna],
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color'       => ['argb' => 'FFD3D3D3'],
            ],
        ],
        'alignment' => [
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
    ]);

    $baris++;
}

// ─── Lebar kolom otomatis ─────────────────────────────────────────────────────
$sheet->getColumnDimension('A')->setWidth(12);  // NIS
$sheet->getColumnDimension('B')->setWidth(30);  // Nama
$sheet->getColumnDimension('C')->setWidth(15);  // Kelas
$sheet->getColumnDimension('D')->setWidth(18);  // No HP

// Set kolom NIS sebagai teks (agar angka panjang tidak diformat scientific)
$sheet->getStyle('A2:A1000')->getNumberFormat()->setFormatCode('@');
// Set kolom No HP sebagai teks
$sheet->getStyle('D2:D1000')->getNumberFormat()->setFormatCode('@');

// ─── Freeze baris header ──────────────────────────────────────────────────────
$sheet->freezePane('A2');

// ─── Output ke browser sebagai file .xlsx ─────────────────────────────────────
$namaFile = 'template_import_siswa.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $namaFile . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;

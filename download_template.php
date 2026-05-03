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
$headers = ['ID Sistem', 'NIS Sekolah', 'NISN', 'Nama Lengkap', 'Tempat, Tgl Lahir', 'NIK', 'Alamat', 'Kelas', 'No HP'];
foreach ($headers as $col => $judul) {
    $kolom = chr(65 + $col); // A, B, C, D, ...
    $sheet->setCellValue($kolom . '1', $judul);
}

// Style header: background hijau, teks putih, bold, center
$styleHeader = [
    'font' => [
        'bold'  => true,
        'color' => ['argb' => 'FFFFFFFF'],
        'size'  => 11,
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
$sheet->getStyle('A1:I1')->applyFromArray($styleHeader);
$sheet->getRowDimension(1)->setRowHeight(25);

// ─── DATA CONTOH (Baris 2–4) ─────────────────────────────────────────────────
$contohData = [
    ['10001', '2324001', '3001234567', 'Ahmad Fauzi',    'Jakarta, 12 Jan 2007', '3175012312070001', 'Jl. Merdeka No. 1, Jakarta',  'X TKJ A', '081234567890'],
    ['10002', '2324002', '3001234568', 'Budi Santoso',   'Bandung, 5 Mar 2007',  '3273050307070002', 'Jl. Sudirman No. 5, Bandung', 'X TKJ A', '082345678901'],
    ['10003', '2324003', '3001234569', 'Citra Dewi',     'Surabaya, 20 Sep 2007','3578200907070003', 'Jl. Ahmad Yani No. 10',      'X TKJ B', '083456789012'],
];

$baris = 2;
foreach ($contohData as $row) {
    $cols = ['A','B','C','D','E','F','G','H','I'];
    foreach ($cols as $i => $col) {
        $sheet->setCellValue($col . $baris, $row[$i]);
    }
    $bgWarna = ($baris % 2 === 0) ? 'FFF0FFF0' : 'FFFFFFFF';
    $sheet->getStyle("A$baris:I$baris")->applyFromArray([
        'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $bgWarna]],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD3D3D3']]],
        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
    ]);
    $baris++;
}

// ─── Lebar kolom ─────────────────────────────────────────────────────────────
$sheet->getColumnDimension('A')->setWidth(12);  // ID Sistem
$sheet->getColumnDimension('B')->setWidth(14);  // NIS Sekolah
$sheet->getColumnDimension('C')->setWidth(14);  // NISN
$sheet->getColumnDimension('D')->setWidth(28);  // Nama
$sheet->getColumnDimension('E')->setWidth(25);  // Tempat, Tgl Lahir
$sheet->getColumnDimension('F')->setWidth(20);  // NIK
$sheet->getColumnDimension('G')->setWidth(35);  // Alamat
$sheet->getColumnDimension('H')->setWidth(15);  // Kelas
$sheet->getColumnDimension('I')->setWidth(18);  // No HP

// Set kolom teks agar angka panjang tidak diformat scientific
foreach (['A','B','C','F','I'] as $col) {
    $sheet->getStyle($col . '2:' . $col . '1000')->getNumberFormat()->setFormatCode('@');
}

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

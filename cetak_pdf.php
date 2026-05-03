<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['is_login'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM data ORDER BY kelas ASC, nama ASC";
$query = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Data Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0 0 5px 0;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            font-size: 14px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        td.center {
            text-align: center;
        }
        
        /* Hilangkan tombol saat di-print */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                margin: 0;
                padding: 15px;
            }
        }
        
        .btn-print {
            display: inline-block;
            background-color: #0d6efd;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-bottom: 20px;
            cursor: pointer;
            border: none;
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="text-align: right;">
        <button class="btn-print" onclick="window.print()">Cetak / Save as PDF</button>
    </div>

    <div class="header">
        <h2>Laporan Data Siswa</h2>
        <p>SMK Al-Maliki - Diekstrak pada <?= date('d M Y H:i'); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="8%">ID Sistem</th>
                <th width="10%">NIS</th>
                <th width="10%">NISN</th>
                <th width="22%">Nama Lengkap</th>
                <th width="15%">Tempat, Tgl Lahir</th>
                <th width="13%">NIK</th>
                <th width="18%">Alamat</th>
                <th width="8%">Kelas</th>
                <th width="10%">No. HP</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($query)) {
                echo "<tr>";
                echo "<td class='center'>{$no}</td>";
                echo "<td class='center'>{$row['id_siswa']}</td>";
                echo "<td class='center'>{$row['nis']}</td>";
                echo "<td class='center'>{$row['nisn']}</td>";
                echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                echo "<td>" . htmlspecialchars($row['tempat_tgl_lahir']) . "</td>";
                echo "<td>{$row['nik']}</td>";
                echo "<td>" . htmlspecialchars($row['alamat']) . "</td>";
                echo "<td class='center'>" . htmlspecialchars($row['kelas']) . "</td>";
                echo "<td>{$row['no_hp']}</td>";
                echo "</tr>";
                $no++;
            }
            ?>
        </tbody>
    </table>

</body>
</html>

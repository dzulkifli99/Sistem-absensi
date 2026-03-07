<div class="dropdown">
    <button type="button" class="btn btn-outline-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <?= isset($_GET['kelas']) ? "Kelas " . $_GET['kelas'] : "Cari Kelas"; ?>
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="dashboard.php">Semua Kelas</a></li>
        <li>
            <hr class="dropdown-divider">
        </li>
        <li><a class="dropdown-item" href="dashboard.php?kelas=10">Kelas 10</a></li>
        <li><a class="dropdown-item" href="dashboard.php?kelas=11">Kelas 11</a></li>
        <li><a class="dropdown-item" href="dashboard.php?kelas=12">Kelas 12</a></li>
    </ul>
</div>
<?php
include "koneksi.php";

$tanggal = date('Y-m-d');
$filter_kelas = "";

// Cek apakah user memilih kelas tertentu
if (isset($_GET['kelas']) && $_GET['kelas'] != '') {
    $kelas = mysqli_real_escape_string($koneksi, $_GET['kelas']);
    // Tambahkan kondisi filter untuk SQL
    $filter_kelas = " AND d.kelas = '$kelas' ";
}

$sql = "SELECT d.NIS, d.nama, d.kelas, a.jam_datang, a.status, a.tanggal 
        FROM data d 
        LEFT JOIN absensi a ON d.NIS = a.NIS AND a.tanggal = '$tanggal' 
        WHERE 1=1 $filter_kelas 
        ORDER BY d.kelas, d.nama";

$query = mysqli_query($koneksi, $sql);
?>
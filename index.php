<?php
session_start();
if (isset($_SESSION["is_login"])) {
    header("location:dashboard.php");
}
include "koneksi.php";
// include "sidebar.php";
$tgl_sekarang = date('Y-m-d');
$q_hadir = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM absensi WHERE tanggal='$tgl_sekarang' AND (status='Hadir' )");
$res_hadir = mysqli_fetch_assoc($q_hadir);
$jml_hadir = $res_hadir['jml'];

$q_telat = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM absensi WHERE tanggal='$tgl_sekarang' AND ( status='Terlambat')");
$res_telat = mysqli_fetch_assoc($q_telat);
$jml_telat = $res_telat['jml'];

$q_izin = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM absensi WHERE tanggal='$tgl_sekarang' AND ( status='izin')");
$res_izin = mysqli_fetch_assoc($q_izin);
$jml_izin = $res_izin['jml'];

$q_alpa = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM absensi WHERE tanggal='$tgl_sekarang' AND ( status='alpa')");
$res_alpa = mysqli_fetch_assoc($q_alpa);
$jml_alpa = $res_alpa['jml'];


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>SMALKIS</title>
    <link rel="icon" href="assets/img/smalkis.png" type="image/png" sizes="192x192">
    <link
        href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css"
        rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script
        src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"
        crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">


    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <div class="card-body d-flex justify-content-between align-items-center p-4 bg-dark rounded-4 my-2 shadow">
                    <div>
                        <h1 class="mt-4 text-light">Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active text-light">Dashboard</li>
                        </ol>
                    </div>
                    <div class="text-end">
                        <div class="d-flex align-items-center justify-content-end text-light fw-bold">
                            <i class="fa-solid fa-clock me-2"></i>
                            <h3 id="clock" class="mb-0">00:00:00</h3>
                        </div>
                        <div id="date" class="text-light fw-medium mt-1 mb-2">Memuat Tanggal...</div>
                        <a href="login.php" class="btn btn-outline-light btn-sm px-3 rounded-pill"><i class="fa-solid fa-right-to-bracket me-1"></i> Login Admin</a>
                    </div>
                </div>



                <script>
                    function updateDateTime() {
                        const now = new Date();

                        // 1. Logika Jam Digital
                        const time = now.toLocaleTimeString('id-ID', {
                            hour12: false,
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit'
                        });
                        document.getElementById("clock").innerHTML = time.replace(/\./g, ':');

                        // 2. Logika Tanggal Indonesia
                        const options = {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        };
                        const dateString = now.toLocaleDateString('id-ID', options);
                        document.getElementById("date").innerHTML = dateString;
                    }

                    // Jalankan setiap detik
                    setInterval(updateDateTime, 1000);
                    updateDateTime();
                </script>

                <div class="row text-white">
                    <div class="col-md-3">
                        <div class="card bg-primary shadow">
                            <div class="card-body">
                                <h6>HADIR</h6>
                                <h2><?= $jml_hadir; ?></h2>

                                <i class="fa-solid fa-user-check float-end opacity-50" style="font-size: 40px; margin-top: -40px;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning shadow">
                            <div class="card-body">
                                <h6>TERLAMBAT</h6>
                                <h2><?= $jml_telat; ?></h2>
                                <i class="fa-solid fa-user-minus float-end opacity-50" style="font-size: 40px; margin-top: -40px;"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card bg-success shadow">
                            <div class="card-body">
                                <h6>IZIN</h6>
                                <h2><?= $jml_izin; ?></h2>
                                <float-end class="fa-solid fa-user-xmark float-end opacity-50" style="font-size: 40px; margin-top: -40px;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger shadow">
                            <div class="card-body">
                                <h6>ALPA</h6>
                                <h2><?= $jml_alpa; ?></h2>
                                <i class="fa-solid fa-user-slash float-end opacity-50" style="font-size: 40px; margin-top: -40px;"></i>
                            </div>
                        </div>
                    </div>
                </div>







                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div><i class="fas fa-table me-1"></i> Data Kehadiran Siswa (Publik)</div>
                            <div class="dropdown">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-expanded="false">
                                    Cari Kelas
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="index.php">Semua Kelas</a></li>
                                    <li><a class="dropdown-item" href="index.php?kelas=10">Kelas 10</a></li>
                                    <li><a class="dropdown-item" href="index.php?kelas=11">Kelas 11</a></li>
                                    <li><a class="dropdown-item" href="index.php?kelas=12">Kelas 12</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>


                    <?php
                    $tanggal = date('Y-m-d');

                    $filter_kelas = "";
                    if (isset($_GET['kelas']) && $_GET['kelas'] != '') {
                        $kls = mysqli_real_escape_string($koneksi, $_GET['kelas']);
                        $filter_kelas = " WHERE d.kelas LIKE '%$kls%' ";
                    }

                    $sql = "
SELECT 
d.NIS,
d.nama,
d.kelas,
a.jam_datang,
a.jam_pulang,
a.tanggal,
a.status
FROM data d
LEFT JOIN absensi a 
ON d.NIS = a.NIS 
AND a.tanggal = '$tanggal'
$filter_kelas
ORDER BY d.kelas, d.nama
";

                    $query = mysqli_query($koneksi, $sql);

                    if (!$query) {
                        die(mysqli_error($koneksi));
                    }
                    ?>

                    <div class="card-body">
                        <table id="datatablesSimple">

                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Jam datang</th>
                                    <th>Jam pulang</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <!-- <th>Aksi</th> -->
                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($query)) {
                                    // 1. Masking NIS (Misal: 10123 jadi 10***)
                                    $nis_asli = $row['NIS'];
                                    $nis_sensor = substr($nis_asli, 0, 2) . str_repeat('*', max(0, strlen($nis_asli) - 2));

                                    // 2. Masking Nama (Misal: Budi Santoso jadi Budi S******)
                                    $nama_asli = $row['nama'];
                                    $exp_nama = explode(' ', $nama_asli);
                                    $nama_depan = $exp_nama[0];
                                    $nama_sensor = $nama_depan . " " . str_repeat('*', max(3, strlen($nama_asli) - strlen($nama_depan) - 1));

                                    // 3. Status Badge Color
                                    $status = $row['status'] ?: 'Belum Absen';
                                    $bg_color = "secondary";
                                    if ($status == 'Hadir') $bg_color = "success";
                                    if ($status == 'Terlambat') $bg_color = "warning text-dark";
                                    if ($status == 'Alpa') $bg_color = "danger";
                                    if ($status == 'Sakit' || $status == 'Izin') $bg_color = "info text-dark";
                                ?>

                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $nis_sensor ?></td>
                                        <td><?= $nama_sensor ?></td>
                                        <td><?= $row['kelas'] ?></td>
                                        <td><?= $row['jam_datang'] ?: '-' ?></td>
                                        <td><?= $row['jam_pulang'] ?: '-' ?></td>
                                        <td><?= date('d-m-Y', strtotime($tanggal)) ?></td>
                                        <td><span class="badge bg-<?= $bg_color ?>"><?= $status ?></span></td>
                                    </tr>

                                <?php } ?>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </main>
        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid px-4">
                <div
                    class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; SMK AL-MALIKI SUKODONO</div>
                    <div>
                        <a href="#">Privacy Policy</a>
                        &middot;
                        <a href="#">Terms &amp; Conditions</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    </div>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"
        crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>

    <!-- SCRIPT DAERAH PUBLIK: TINGGALKAN KOSONG TANPA SINKRONISASI -->

</body>

</html>
<?php
session_start();
if (!isset($_SESSION["is_login"])) {
    header("Location: login.php");
    exit();
}

include "koneksi.php";

// 1. Ambil Parameter Filter
// Jika tidak ada di URL, pakai tanggal hari ini
$tgl_awal = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-d');
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : date('Y-m-d');
$kelas_filter = isset($_GET['kelas']) ? $_GET['kelas'] : '';

// 2. Bangun Array Tanggal (Mengabaikan Sabtu & Minggu)
$dates = [];
$start = new DateTime($tgl_awal);
$end = new DateTime($tgl_akhir);
$end->modify('+1 day'); // Supaya tanggal akhir ikut terhitung

$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($start, $interval, $end);

foreach ($period as $dt) {
    if ($dt->format('N') < 6) { // 1-5 adalah Senin-Jumat
        $dates[] = $dt->format('Y-m-d');
    }
}

// 3. Ambil Data Siswa (Filter Kelas Jika Ada)
$filter_sql = "";
if ($kelas_filter != '') {
    $kls = mysqli_real_escape_string($koneksi, $kelas_filter);
    $filter_sql = " WHERE kelas = '$kls' ";
}
$sql_siswa = "SELECT NIS, nama, kelas FROM data $filter_sql ORDER BY kelas, nama";
$query_siswa = mysqli_query($koneksi, $sql_siswa);

// 4. Ambil Data Absensi Rentang Tanggal
$sql_absen = "SELECT NIS, tanggal, status FROM absensi WHERE tanggal >= '$tgl_awal' AND tanggal <= '$tgl_akhir'";
$query_absen = mysqli_query($koneksi, $sql_absen);

$data_absen = [];
while ($row = mysqli_fetch_assoc($query_absen)) {
    // Kelompokkan dalam array: $data_absen[nis][tanggal]
    $data_absen[$row['NIS']][$row['tanggal']] = $row['status'];
}

// 5. Header dan Sidebar
include "header.php";
include "sidebar.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Rekap Absensi - SMK Al-Maliki</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <style>
        /* Desain Print CSS */
        @media print {
            @page {
                size: landscape;
                margin: 0mm;
                /* Menghilangkan URL / header footer bawaan browser secara paksa */
            }

            body * {
                visibility: hidden;
            }

            /* Sembunyikan elemen scroll agar print bersih dan RESET margin sidebar */
            html,
            body,
            #layoutSidenav_content,
            #layoutSidenav {
                overflow: visible !important;
                height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
                position: static !important;
            }

            #area-cetak,
            #area-cetak * {
                visibility: visible;
            }

            #area-cetak {
                position: static !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            .table-bordered th,
            .table-bordered td {
                border: 1px solid black !important;
                padding: 2px !important;
                font-size: 9px !important;
                /* Teks lebih kecil dan padding minim agar muat banyak kolom */
            }

            .table th {
                background-color: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
                white-space: nowrap !important;
                padding: 2px !important;
            }
            
            .table td {
                white-space: nowrap !important;
            }

            /* Kolom Nama bisa mengambil sisa ruang tapi tidak terlalu lebar */
            .nama-siswa {
                white-space: normal !important;
                min-width: 100px;
            }

            .table th {
                background-color: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
            }

            .text-center {
                text-align: center !important;
            }

            .keterangan-warna {
                font-size: 11px;
                margin-bottom: 10px;
            }

            /* Footer Kustom untuk menggantikan localhost */
            .cetak-footer {
                display: block !important;
                position: fixed;
                bottom: 5mm;
                left: 1cm;
                font-size: 10px;
                font-weight: bold;
                visibility: visible !important;
            }
        }

        .cetak-footer {
            display: none;
            /* Sembunyikan kalau sedang dilihat di layar */
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <!-- Footer Kustom Cetak akan muncul di pojok bawah kertas -->
    <div class="cetak-footer">SMK AL-MALIKI SUKODONO</div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 no-print">
                <div class="card-body d-flex justify-content-between align-items-center p-4 bg-dark rounded-4 my-2 shadow">
                    <div>
                        <h1 class="mt-4 text-light">Rekap Absensi</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item "><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active text-light">Rekap per Kelas</li>
                        </ol>
                    </div>
                    <div class="text-end">
                        <div class="d-flex align-items-center justify-content-end text-light fw-bold">
                            <i class="far fa-clock me-2"></i>
                            <h3 id="clock" class="mb-0">00:00:00</h3>
                        </div>
                        <div id="date" class="text-light fw-medium mt-1">Memuat Tanggal...</div>
                    </div>
                </div>

                <!-- Script Jam Digital -->
                <script>
                    function updateDateTime() {
                        const now = new Date();
                        const time = now.toLocaleTimeString('id-ID', {
                            hour12: false,
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit'
                        });
                        document.getElementById("clock").innerHTML = time.replace(/\./g, ':');

                        const options = {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        };
                        document.getElementById("date").innerHTML = now.toLocaleDateString('id-ID', options);
                    }
                    setInterval(updateDateTime, 1000);
                    updateDateTime();
                </script>

                <!-- Form Filter (Tidak ikut di-print) -->
                <div class="card mb-4 shadow">
                    <div class="card-header bg-primary text-white font-weight-bold">
                        <i class="fas fa-filter me-1"></i> Filter Pencarian Rekap
                    </div>
                    <div class="card-body">
                        <form method="GET" action="rekap.php" class="row gx-3 align-items-center">
                            <div class="col-md-3 mb-2">
                                <label for="tgl_awal" class="form-label">Tanggal Awal :</label>
                                <input type="date" class="form-control" name="tgl_awal" id="tgl_awal" value="<?= htmlspecialchars($tgl_awal) ?>" required>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="tgl_akhir" class="form-label">Tanggal Akhir :</label>
                                <input type="date" class="form-control" name="tgl_akhir" id="tgl_akhir" value="<?= htmlspecialchars($tgl_akhir) ?>" required>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="kelas" class="form-label">Kelas :</label>
                                <select class="form-select" name="kelas" id="kelas">
                                    <option value="">-- Semua Kelas --</option>
                                    <?php
                                    $sql_kelas = "SELECT DISTINCT kelas FROM data ORDER BY kelas ASC";
                                    $query_kelas = mysqli_query($koneksi, $sql_kelas);
                                    if ($query_kelas) {
                                        while ($row_kelas = mysqli_fetch_assoc($query_kelas)) {
                                            $selected = ($kelas_filter == $row_kelas['kelas']) ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($row_kelas['kelas']) . '" ' . $selected . '>' . htmlspecialchars($row_kelas['kelas']) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2 mt-4 text-end">
                                <button type="submit" class="btn btn-success w-100"><i class="fas fa-search me-1"></i>Tampilkan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- AREA CETAK MULAI DI SINI -->
            <div class="container-fluid px-4" id="area-cetak">
                <div class="card mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h3 class="text-center mb-0"><b>LAPORAN REKAPITULASI ABSENSI SISWA</b></h3>
                        <h4 class="text-center mb-0">SMK Al-Maliki</h4>
                        <div class="mt-3">
                            <table class="table table-borderless table-sm w-auto mb-1">
                                <tr>
                                    <th class="p-0 pe-3">Tanggal Laporan</th>
                                    <td class="p-0">: <?= date('d-m-Y', strtotime($tgl_awal)) ?> s.d <?= date('d-m-Y', strtotime($tgl_akhir)) ?></td>
                                </tr>
                                <tr>
                                    <th class="p-0 pe-3">Kelas</th>
                                    <td class="p-0">: <?= $kelas_filter ? htmlspecialchars($kelas_filter) : 'Semua Kelas' ?></td>
                                </tr>
                            </table>
                        </div>

                        <!-- Keterangan Status -->
                        <div class="keterangan-warna mt-2">
                            <strong>Keterangan Status : </strong>
                            <span class="badge bg-success text-white me-1">H : Hadir</span>
                            <span class="badge bg-warning text-dark me-1">T : Terlambat</span>
                            <span class="badge bg-info text-dark me-1">I : Izin / Sakit</span>
                            <span class="badge bg-danger text-white me-1">A : Alpa</span>
                            <span class="badge bg-secondary text-white me-1">- : Belum Absen</span>
                            <button class="btn btn-sm btn-outline-dark float-end no-print" onclick="window.print()"><i class="fas fa-print me-1"></i> Cetak Dokumen</button>
                        </div>
                    </div>

                    <div class="card-body p-0 p-lg-3">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover align-middle">
                                <thead class="text-center bg-light">
                                    <tr>
                                        <th rowspan="2" class="align-middle">No</th>
                                        <th rowspan="2" class="align-middle nama-siswa">Nama</th>
                                        <th colspan="<?= count($dates) ?>" class="align-middle">Tanggal Pertemuan</th>
                                        <th colspan="4" class="align-middle">Total</th>
                                    </tr>
                                    <tr>
                                        <!-- Header Tanggal -->
                                        <?php if (count($dates) == 0): ?>
                                            <th>-</th>
                                        <?php else: ?>
                                            <?php foreach ($dates as $d): ?>
                                                <th title="<?= $d ?>">
                                                    <?= date('d/m', strtotime($d)) ?>
                                                </th>
                                            <?php endforeach; ?>
                                        <?php endif; ?>

                                        <!-- Header Rekap Total -->
                                        <!-- <th class="text-success">H</th> -->
                                        <th class="text-warning">T</th>
                                        <th class="text-info">I</th>
                                        <th class="text-danger">A</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (mysqli_num_rows($query_siswa) == 0) {
                                        echo "<tr><td colspan='100%' class='text-center'>Data siswa tidak ditemukan / kelas kosong.</td></tr>";
                                    }

                                    $no = 1;
                                    while ($siswa = mysqli_fetch_assoc($query_siswa)) {
                                        $nis = $siswa['NIS'];
                                        $nama = $siswa['nama'];

                                        // $total_h = 0;
                                        $total_t = 0;
                                        $total_i = 0;
                                        $total_a = 0;
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td class="nama-siswa"><?= htmlspecialchars($nama) ?></td>

                                            <!-- Looping Tanggal Data Kehadiran -->
                                            <?php if (count($dates) == 0): ?>
                                                <td class="text-center">-</td>
                                            <?php else: ?>
                                                <?php foreach ($dates as $d): ?>
                                                    <?php
                                                    $status = isset($data_absen[$nis][$d]) ? $data_absen[$nis][$d] : 'Belum Absen';

                                                    $simbol = '-';
                                                    $warna = 'text-secondary font-weight-bold';

                                                    if ($status == 'Hadir') {
                                                        $simbol = 'H';
                                                        $warna = 'text-success font-weight-bold';
                                                    } elseif ($status == 'Terlambat') {
                                                        $simbol = 'T';
                                                        $warna = 'text-warning font-weight-bold';
                                                        $total_t++;
                                                    } elseif ($status == 'Izin' || $status == 'Sakit') {
                                                        // Menggabungkan izin dan sakit menjadi satu
                                                        $simbol = 'I';
                                                        $warna = 'text-info font-weight-bold';
                                                        $total_i++;
                                                    } elseif ($status == 'Alpa') {
                                                        $simbol = 'A';
                                                        $warna = 'text-danger font-weight-bold';
                                                        $total_a++;
                                                    }
                                                    ?>
                                                    <td class="text-center <?= $warna ?>"><?= $simbol ?></td>
                                                <?php endforeach; ?>
                                            <?php endif; ?>

                                            <!-- Total Rekap Baris Ini -->
                                            <!-- <td class="text-center fw-bold text-success"><?= $total_h ?></td> -->
                                            <td class="text-center fw-bold text-warning"><?= $total_t ?></td>
                                            <td class="text-center fw-bold text-info"><?= $total_i ?></td>
                                            <td class="text-center fw-bold text-danger"><?= $total_a ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="py-4 bg-light mt-auto no-print">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; SMK Al-Maliki 2026</div>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>
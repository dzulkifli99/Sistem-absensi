<?php
// 1. Jalankan session dan proteksi login di baris paling atas
session_start();
if (!isset($_SESSION["is_login"])) {
  header("Location: login.php");
  exit();
}

// 2. Sertakan koneksi dan file pendukung
include "koneksi.php";

// 3. Logika kueri database (pindahkan ke atas agar rapi)
$tanggal = date('Y-m-d');
$filter_kelas = "";
if (isset($_GET['kelas']) && $_GET['kelas'] != '') {
    $kls = mysqli_real_escape_string($koneksi, $_GET['kelas']);
    $filter_kelas = " WHERE d.kelas LIKE '%$kls%' ";
}

$sql = "SELECT d.NIS, d.nama, d.kelas, a.jam_pulang, a.status, a.tanggal 
        FROM data d 
        LEFT JOIN absensi a ON d.NIS = a.NIS AND a.tanggal = '$tanggal' 
        $filter_kelas
        ORDER BY d.kelas, d.nama";

$query = mysqli_query($koneksi, $sql);
if (!$query) {
  die(mysqli_error($koneksi));
}

// 4. Baru panggil header dan sidebar
include "header.php";
include "sidebar.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Dashboard - SMK Al-Maliki</title>
  <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
  <link href="css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-2">
        <div class="card-body d-flex justify-content-between align-items-center p-4 bg-dark rounded-4 my-2 shadow">
          <div>
            <h1 class="mt-4 text-light">Absensi Pulang</h1>
            <ol class="breadcrumb mb-4">
              <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
              <li class="breadcrumb-item active text-light">Absensi Pulang</li>
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

        <div class="card mb-4">
          <div class="card-header">
            <strong>Data Absensi Pulang Hari Ini (<?= date('d-m-Y'); ?>) </strong>
          </div>
          <div class="card-body">
            <table id="datatablesSimple">
              <button type="button" class="btn btn-outline-secondary dropdown-toggle float-end " data-bs-toggle="dropdown" aria-expanded="false">
                Cari Kelas
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="pulang.php">Semua Kelas</a></li>
                <?php
                include "koneksi.php";
                $sql_k = "SELECT DISTINCT kelas FROM data ORDER BY kelas ASC";
                $q_k = mysqli_query($koneksi, $sql_k);
                if ($q_k) {
                  while ($rk = mysqli_fetch_array($q_k)) {
                    echo '<li><a class="dropdown-item" href="pulang.php?kelas=' . urlencode($rk['kelas']) . '">' . htmlspecialchars($rk['kelas']) . '</a></li>';
                  }
                }
                ?>
              </ul>
          </div>
          <thead>
            <tr>
              <th>#</th>
              <th>Nama</th>
              <th>Kelas</th>
              <th>Jam Pulang</th>
              <th>Status</th>
              <th>Tanggal</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($query)) {
              // Logika Status Pulang Otomatis (Opsi 1)
              $status_harian = $row['status'];
              $jam_pulang = $row['jam_pulang'];
              
              if (in_array($status_harian, ['Izin', 'Sakit', 'Alpa'])) {
                  // Jika dari pagi memang Izin / Sakit / Alpa, tetapkan status tersebut
                  $status_tampil = $status_harian;
                  if ($status_harian == 'Alpa') $bg_color = "danger";
                  else $bg_color = "info text-dark";
              } else {
                  // Jika Hadir / Terlambat / Belum Absen
                  if (!empty($jam_pulang)) {
                      $status_tampil = "Sudah Pulang";
                      $bg_color = "success";
                  } else {
                      $status_tampil = "Belum Pulang";
                      $bg_color = "warning text-dark";
                  }
              }
            ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['nama'] ?></td>
                <td><?= $row['kelas'] ?></td>
                <td><?= $jam_pulang ?: '-' ?></td>
                <td>
                  <span class="badge bg-<?= $bg_color ?> p-2" style="width: 100px; font-size: 0.85rem;">
                    <?= $status_tampil ?>
                  </span>
                </td>
                <td><?= date('d-m-Y', strtotime($tanggal)) ?></td>
              </tr>
            <?php } ?>
          </tbody>
          </table>
        </div>
      </div>
  </div>
  </main>
  </div>

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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="js/scripts.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
  <script src="js/datatables-simple-demo.js"></script>

  <script>
    // Gunakan Event Delegation agar dropdown tetap berfungsi meskipun tabel dipindah halaman (pagination)
    document.body.addEventListener('change', function(e) {
      if (e.target.classList.contains('select-status')) {
        const select = e.target;
        const nis = select.getAttribute('data-nis');
        const statusBaru = select.value;

        // Beri efek visual sedang loading (opsional)
        select.style.opacity = '0.5';

        // Kirim data ke file update_status.php
        fetch('update_status.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `nis=${nis}&status=${statusBaru}`
          })
          .then(res => res.text())
          .then(data => {
            if (data === "success") {
              // Beri tanda sukses kecil (border hijau sesaat)
              select.style.borderColor = '#198754';
              setTimeout(() => {
                select.style.borderColor = '';
              }, 1000);
            } else {
              alert("Gagal update database!");
            }
          })
          .finally(() => {
            select.style.opacity = '1';
          });
      }
    });
  </script>
</body>

</html>
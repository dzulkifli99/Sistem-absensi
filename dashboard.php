<?php
session_start();
include "header.php";
if (!isset($_SESSION["is_login"])) {
  header("Location: index.php");
  exit(); // Wajib ada agar kode di bawahnya tidak bocor/dieksekusi
}

include "koneksi.php";

include "sidebar.php";

$tgl_sekarang = date('Y-m-d');
$q_hadir = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM absensi WHERE tanggal='$tgl_sekarang' AND (status='Hadir' )");
$res_hadir = mysqli_fetch_assoc($q_hadir);
$jml_hadir = $res_hadir['jml'];

$q_telat = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM absensi WHERE tanggal='$tgl_sekarang' AND ( status='Terlambat')");
$res_telat = mysqli_fetch_assoc($q_telat);
$jml_telat = $res_telat['jml'];

$q_izin = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM absensi WHERE tanggal='$tgl_sekarang' AND ( status='izin' OR status='sakit')");
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script
    src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"
    crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-2 ">
        <div class="card-body d-flex justify-content-between align-items-center p-4 bg-dark rounded-4 my-2 shadow">
          <div>
            <h1 class="mt-4 text-light">SELAMAT DATANG <?= $_SESSION["username"]; ?></h1>
            <ol class="breadcrumb mb-4">
              <li class="breadcrumb-item active text-light">Dashboard</li>
            </ol>
          </div>
          <div class="text-end">
            <div class="d-flex align-items-center justify-content-end text-light fw-bold">
              <i class="fa-solid fa-clock me-2"></i>
              <h3 id="clock" class="mb-0">00:00:00</h3>
            </div>
            <div id="date" class="text-light fw-medium mt-1 ">Memuat Tanggal...</div>
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



        <div class="row text-white my-2 shadow">
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
            <div>
              <button id="btnSync" class="btn btn-primary shadow-sm float-end" onclick="jalankanSync()">
                <i class="fas fa-sync-alt me-1"></i> Sinkronkan Mesin
              </button>
              <span id="syncStatus" class="ms-2 text-muted float-end" style="display:none;">Sedang menarik data...</span>
            </div>
          </div>


          <?php
          $tanggal = date('Y-m-d');

          $filter_kelas = "";
          if (isset($_GET['kelas']) && $_GET['kelas'] != '') {
            $kls = mysqli_real_escape_string($koneksi, $_GET['kelas']);
            // Filter siswa berdasarkan kelas yang mirip/mengandung angka tersebut
            $filter_kelas = " WHERE d.kelas LIKE '%$kls%' ";
          }

          $sql = "
SELECT 
d.id_siswa,
d.nama,
d.kelas,
a.jam_datang,
a.jam_pulang,
a.tanggal,
a.status
FROM data d
LEFT JOIN absensi a 
ON d.id_siswa = a.id_siswa 
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
              <button type="button" class="btn btn-outline-secondary dropdown-toggle float-end " data-bs-toggle="dropdown" aria-expanded="false">
                Cari Kelas
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="dashboard.php">Semua Kelas</a></li>
                <?php
                $sql_k = "SELECT DISTINCT kelas FROM data ORDER BY kelas ASC";
                $q_k = mysqli_query($koneksi, $sql_k);
                if ($q_k) {
                  while ($rk = mysqli_fetch_array($q_k)) {
                    echo '<li><a class="dropdown-item" href="dashboard.php?kelas=' . urlencode($rk['kelas']) . '">' . htmlspecialchars($rk['kelas']) . '</a></li>';
                  }
                }
                ?>
              </ul>
              <thead>
                <tr>
                  <th>No</th>
                  <th>ID Sistem</th>
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
                  $status = $row['status'] ?: 'Belum Absen';
                  $bg_color = "secondary";
                  if ($status == 'Hadir') $bg_color = "primary";
                  if ($status == 'Terlambat') $bg_color = "warning text-dark";
                  if ($status == 'Alpa') $bg_color = "danger";
                ?>

                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['id_siswa'] ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td><?= $row['kelas'] ?></td>
                    <td><?= $row['jam_datang'] ?: '-' ?></td>
                    <td><?= $row['jam_pulang'] ?: '-' ?></td>
                    <td><?= date('d-m-Y', strtotime($tanggal)) ?></td>
                    <td><span class="badge bg-<?= $bg_color ?>"><?= $status ?></span></td>
                    <!-- <td>
                      <a href="form.php?id=<?php echo $row['id_siswa']; ?>" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen me-2"></i></a>
                    </td> -->
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
        <div class="d-flex align-items-center justify-content-between small">
          <div class="text-muted">Copyright &copy; SMK Al-Maliki 2026</div>
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

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- sinkron data -->
  <script>
    function jalankanSync() {
      const btn = document.getElementById('btnSync');

      // 1. Tampilkan loading SweetAlert2
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menghubungkan...';

      Swal.fire({
        title: 'Sinkronisasi Berjalan',
        html: `
          <div class="d-flex flex-column align-items-center gap-2 py-2">
            <div class="spinner-border text-primary" style="width:3rem;height:3rem;"></div>
            <p class="mb-0 text-muted">Menarik data dari mesin fingerprint...<br><small>Harap tunggu, proses ini bisa memakan beberapa detik.</small></p>
          </div>`,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => Swal.showLoading()
      });

      // 2. Panggil synch_absen.php (sekarang return JSON)
      fetch('synch_absen.php')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'success') {
            const d = data.detail;
            Swal.fire({
              icon: 'success',
              title: data.title,
              html: `
                <p class="text-muted mb-3">${data.message}</p>
                <div class="row text-center g-2">
                  <div class="col-6">
                    <div class="rounded-3 p-2" style="background:#e8f5e9;">
                      <div class="fw-bold text-success" style="font-size:1.6rem">${d.datang}</div>
                      <div class="small text-muted">Datang diproses</div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="rounded-3 p-2" style="background:#e3f2fd;">
                      <div class="fw-bold text-primary" style="font-size:1.6rem">${d.pulang}</div>
                      <div class="small text-muted">Pulang diproses</div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="rounded-3 p-2" style="background:#ffebee;">
                      <div class="fw-bold text-danger" style="font-size:1.6rem">${d.alpa_baru}</div>
                      <div class="small text-muted">Auto-Alpa baru</div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="rounded-3 p-2" style="background:#f5f5f5;">
                      <div class="fw-bold text-secondary" style="font-size:1.6rem">${d.dilewati}</div>
                      <div class="small text-muted">Dilewati/duplikat</div>
                    </div>
                  </div>
                </div>
                <hr class="my-3">
                <div class="text-start small text-muted">
                  <i class="fas fa-clock me-1"></i> Sekarang: <strong>${d.sekarang}</strong> &nbsp;|&nbsp;
                  <i class="fas fa-sign-in-alt me-1"></i> Batas masuk: <strong>${d.batas_masuk}</strong> &nbsp;|&nbsp;
                  <i class="fas fa-user-slash me-1"></i> Alpa trigger: <strong>${d.alpa_trigger}</strong>
                </div>`,
              confirmButtonText: '<i class="fas fa-refresh me-1"></i> Refresh Data',
              confirmButtonColor: '#0d6efd',
            }).then(() => location.reload());

          } else if (data.status === 'warning') {
            Swal.fire({
              icon: 'info',
              title: data.title,
              text: data.message,
              confirmButtonColor: '#0d6efd',
            });

          } else {
            Swal.fire({
              icon: 'error',
              title: data.title,
              html: `<p>${data.message}</p><small class="text-muted">Pastikan mesin menyala dan IP 192.168.1.201 dapat diakses.</small>`,
              confirmButtonColor: '#dc3545',
            });
          }
        })
        .catch(err => {
          Swal.fire({
            icon: 'error',
            title: 'Request Gagal',
            text: 'Terjadi kesalahan saat menghubungi server. Cek koneksi atau log PHP.',
            confirmButtonColor: '#dc3545',
          });
          console.error(err);
        })
        .finally(() => {
          btn.disabled = false;
          btn.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Sinkronkan Mesin';
        });
    }
  </script>
  <script>
    document.querySelectorAll('.select-status').forEach(select => {
      select.onchange = function() {
        const nis = this.getAttribute('data-nis');
        const statusBaru = this.value;
        const dropdown = this;

        // Beri efek visual sedang loading (opsional)
        dropdown.style.opacity = '0.5';

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
              dropdown.style.borderColor = '#198754';
              setTimeout(() => {
                dropdown.style.borderColor = '';
              }, 1000);

              // Opsional: Refresh statistik (Hadir/Alpa) di atas tanpa reload
              // Tapi untuk tahap ini, reload manual saja dulu jika ingin update angka box.
            } else {
              alert("Gagal update database!");
            }
          })
          .finally(() => {
            dropdown.style.opacity = '1';
          });
      };
    });
  </script>

</body>

</html>
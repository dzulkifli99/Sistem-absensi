<?php
include "koneksi.php";
include "header.php";
include "sidebar.php";

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
  <title>Dashboard - Sipantau_SMALKIS</title>
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
        <div class="card-body d-flex justify-content-between align-items-center p-4">
          <div>
            <h1 class="mt-4">Dashboard</h1>
            <ol class="breadcrumb mb-4">
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
          <div class="text-end">
            <div class="d-flex align-items-center justify-content-end text-primary fw-bold">
              <i class="far fa-clock me-2"></i>
              <h3 id="clock" class="mb-0">00:00:00</h3>
            </div>
            <div id="date" class="text-muted fw-medium mt-1">Memuat Tanggal...</div>
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

        <div class="row">
          <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
              <div class="card-body">Hadir</div>
              <div
                class="card-footer d-flex align-items-center justify-content-between">
                <div class="small text-white">
                  <i class="fas fa-angle-right"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
              <div class="card-body">terlambat</div>
              <div
                class="card-footer d-flex align-items-center justify-content-between">
                <div class="small text-white">
                  <i class="fas fa-angle-right"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
              <div class="card-body">Izin</div>
              <div
                class="card-footer d-flex align-items-center justify-content-between">
                <div class="small text-white">
                  <i class="fas fa-angle-right"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
              <div class="card-body">Alpa</div>
              <div
                class="card-footer d-flex align-items-center justify-content-between">
                <div class="small text-white">
                  <i class="fas fa-angle-right"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header">
            <div><i class="fas fa-table me-1"></i>
              <button id="btnSync" class="btn btn-primary shadow-sm float-end" onclick="jalankanSync()">
                <i class="fas fa-sync-alt me-1"></i> Sinkronkan Mesin
              </button>
              <span id="syncStatus" class="ms-2 text-muted" style="display:none;">Sedang menarik data...</span>
            </div>
          </div>


          <?php
          $tanggal = date('Y-m-d');

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
                  <th>Aksi</th>
                </tr>
              </thead>

              <tbody>

                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($query)) {
                ?>

                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['NIS'] ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td><?= $row['kelas'] ?></td>
                    <td><?= $row['jam_datang'] ?: '-' ?></td>
                    <td><?= $row['jam_pulang'] ?: '-' ?></td>
                    <td><?= date('d-m-Y', strtotime($tanggal)) ?></td>
                    <td><?= $row['status'] ?: 'Belum Absen' ?></td>
                    <td>
                      <a href="form.php?id=<?php echo $row['NIS']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    </td>
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
          <div class="text-muted">Copyright &copy; Your Website 2023</div>
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

  <!-- sinkron data -->
  <script>
    function jalankanSync() {
      const btn = document.getElementById('btnSync');
      const status = document.getElementById('syncStatus');

      // 1. Tampilan saat loading
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menghubungkan...';
      status.style.display = 'inline';

      // 2. Panggil file PHP
      fetch('synch_absen.php')
        .then(response => response.text())
        .then(data => {
          alert('Proses Selesai: ' + data);
          location.reload(); // Refresh halaman untuk melihat data terbaru di tabel
        })
        .catch(err => {
          alert('Gagal terhubung ke mesin!');
          console.error(err);
        })
        .finally(() => {
          // 3. Kembalikan tombol ke asal
          btn.disabled = false;
          btn.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Sinkronkan Mesin';
          status.style.display = 'none';
        });
    }
  </script>

</body>

</html>
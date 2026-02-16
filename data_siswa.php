<?php
include "header.php";
include "sidebar.php";
include "hapus.php";
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
  <title>Dashboard - SMK Al-Maliki</title>
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
            <h1 class="mt-4  text-light">Data siswa</h1>
            <ol class="breadcrumb mb-4">
              <li class="breadcrumb-item "><a href="dashboard.php">Dashboard</a></li>
              <li class="breadcrumb-item active text-light">Data siswa</li>
            </ol>
          </div>
          <div class="text-end">
            <div class="d-flex align-items-center justify-content-end text-primary fw-bold">
              <i class="far fa-clock me-2"></i>
              <h3 id="clock" class="mb-0">00:00:00</h3>
            </div>
            <div id="date" class="text-light fw-medium mt-1  ">Memuat Tanggal...</div>
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

        <div class="card mb-4">
          <div class="card-header">
            <i class="fas fa-table me-1"></i>
            DataTable Example

            <button type="button" class="btn btn-outline-primary float-end ms-2" onclick="window.location.href='form.php'"> <i class="fa-solid fa-user-plus"></i> Tambah</button>

            <label for="file-import" class="btn btn-outline-success float-end">
              <i class="fa-solid fa-file-import"></i> Import
            </label>

            <input type="file" id="file-import" name="import" style="display:none;">
          </div>
          <div class="card-body">
            <table id="datatablesSimple">
              <thead>
                <tr>
                  <th>no</th>
                  <th>NIS</th>
                  <th>Nama</th>
                  <th>Kelas</th>
                  <th>No HP</th>
                  <th>aksi</th>
                </tr>
              </thead>

              <tbody>
                <?php
                include "koneksi.php";
                include "hapus.php";
                // $sql = "SELECT * FROM siswa ORDER BY id DESC";
                $sql = "SELECT * FROM data";
                $query = mysqli_query($koneksi, $sql);
                if (!$query) {
                  die("Error pada query :" . mysqli_error($koneksi));
                }
                $no = 1;
                while ($dt_siswa = mysqli_fetch_array($query)) {
                ?>
                  <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $dt_siswa['NIS']; ?></td>
                    <td><?= $dt_siswa['nama']; ?></td>
                    <td><?= $dt_siswa['kelas']; ?></td>
                    <td><?= $dt_siswa['no_hp']; ?></td>
                    <td>
                      <a href="edit.php?id=<?php echo $dt_siswa['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                      <button type="button"
                        class="btn btn-danger btn-sm"
                        onclick="konfirmasiHapus('<?= $dt_siswa['NIS']; ?>', this)">
                        <i class="fas fa-trash"></i> Hapus
                      </button>
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
  <!--link bootstrap  -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>
  <script src="js/scripts.js"></script>
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"
    crossorigin="anonymous"></script>
  <script src="assets/demo/chart-area-demo.js"></script>
  <script src="assets/demo/chart-bar-demo.js"></script>
  <!-- otomatis search -->
  <script
    src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
    crossorigin="anonymous"></script>
  <script src="js/datatables-simple-demo.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    function konfirmasiHapus(id, elemen) {
      Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Data NIS " + id + " akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          // Jalankan AJAX Fetch
          fetch('aksi_siswa.php?proses=hapus&id=' + id)
            .then(response => response.json())
            .then(data => {
              if (data.status === 'success') {
                Swal.fire(
                  'Terhapus!',
                  'Data berhasil dihapus tanpa refresh.',
                  'success'
                );
                // Menghapus baris tabel (tr) secara otomatis
                elemen.closest('tr').remove();
              } else {
                Swal.fire('Gagal!', 'Terjadi kesalahan: ' + data.message, 'error');
              }
            })
            .catch(err => {
              Swal.fire('Error!', 'Tidak dapat terhubung ke server', 'error');
            });
        }
      })
    }
  </script>
</body>

</html>
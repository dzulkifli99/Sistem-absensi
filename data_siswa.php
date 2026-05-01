<?php
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
      <div class="container-fluid px-2">
        <div class="card-body d-flex justify-content-between align-items-center p-4 bg-dark rounded-4 my-2 shadow">
          <div>
            <h1 class="mt-4  text-light">Data siswa</h1>
            <ol class="breadcrumb mb-4">
              <li class="breadcrumb-item "><a href="dashboard.php">Dashboard</a></li>
              <li class="breadcrumb-item active text-light">Data siswa</li>
            </ol>
          </div>
          <div class="text-end">
            <div class="d-flex align-items-center justify-content-end text-light fw-bold">
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

            <button type="button" class="btn btn-outline-info float-end ms-2" onclick="kirimKeMesin()">
              <i class="fa-solid fa-upload"></i> Kirim ke Mesin
            </button>

            <button type="button" class="btn btn-outline-primary float-end ms-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
              <i class="fa-solid fa-user-plus"></i> Tambah
            </button>

            <button type="button" class="btn btn-outline-success float-end" onclick="pilihFile()">
              <i class="fa-solid fa-file-excel"></i> Import Data
            </button>

            <a href="download_template.php" class="btn btn-outline-secondary float-end me-2">
              <i class="fa-solid fa-download"></i> Download Template
            </a>

            <form id="formImport" action="import.data.php" method="POST" enctype="multipart/form-data">
              <input type="file" id="inputHidden" name="file_excel" accept=".xlsx,.xls,.csv" style="display:none;" onchange="submitOtomatis()">
            </form>
          </div>

          <div class="card-body">
            <table id="datatablesSimple">
              <button type="button" class="btn btn-outline-secondary dropdown-toggle float-end " data-bs-toggle="dropdown" aria-expanded="false">
                <?= isset($_GET['kelas']) && $_GET['kelas'] != '' ? htmlspecialchars($_GET['kelas']) : 'Semua Kelas' ?>
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="data_siswa.php">Semua Kelas</a></li>
                <?php
                include "koneksi.php";
                $sql_k = "SELECT DISTINCT kelas FROM data ORDER BY kelas ASC";
                $q_k = mysqli_query($koneksi, $sql_k);
                if ($q_k) {
                  while ($rk = mysqli_fetch_array($q_k)) {
                    echo '<li><a class="dropdown-item" href="data_siswa.php?kelas=' . urlencode($rk['kelas']) . '">' . htmlspecialchars($rk['kelas']) . '</a></li>';
                  }
                }
                ?>
              </ul>
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
                <?php // include "koneksi.php"; // sudah di include di atas
                $sql = "SELECT * FROM data";
                if (isset($_GET['kelas']) && $_GET['kelas'] != '') {
                  $kls = mysqli_real_escape_string($koneksi, $_GET['kelas']);
                  $sql .= " WHERE kelas = '$kls'";
                }
                $sql .= " ORDER BY kelas ASC, nama ASC";
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
                      <button type="button"
                        class="btn btn-warning btn-sm btn-edit"
                        data-id="<?php echo $dt_siswa['NIS']; ?>"
                        data-nama="<?php echo $dt_siswa['nama']; ?>"
                        data-kelas="<?php echo $dt_siswa['kelas']; ?>"
                        data-hp="<?php echo $dt_siswa['no_hp']; ?>">
                        <i class="fa-solid fa-pen"></i>
                      </button>
                      <button type="button"
                        class="btn btn-danger btn-sm"
                        onclick="konfirmasiHapus('<?= $dt_siswa['NIS']; ?>', this)">
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Modal Tambah -->
      <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header pb-3">
              <h5 class="modal-title" id="modalTambahLabel"><i class="fa-solid fa-user-plus me-2"></i> Tambah Data Siswa</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTambah">
              <div class="modal-body">
                <div class="mb-3">
                  <label for="nis" class="form-label fw-bold">NIS</label>
                  <input type="number" class="form-control" id="nis" name="nis" required placeholder="Masukkan NIS">
                </div>
                <div class="mb-3">
                  <label for="nama" class="form-label fw-bold">Nama Lengkap</label>
                  <input type="text" class="form-control" id="nama" name="nama" required placeholder="Masukkan Nama Siswa">
                </div>
                <div class="mb-3">
                  <label for="kelas" class="form-label fw-bold">Kelas</label>
                  <input type="text" class="form-control" id="kelas" name="kelas" required placeholder="Contoh: 10 TKJ 1">
                </div>
                <div class="mb-3">
                  <label for="no_hp" class="form-label fw-bold">No HP</label>
                  <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="Contoh: 081234...">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="btnSimpanTambah" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Modal Edit -->
      <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header pb-3">
              <h5 class="modal-title" id="modalEditLabel"><i class="fa-solid fa-pen me-2"></i> Edit Data Siswa</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEdit">
              <div class="modal-body">
                <input type="hidden" id="edit_nis_lama" name="nis_lama">
                <div class="mb-3">
                  <label for="edit_nis" class="form-label fw-bold">NIS</label>
                  <input type="number" class="form-control" id="edit_nis" name="nis" required>
                </div>
                <div class="mb-3">
                  <label for="edit_nama" class="form-label fw-bold">Nama Lengkap</label>
                  <input type="text" class="form-control" id="edit_nama" name="nama" required>
                </div>
                <div class="mb-3">
                  <label for="edit_kelas" class="form-label fw-bold">Kelas</label>
                  <input type="text" class="form-control" id="edit_kelas" name="kelas" required>
                </div>
                <div class="mb-3">
                  <label for="edit_no_hp" class="form-label fw-bold">No HP</label>
                  <input type="text" class="form-control" id="edit_no_hp" name="no_hp">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="btnSimpanEdit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Simpan Perubahan</button>
              </div>
            </form>
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
    // ── Helper: kirim form via AJAX, return Promise JSON ─────────────────────
    function ajaxPost(url, formData) {
      return fetch(url, { method: 'POST', body: formData })
        .then(res => {
          if (!res.ok) throw new Error('HTTP ' + res.status);
          return res.json();
        });
    }

    // ── HAPUS ─────────────────────────────────────────────────────────────────
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

    // ── TAMBAH ────────────────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {

      document.getElementById('btnSimpanTambah').addEventListener('click', function () {
        const form = document.getElementById('formTambah');
        if (!form.checkValidity()) { form.reportValidity(); return; }

        const fd = new FormData(form);
        fd.append('tambah', '1');

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

        ajaxPost('aksi_siswa.php', fd)
          .then(data => {
            if (data.status === 'success') {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                html: data.message,
                confirmButtonColor: '#0d6efd',
                timer: 2000,
                timerProgressBar: true
              }).then(() => window.location.reload());

              // Tutup modal
              bootstrap.Modal.getInstance(document.getElementById('modalTambah')).hide();
              form.reset();
            } else {
              Swal.fire({ icon: 'error', title: 'Gagal!', html: data.message });
            }
          })
          .catch(err => Swal.fire('Error!', err.message, 'error'))
          .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-save me-1"></i> Simpan';
          });
      });

      // ── EDIT ───────────────────────────────────────────────────────────────
      // Isi modal edit via event delegation (Simple-DataTables re-render DOM)
      document.body.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-edit');
        if (!btn) return;

        document.getElementById('edit_nis_lama').value = btn.dataset.id;
        document.getElementById('edit_nis').value      = btn.dataset.id;
        document.getElementById('edit_nama').value     = btn.dataset.nama;
        document.getElementById('edit_kelas').value    = btn.dataset.kelas;
        document.getElementById('edit_no_hp').value    = btn.dataset.hp;

        new bootstrap.Modal(document.getElementById('modalEdit')).show();
      });

      document.getElementById('btnSimpanEdit').addEventListener('click', function () {
        const form = document.getElementById('formEdit');
        if (!form.checkValidity()) { form.reportValidity(); return; }

        const fd = new FormData(form);
        fd.append('edit', '1');

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

        ajaxPost('aksi_siswa.php', fd)
          .then(data => {
            if (data.status === 'success') {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil Diperbarui!',
                html: data.message,
                confirmButtonColor: '#0d6efd',
                timer: 2000,
                timerProgressBar: true
              }).then(() => window.location.reload());

              bootstrap.Modal.getInstance(document.getElementById('modalEdit')).hide();
            } else {
              Swal.fire({ icon: 'error', title: 'Gagal!', html: data.message });
            }
          })
          .catch(err => Swal.fire('Error!', err.message, 'error'))
          .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-save me-1"></i> Simpan Perubahan';
          });
      });

    });
  </script>

  <!-- import excel via AJAX -->
  <script>
    function pilihFile() {
      // Reset value dulu agar event onchange selalu terpicu walau file sama
      document.getElementById('inputHidden').value = '';
      document.getElementById('inputHidden').click();
    }

    function submitOtomatis() {
      const fileInput = document.getElementById('inputHidden');
      if (fileInput.files.length === 0) return;

      const namaFile = fileInput.files[0].name;

      // Konfirmasi sebelum upload
      Swal.fire({
        title: 'Import Data Siswa',
        html: `Yakin ingin mengimport data dari:<br><b>${namaFile}</b>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fa-solid fa-file-import"></i> Ya, Import!',
        cancelButtonText: 'Batal',
        showLoaderOnConfirm: true,
        preConfirm: () => {
          // Kirim file via AJAX menggunakan FormData
          const formData = new FormData();
          formData.append('file_excel', fileInput.files[0]);

          return fetch('import.data.php', {
              method: 'POST',
              body: formData
            })
            .then(response => {
              if (!response.ok) throw new Error('Koneksi ke server gagal (HTTP ' + response.status + ')');
              return response.json();
            })
            .catch(err => {
              Swal.showValidationMessage('Error: ' + err.message);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {
        if (!result.isConfirmed || !result.value) return;

        const data = result.value;

        if (data.status === 'error') {
          // ── Notifikasi GAGAL ──────────────────────────────────────
          Swal.fire({
            icon: 'error',
            title: 'Import Gagal!',
            text: data.message,
            confirmButtonText: 'OK'
          });
        } else {
          // ── Notifikasi BERHASIL ───────────────────────────────────
          let detailError = '';
          if (data.errors && data.errors.length > 0) {
            const tampil = data.errors.slice(0, 3).map(e => `<li>${e}</li>`).join('');
            const sisanya = data.errors.length > 3 ? `<li>... dan ${data.errors.length - 3} error lainnya</li>` : '';
            detailError = `<hr><p class="text-danger mb-1"><b>⚠️ ${data.errors.length} baris gagal:</b></p><ul class="text-start text-danger small">${tampil}${sisanya}</ul>`;
          }

          Swal.fire({
            icon: data.errors && data.errors.length > 0 ? 'warning' : 'success',
            title: 'Import Selesai!',
            html: `
              <table class="table table-sm text-start mt-2">
                <tr><td>📄 <b>File</b></td><td>${data.nama_file}</td></tr>
                <tr><td>📥 <b>Data baru</b></td><td><span class="badge bg-success">${data.berhasil}</span></td></tr>
                <tr><td>🔄 <b>Diperbarui</b></td><td><span class="badge bg-primary">${data.diperbarui}</span></td></tr>
                <tr><td>⏭️ <b>Baris kosong</b></td><td><span class="badge bg-secondary">${data.dilewati}</span></td></tr>
              </table>
              ${detailError}
            `,
            confirmButtonText: 'OK',
            confirmButtonColor: '#198754',
          }).then(() => {
            // Reload halaman agar tabel data siswa terupdate
            window.location.reload();
          });
        }
      });
    }

    // ── KIRIM KE MESIN FINGERPRINT ─────────────────────────────────────────────
    function kirimKeMesin() {
      Swal.fire({
        title: 'Kirim Data ke Mesin?',
        html: "Tindakan ini akan mengirim <b>seluruh data siswa</b> ke mesin fingerprint.<br>Proses ini mungkin membutuhkan waktu beberapa saat.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0dcaf0',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fa-solid fa-upload"></i> Ya, Kirim!',
        cancelButtonText: 'Batal',
        showLoaderOnConfirm: true,
        preConfirm: () => {
          return fetch('tambah.php', { method: 'POST' })
            .then(response => {
              if (!response.ok) throw new Error(response.statusText);
              return response.json();
            })
            .catch(error => {
              Swal.showValidationMessage(`Request gagal: ${error}`);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {
        if (result.isConfirmed) {
          if (result.value.status === 'success') {
            Swal.fire({
              icon: 'success',
              title: result.value.title,
              text: result.value.message,
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: result.value.title,
              text: result.value.message,
            });
          }
        }
      });
    }
  </script>
</body>

</html>
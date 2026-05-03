<?php
session_start();
include "header.php";
include "sidebar.php";
include "koneksi.php";

if (!isset($_SESSION["is_login"])) {
  header("Location: index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Setting Absensi - SMK Al-Maliki</title>
  <link rel="icon" href="assets/img/smalkis.png" type="image/png" sizes="192x192">
  <link href="css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
  <div id="layoutSidenav_content">
    <div id="layoutAuthentication_content">
      <main>
        <div class="container-fluid px-4">
          <div class="card-body d-flex justify-content-between align-items-center p-4 bg-dark rounded-4 my-2 shadow">
            <div>
              <h1 class="mt-4 text-light">Setting</h1>
              <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active text-light">Setting</li>
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
        </div>



        <div class="card mb-4">
          <div class="card-header">
            <button type="button" class="btn btn-success btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#modalTambahHari">
              <i class="fa-solid fa-plus me-1"></i> Tambah Hari
            </button>
          </div>
          <div class="card-body">
            <table id="datatablesSimple" class="table table-striped-columns">
              <thead>
                  <tr>
                    <th>Hari</th>
                    <th>jam masuk</th>
                    <th>batas masuk</th>
                    <th>toleransi telat (menit)</th>
                    <th>jam pulang</th>
                    <th>batas pulang</th>
                    <th>aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sql = "SELECT * FROM setting";
                  $query = mysqli_query($koneksi, $sql);
                  if (!$query) {
                    die("Query error: " . mysqli_error($koneksi));
                  }
                  $no = 1;
                  while ($setting = mysqli_fetch_array($query)) {
                  ?>
                    <tr>
                      <td><?= $setting['hari']; ?></td>
                      <td><?= $setting['jam_masuk']; ?></td>
                      <td><?= $setting['batas_masuk']; ?></td>
                      <td><?= $setting['toleransi_terlambat']; ?> menit</td>
                      <td><?= $setting['jam_pulang']; ?></td>
                      <td><?= $setting['batas_pulang']; ?></td>
                      <td>
                        <button type="button" class="btn btn-warning btn-sm btn-edit"
                          data-id="<?= $setting['id']; ?>"
                          data-hari="<?= $setting['hari']; ?>"
                          data-jam-masuk="<?= $setting['jam_masuk']; ?>"
                          data-batas-masuk="<?= $setting['batas_masuk']; ?>"
                          data-toleransi="<?= $setting['toleransi_terlambat']; ?>"
                          data-jam-pulang="<?= $setting['jam_pulang']; ?>"
                          data-batas-pulang="<?= $setting['batas_pulang']; ?>">
                          <i class="fa-solid fa-pen"></i> Edit
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="hapusHari(<?= $setting['id']; ?>, '<?= $setting['hari']; ?>')">
                          <i class="fa-solid fa-trash"></i> Hapus
                        </button>
                      </td>

                    </tr>
                  <?php  } ?>

                </tbody>

              </table>
          </div>
        </div>
    </div>
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

  <!-- Modal Edit Setting -->
  <div class="modal fade" id="modalEditSetting" tabindex="-1" aria-labelledby="modalEditSettingLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content text-dark">
        <div class="modal-header pb-3">
          <h5 class="modal-title" id="modalEditSettingLabel"><i class="fa-solid fa-clock me-2"></i> Edit Setting Jam: <span id="hariTitle"></span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formEditSetting">
          <div class="modal-body">
            <input type="hidden" id="edit_id" name="id">
            <div class="mb-3">
              <label for="edit_jam_masuk" class="form-label fw-bold">Jam Masuk</label>
              <input type="time" class="form-control" id="edit_jam_masuk" name="jam_masuk" required>
            </div>
            <div class="mb-3">
              <label for="edit_batas_masuk" class="form-label fw-bold">Batas Masuk</label>
              <input type="time" class="form-control" id="edit_batas_masuk" name="batas_masuk" required>
            </div>
            <div class="mb-3">
              <label for="edit_toleransi" class="form-label fw-bold">Toleransi Terlambat (Menit)</label>
              <input type="number" class="form-control" id="edit_toleransi" name="toleransi_terlambat" required>
            </div>
            <div class="mb-3">
              <label for="edit_jam_pulang" class="form-label fw-bold">Jam Pulang</label>
              <input type="time" class="form-control" id="edit_jam_pulang" name="jam_pulang" required>
            </div>
            <div class="mb-3">
              <label for="edit_batas_pulang" class="form-label fw-bold">Batas Pulang</label>
              <input type="time" class="form-control" id="edit_batas_pulang" name="batas_pulang" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="button" id="btnSimpanSetting" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Tambah Hari -->
  <div class="modal fade" id="modalTambahHari" tabindex="-1" aria-labelledby="modalTambahHariLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content text-dark">
        <div class="modal-header pb-3 bg-success text-white">
          <h5 class="modal-title" id="modalTambahHariLabel"><i class="fa-solid fa-calendar-plus me-2"></i> Tambah Hari Kerja</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formTambahHari">
          <div class="modal-body">
            <div class="mb-3">
              <label for="tambah_hari" class="form-label fw-bold">Nama Hari</label>
              <select class="form-select" id="tambah_hari" name="hari" required>
                <option value="">-- Pilih Hari --</option>
                <option value="Senin">Senin</option>
                <option value="Selasa">Selasa</option>
                <option value="Rabu">Rabu</option>
                <option value="Kamis">Kamis</option>
                <option value="Jumat">Jumat</option>
                <option value="Sabtu">Sabtu</option>
                <option value="Minggu">Minggu</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="tambah_jam_masuk" class="form-label fw-bold">Jam Masuk</label>
              <input type="time" class="form-control" id="tambah_jam_masuk" name="jam_masuk" value="07:00" required>
            </div>
            <div class="mb-3">
              <label for="tambah_batas_masuk" class="form-label fw-bold">Batas Masuk</label>
              <input type="time" class="form-control" id="tambah_batas_masuk" name="batas_masuk" value="07:15" required>
            </div>
            <div class="mb-3">
              <label for="tambah_toleransi" class="form-label fw-bold">Toleransi Terlambat (Menit)</label>
              <input type="number" class="form-control" id="tambah_toleransi" name="toleransi_terlambat" value="15" required>
            </div>
            <div class="mb-3">
              <label for="tambah_jam_pulang" class="form-label fw-bold">Jam Pulang</label>
              <input type="time" class="form-control" id="tambah_jam_pulang" name="jam_pulang" value="14:00" required>
            </div>
            <div class="mb-3">
              <label for="tambah_batas_pulang" class="form-label fw-bold">Batas Pulang</label>
              <input type="time" class="form-control" id="tambah_batas_pulang" name="batas_pulang" value="14:30" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="button" id="btnSimpanHariBaru" class="btn btn-success"><i class="fa-solid fa-save me-1"></i> Simpan Hari</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="js/scripts.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {

      // Buka modal saat tombol edit ditekan
      const editButtons = document.querySelectorAll('.btn-edit');
      editButtons.forEach(btn => {
        btn.addEventListener('click', function() {
          document.getElementById('edit_id').value = this.dataset.id;
          document.getElementById('hariTitle').textContent = this.dataset.hari;
          document.getElementById('edit_jam_masuk').value = this.dataset.jamMasuk;
          document.getElementById('edit_batas_masuk').value = this.dataset.batasMasuk;
          document.getElementById('edit_toleransi').value = this.dataset.toleransi;
          document.getElementById('edit_jam_pulang').value = this.dataset.jamPulang;
          document.getElementById('edit_batas_pulang').value = this.dataset.batasPulang;

          new bootstrap.Modal(document.getElementById('modalEditSetting')).show();
        });
      });

      // Proses simpan data via AJAX
      document.getElementById('btnSimpanSetting').addEventListener('click', function() {
        const form = document.getElementById('formEditSetting');
        if (!form.checkValidity()) {
          form.reportValidity();
          return;
        }

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

        const fd = new FormData(form);
        fd.append('simpan', '1');
        const id = document.getElementById('edit_id').value;

        fetch('edit.php?id=' + id, {
            method: 'POST',
            body: fd
          })
          .then(res => {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
          })
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

              bootstrap.Modal.getInstance(document.getElementById('modalEditSetting')).hide();
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                html: data.message
              });
            }
          })
          .catch(err => Swal.fire('Error!', err.message, 'error'))
          .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-save me-1"></i> Simpan Perubahan';
          });
      });

      // Proses tambah hari via AJAX
      document.getElementById('btnSimpanHariBaru').addEventListener('click', function() {
        const form = document.getElementById('formTambahHari');
        if (!form.checkValidity()) {
          form.reportValidity();
          return;
        }

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

        const fd = new FormData(form);
        fetch('tambah_hari.php', {
            method: 'POST',
            body: fd
          })
          .then(res => {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
          })
          .then(data => {
            if (data.status === 'success') {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                html: data.message,
                confirmButtonColor: '#198754',
                timer: 2000,
                timerProgressBar: true
              }).then(() => window.location.reload());

              bootstrap.Modal.getInstance(document.getElementById('modalTambahHari')).hide();
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                html: data.message
              });
            }
          })
          .catch(err => Swal.fire('Error!', err.message, 'error'))
          .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-save me-1"></i> Simpan Hari';
          });
      });
    });

    function hapusHari(id, namaHari) {
      Swal.fire({
        title: 'Hapus Hari ' + namaHari + '?',
        text: "Jadwal absensi untuk hari ini akan dihapus dari sistem!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          const fd = new FormData();
          fd.append('id', id);

          fetch('hapus_hari.php', {
              method: 'POST',
              body: fd
            })
            .then(res => res.json())
            .then(data => {
              if (data.status === 'success') {
                Swal.fire('Terhapus!', data.message, 'success')
                  .then(() => window.location.reload());
              } else {
                Swal.fire('Gagal!', data.message, 'error');
              }
            })
            .catch(err => Swal.fire('Error!', err.message, 'error'));
        }
      });
    }
  </script>
  <!-- jam analog -->
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

</body>

</html>
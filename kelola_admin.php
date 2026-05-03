<?php
session_start();
include "header.php";
include "sidebar.php";
include "koneksi.php";

if (!isset($_SESSION["is_login"])) {
    header("Location: login.php");
    exit();
}

$pesan = "";

// PROSES TAMBAH ADMIN
if (isset($_POST["tambah_admin"])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    // Cek duplikat
    $cek_user = mysqli_query($koneksi, "SELECT username FROM admin WHERE username = '$username'");
    if (mysqli_num_rows($cek_user) > 0) {
        $pesan = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> Username <strong>' . htmlspecialchars($username) . '</strong> sudah digunakan!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $q = "INSERT INTO admin (username, password) VALUES ('$username', '$password_hash')";
        if (mysqli_query($koneksi, $q)) {
            $pesan = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i> Admin baru berhasil ditambahkan!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
        } else {
            $pesan = '<div class="alert alert-warning">' . mysqli_error($koneksi) . '</div>';
        }
    }
}

// PROSES HAPUS ADMIN (AJAX)
if (isset($_POST["hapus_admin_ajax"])) {
    header('Content-Type: application/json');
    $id_hapus = (int)$_POST['id_hapus'];
    
    // Jangan izinkan admin menghapus dirinya sendiri
    $q_cek = mysqli_query($koneksi, "SELECT username FROM admin WHERE id = $id_hapus");
    $data_hapus = mysqli_fetch_assoc($q_cek);
    
    if ($data_hapus && $data_hapus['username'] === $_SESSION['username']) {
        echo json_encode(['status' => 'error', 'message' => 'Anda tidak bisa menghapus akun Anda sendiri yang sedang aktif!']);
    } else {
        if (mysqli_query($koneksi, "DELETE FROM admin WHERE id = $id_hapus")) {
            echo json_encode(['status' => 'success', 'message' => 'Admin berhasil dihapus!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus admin.']);
        }
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Kelola Admin - SMK Al-Maliki</title>
  <link rel="icon" href="assets/img/smalkis.png" type="image/png" sizes="192x192">
  <link href="css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="sb-nav-fixed">
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
        <h1 class="mt-4">Kelola Admin</h1>
        <ol class="breadcrumb mb-4">
          <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
          <li class="breadcrumb-item active">Kelola Admin</li>
        </ol>

        <?= $pesan; ?>

        <div class="row">
          <!-- Form Tambah Admin -->
          <div class="col-xl-4 mb-4">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-primary text-white">
                <i class="fa-solid fa-user-plus me-1"></i> Tambah Admin Baru
              </div>
              <div class="card-body">
                <form action="" method="post">
                  <div class="mb-3">
                    <label class="form-label fw-bold">Username</label>
                    <input type="text" class="form-control" name="username" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" class="form-control" name="password" required>
                  </div>
                  <button type="submit" name="tambah_admin" class="btn btn-primary w-100">
                    <i class="fa-solid fa-save"></i> Simpan Admin
                  </button>
                </form>
              </div>
            </div>
          </div>

          <!-- Tabel Data Admin -->
          <div class="col-xl-8">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-white">
                <i class="fa-solid fa-users-gear me-1"></i> Daftar Admin Sistem
              </div>
              <div class="card-body">
                <table class="table table-bordered table-hover">
                  <thead class="table-light">
                    <tr>
                      <th width="10%">No</th>
                      <th width="60%">Username</th>
                      <th width="30%">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $q_admin = mysqli_query($koneksi, "SELECT * FROM admin ORDER BY id ASC");
                    $no = 1;
                    while ($ad = mysqli_fetch_assoc($q_admin)) {
                    ?>
                      <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($ad['username']); ?></td>
                        <td>
                          <?php if ($ad['username'] !== $_SESSION['username']): ?>
                            <button type="button" onclick="hapusAdmin(<?= $ad['id']; ?>, '<?= htmlspecialchars(addslashes($ad['username'])); ?>')" class="btn btn-danger btn-sm">
                              <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                          <?php else: ?>
                            <span class="badge bg-success">Anda (Aktif)</span>
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="js/scripts.js"></script>
  <script>
    function hapusAdmin(id, username) {
      Swal.fire({
        title: 'Hapus Admin?',
        html: `Apakah Anda yakin ingin menghapus admin <strong>${username}</strong>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          const fd = new FormData();
          fd.append('hapus_admin_ajax', '1');
          fd.append('id_hapus', id);

          fetch('kelola_admin.php', {
            method: 'POST',
            body: fd
          })
          .then(response => response.json())
          .then(data => {
            if (data.status === 'success') {
              Swal.fire('Terhapus!', data.message, 'success').then(() => {
                location.reload();
              });
            } else {
              Swal.fire('Gagal!', data.message, 'error');
            }
          })
          .catch(error => {
            Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error');
          });
        }
      });
    }
  </script>
</body>
</html>

<?php
include "koneksi.php";
session_start();


if (isset($_SESSION["is_login"])) {
  header("location:dashboard.php");
}

$pesan = "";

if (isset($_POST['login'])) {
  $username = mysqli_real_escape_string($koneksi, $_POST['username']) ?? '';
  $password = $_POST['password'] ?? ''; // Jangan di-escape_string karena akan dicek lewat fungsi

  // 1. Cari user hanya berdasarkan USERNAME
  $q = "SELECT * FROM admin WHERE username='$username'";
  $result = mysqli_query($koneksi, $q);

  if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);

    // 2. VERIFIKASI: Bandingkan password input dengan hash di database
    if (password_verify($password, $data['password'])) {
      // Jika cocok, buat session
      $_SESSION["username"] = $data["username"];
      $_SESSION["is_login"] = true;

      header("Location: dashboard.php");
      exit();
    } else {
      // Password salah → set flag untuk SweetAlert2
      $pesan_swal = 'Password yang Anda masukkan salah!';
    }
  } else {
    $pesan = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-triangle-exclamation me-2"></i> Username tidak ditemukan!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  }
}

$pesan_swal = $pesan_swal ?? '';
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Login - SMALKIS</title>
  <link rel="icon" href="assets/img/smalkis.png" type="image/png" sizes="192x192">

  <!-- Google Fonts: Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <link href="css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
  
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f4f7f6;
      margin: 0;
      padding: 0;
      overflow-x: hidden;
    }
    .split-layout {
      display: flex;
      min-height: 100vh;
    }
    .left-side {
      flex: 1.2;
      background: linear-gradient(rgba(13, 110, 253, 0.7), rgba(0, 0, 0, 0.7)), url('assets/img/smk_almaliki.jpg');
      background-size: cover;
      background-position: center;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: white;
      padding: 40px;
      text-align: center;
    }
    .left-side h1 {
      font-weight: 700;
      font-size: 3rem;
      margin-bottom: 10px;
    }
    .left-side p {
      font-size: 1.1rem;
      font-weight: 300;
      opacity: 0.9;
    }
    .right-side {
      flex: 1;
      background-color: #ffffff;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px;
      box-shadow: -10px 0 30px rgba(0,0,0,0.05);
      z-index: 2;
    }
    .login-container {
      width: 100%;
      max-width: 400px;
    }
    .login-header {
      margin-bottom: 40px;
      text-align: center;
    }
    .login-header h3 {
      font-weight: 600;
      color: #2c3e50;
      margin-bottom: 5px;
    }
    .login-header p {
      color: #7f8c8d;
      font-size: 0.9rem;
    }
    .form-control {
      border-radius: 8px;
      padding: 12px 15px;
      border: 1px solid #e0e6ed;
      background-color: #f8f9fa;
      transition: all 0.3s ease;
    }
    .form-control:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
      background-color: #ffffff;
    }
    .form-floating label {
      color: #7f8c8d;
    }
    .btn-login {
      background-color: #0d6efd;
      color: white;
      border-radius: 8px;
      padding: 12px;
      font-weight: 500;
      width: 100%;
      transition: all 0.3s ease;
      border: none;
      margin-top: 20px;
    }
    .btn-login:hover {
      background-color: #0b5ed7;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
    }
    .register-link {
      text-align: center;
      margin-top: 20px;
      font-size: 0.9rem;
    }
    .register-link a {
      color: #0d6efd;
      text-decoration: none;
      font-weight: 600;
    }
    .register-link a:hover {
      text-decoration: underline;
    }
    @media (max-width: 768px) {
      .split-layout {
        flex-direction: column;
      }
      .left-side {
        min-height: 30vh;
        padding: 30px 20px;
      }
      .left-side h1 {
        font-size: 2rem;
      }
      .right-side {
        min-height: 70vh;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
        margin-top: -20px;
      }
    }
  </style>
</head>

<body>
  <div class="split-layout">
    <!-- Kiri: Visual Branding -->
    <div class="left-side">
      <h1>SMALKIS</h1>
      <p>Sistem Informasi Kehadiran Siswa Terpadu<br>SMK Al-Maliki</p>
    </div>

    <!-- Kanan: Form Login -->
    <div class="right-side">
      <div class="login-container">
        <div class="login-header">
          <h3>Selamat Datang!</h3>
          <p>Silakan masukkan kredensial Anda untuk melanjutkan.</p>
        </div>

        <?= $pesan; ?>

        <form action="" method="post">
          <div class="form-floating mb-4">
            <input class="form-control" id="username" name="username" type="text" placeholder="Masukkan Username" required />
            <label for="username"><i class="fa-solid fa-user me-2"></i>Username</label>
          </div>
          
          <div class="form-floating mb-3">
            <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Password" required />
            <label for="inputPassword"><i class="fa-solid fa-lock me-2"></i>Password</label>
          </div>

          <button class="btn btn-login" type="submit" id="login" name="login">
            <i class="fa-solid fa-right-to-bracket me-2"></i>Masuk Sistem
          </button>
        </form>


      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?php if (!empty($pesan_swal)): ?>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      Swal.fire({
        icon: 'error',
        title: 'Login Gagal!',
        text: '<?= addslashes($pesan_swal) ?>',
        confirmButtonColor: '#d33',
        confirmButtonText: 'Coba Lagi',
        customClass: {
          confirmButton: 'btn btn-danger px-4 rounded-3'
        }
      });
    });
  </script>
  <?php endif; ?>
</body>
</html>
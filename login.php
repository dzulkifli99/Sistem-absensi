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
      // Password salah
      echo "<script>alert('Password Salah!');</script>";
    }
  } else {
    $pesan = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-triangle-exclamation me-2"></i> Username tidak ditemukan!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  }
}

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

  <link href="css/styles.css" rel="stylesheet" />
  <script
    src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"
    crossorigin="anonymous"></script>
</head>
<style>
  body {
    background: url('assets/img/smk_almaliki.jpg') no-repeat center center fixed;
    background-size: cover;

  }

  #layoutAuthentication {
    background-color: rgba(0, 0, 0, 0.5);
    min-height: 100vh;
  }
</style>

<body>
  <div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
      <main>
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-5">
              <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header">
                  <h3 class="text-center font-weight-light my-4">Login</h3>
                </div>
                <div class="card-body">
                  <?= $pesan; ?>
                  <form action="" method="post">
                    <div class="form-floating mb-3">
                      <input class="form-control" id="username" name="username" type="text" placeholder="Masukkan Username" required />
                      <label for="username"><i class="fa-solid fa-user me-2"></i>Username</label>
                    </div>
                    <div class="form-floating mb-3">
                      <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Password" required />
                      <label for="inputPassword"><i class="fa-solid fa-lock me-2"></i>Password</label>
                    </div>
                    <div
                      class="d-flex align-items-center justify-content-between mt-4 mb-0 float-end">

                      <button class="btn btn-primary" id="login" name="login">Login</button>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center py-3">
                  <div class="card-footer text-center py-3">
                    <div class="small">
                      <a href="register.php">Register!</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

  </div>
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>
  <script src="js/scripts.js"></script>
</body>

</html>
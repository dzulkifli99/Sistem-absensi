<?php
session_start();
include "koneksi.php";

if (isset($_SESSION["is_login"])) {
    header("location:dashboard.php");
}


$pesan = ""; // Variabel untuk menampung pesan notifikasi

if (isset($_POST["register"])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password']; // Ambil password asli dulu

    // 1. CEK DUPLIKAT: Cari apakah username sudah ada
    $cek_user = mysqli_query($koneksi, "SELECT username FROM admin WHERE username = '$username'");

    if (mysqli_num_rows($cek_user) > 0) {
        $pesan = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> Username <strong>' . $username . '</strong> sudah terdaftar!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
    } else {
        // 2. HASHING: Ubah password jadi kode acak yang aman
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // 3. INSERT: Masukkan data yang sudah aman ke database
        $q = "INSERT INTO admin (username, password) VALUES ('$username', '$password_hash')";

        if (mysqli_query($koneksi, $q)) {
            $pesan = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i> Akun berhasil dibuat! Silakan <a href="login.php" class="alert-link">Login di sini</a>.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
        } else {
            // Tampilkan error asli dari database untuk debug
            $pesan = '<div class="alert alert-warning">' . mysqli_error($koneksi) . '</div>';
        }
    }
}
?>
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
                                    <h3 class="text-center font-weight-light my-4">DAFTAR AKUN</h3>
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

                                            <button class="btn btn-primary" id="register" name="register">Daftar</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="card-footer text-center py-3">
                                        <div class="small">
                                            <a href="login.php">Login</a>
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
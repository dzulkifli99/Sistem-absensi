<?php
session_start();

if (!isset($_SESSION["is_login"])) {
  header("Location: index.php");
  exit(); // Wajib ada agar kode di bawahnya tidak bocor/dieksekusi
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
    background: url('assets/img/smk_almaliki.JPG') no-repeat center center fixed;
    background-size: cover;
  }

  #layoutAuthentication {
    background-color: rgba(0, 0, 0, 0.5);
    min-height: 100vh;
  }

  .card {
    background-color: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(5px);
  }
</style>

<body>
  <div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
      <main>
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-7">
              <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header">
                  <h3 class="text-center font-weight-light my-4">
                    Setting Absensi
                  </h3>
                </div>
                <div class="card-body">
                  <table class="table table-striped-columns">
                    <thead>
                      <tr>
                        <th>Hari</th>
                        <th>jam masuk</th>
                        <th>batas masuk</th>
                        <th>jam pulang</th>
                        <th>batas pulang</th>
                        <th>aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      include "koneksi.php";
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
                          <td><?= $setting['jam_pulang']; ?></td>
                          <td><?= $setting['batas_pulang']; ?></td>
                          <td>
                            <a href="edit.php?id=<?php echo $setting['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                          </td>

                        </tr>
                      <?php  } ?>

                    </tbody>

                  </table>
                </div>
                <div class="card-footer text-left py-3">
                  <div class="small">

                    <a href="dashboard.php" class="btn btn-light">
                      <i class="fa-solid fa-reply"></i>
                    </a>
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
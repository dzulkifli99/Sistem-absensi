<?php
// $nilai = 80;

// if ($nilai >= 90) {
//   echo "A";
// } else if ($nilai >= 75 && $nilai < 90) {
//   echo "B";
// } else {
//   echo "C";
// }
// $masuk = "07.00";
// $datang = "06.20";
// $batas = "07.15";
// if (strtotime($datang) <= strtotime($masuk)) {
//   echo "hadir";
// } else if (strtotime($datang) >  strtotime($masuk) &&  strtotime($datang) <=  strtotime($batas)) {
//   echo "terlambat";
// } else {
//   echo "alpa";
// }
// $jam_pulang = "14:30";
// $batas = "15:00";
// if ("siswa pulang" < strtotime($jam_pulang)) {
//   echo "Bolos";
// } elseif ("siswa Pulang" >= strtotime($jam_pulang) && ("siswa pulang") <= strtotime($batas)) {
//   echo "normal";
// } else {
//   echo "tidak boleh absen";
// }


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
  <title>Register - SB Admin</title>
  <link href="css/styles.css" rel="stylesheet" />
  <script
    src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"
    crossorigin="anonymous"></script>
</head>

<body class="bg-primary">
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
                        die("Query eror") . mysqli_error($koneksi);
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
                          <button class="btn btn-primary float-end" id="login" name="login">Simpan</button>
                          <td></td>
                        </tr>
                      <?php  } ?>

                    </tbody>

                  </table>
                </div>
                <div class="card-footer text-center py-3">
                  <div class="small">
                    <a href="dashboard.php">Kembali ke Dashboard</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
    <div id="layoutAuthentication_footer">
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
</body>

</html>
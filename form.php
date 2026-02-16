<?php
include "koneksi.php";
$sukses  = "";
$error  = "";
$no_hp         = "";
$tempat_lahir = "";
$kota     = "";
$agama     = "";
$jenis_kelamin     = "";
$NIS    = "";
$NIK     = "";
$nama       = "";
$tgl_lahir  = "";
$alamat     = "";
$kelas      = "";
$n          = "";


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DATA SISWA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .mx-auto {
            max-width: 800px;
            /* Lebar maksimal 800px */
            width: 100%;
            /* Tapi kalau layarnya kecil, dia akan ikut mengecil */
            padding: 15px;
            /* Biar nggak mepet banget ke pinggir layar HP */
        }

        .card {
            margin-top: 10px;
        }

        .col-sm-10 {
            border: 1 px solid;
        }


        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
        }
    </style>
</head>

<body class="bg-secondary">

    </div>
    </div>
</body>

<body class=" bg-secondary">

    <div class="container">
        <div class="mx-auto" style="max-width: 800px;">

            <!-- untuk menambah data -->
            <div class="card">
                <div class="card-header  text-white bg-black">
                    TAMBAH SISWA
                </div>
                <div class="card-body">
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger" role="alert"><?php echo $error ?></div>
                        <?php endif; ?>
                        <?php if ($sukses): ?>
                            <div class="alert alert-success" role="alert"><?php echo $sukses ?></div>
                        <?php endif; ?>

                        <form action="" method="post">

                            <form action="" method="post">

                                <div class="mb-3 row">
                                    <label for="NIS" class="col-sm-2 col-form-label">NIS</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="NIS" name="NIS" placeholder="Masukkan angka" required value=" <?php echo $NIS ?> ">
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="nama" class="col-sm-2 col-form-label">nama</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="nama" name="nama" value=" <?php echo $nama ?> ">
                                    </div>
                                </div>


                                <div class="mb-3 row">
                                    <label for="kelas" class="col-sm-2 col-form-label">KELAS</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="kelas" name="kelas" value=" <?php echo $kelas ?> ">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="status" class="col-sm-2 col-form-label">status</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="kota" name="kota" value=" <?php echo $kota ?> ">
                                    </div>
                                </div>

                                <button type="submit" name="simpan" class="btn btn-primary">KIRIM</button>
                            </form>
                    </div>
                </div>

            </div>
        </div>
</body>

</html>
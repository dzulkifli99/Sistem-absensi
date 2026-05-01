<?php
include "koneksi.php";

/* =========================
   CEK ID
========================= */
if (!isset($_GET['id']) || $_GET['id'] == '') {
    die("ID tidak ditemukan!");
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

/* =========================
   AMBIL DATA BERDASARKAN ID
========================= */
$query = mysqli_query($koneksi, "SELECT * FROM setting WHERE id='$id'");

if (!$query) {
    die("Query Error: " . mysqli_error($koneksi));
}

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data tidak ditemukan!");
}

/* =========================
   PROSES UPDATE → return JSON
========================= */
if (isset($_POST['simpan'])) {
    header('Content-Type: application/json');

    $jam_masuk    = mysqli_real_escape_string($koneksi, $_POST['jam_masuk']);
    $batas_masuk  = mysqli_real_escape_string($koneksi, $_POST['batas_masuk']);
    $jam_pulang   = mysqli_real_escape_string($koneksi, $_POST['jam_pulang']);
    $batas_pulang = mysqli_real_escape_string($koneksi, $_POST['batas_pulang']);

    $update = mysqli_query($koneksi, "UPDATE setting SET
        jam_masuk='$jam_masuk',
        batas_masuk='$batas_masuk',
        jam_pulang='$jam_pulang',
        batas_pulang='$batas_pulang'
        WHERE id='$id'
    ");

    if ($update) {
        echo json_encode(['status' => 'success', 'message' => "Setting hari <b>{$data['hari']}</b> berhasil diperbarui!"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal update: ' . mysqli_error($koneksi)]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Setting Absensi</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"></script>
    <link rel="icon" href="assets/img/smalkis.png" type="image/png" sizes="192x192">
</head>

<body>

    <div class="container mt-5">
        <div class="card shadow p-4">
            <h3 class="mb-4 text-center">
                Edit Setting Hari <?= htmlspecialchars($data['hari']); ?>
            </h3>

            <form id="formSetting">
                <div class="mb-3">
                    <label>Jam Masuk</label>
                    <input type="time" name="jam_masuk"
                        value="<?= $data['jam_masuk']; ?>"
                        class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Batas Masuk</label>
                    <input type="time" name="batas_masuk"
                        value="<?= $data['batas_masuk']; ?>"
                        class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Jam Pulang</label>
                    <input type="time" name="jam_pulang"
                        value="<?= $data['jam_pulang']; ?>"
                        class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Batas Pulang</label>
                    <input type="time" name="batas_pulang"
                        value="<?= $data['batas_pulang']; ?>"
                        class="form-control" required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="setting.php" class="btn btn-secondary">
                        ← Kembali
                    </a>

                    <button type="button" id="btnSimpanSetting" class="btn btn-primary">
                        <i class="fas fa-floppy-disk"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('btnSimpanSetting').addEventListener('click', function () {
            const form   = document.getElementById('formSetting');
            if (!form.checkValidity()) { form.reportValidity(); return; }

            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

            const fd = new FormData(form);
            fd.append('simpan', '1');

            fetch('edit.php?id=<?= $id ?>', { method: 'POST', body: fd })
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
                        }).then(() => window.location.href = 'setting.php');
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal!', html: data.message });
                    }
                })
                .catch(err => Swal.fire('Error!', err.message, 'error'))
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-floppy-disk"></i> Simpan';
                });
        });
    </script>

</body>

</html>
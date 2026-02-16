<?php
require "koneksi.php";
$nis = $_GET['id'];

$data = mysqli_fetch_assoc(mysqli_query(
    $koneksi,
    "SELECT * FROM absensi WHERE NIS='$nis'"
));
?>

<form method="POST">
    <input type="time" name="datang" value="<?= $data['jam_datang'] ?>">
    <input type="time" name="pulang" value="<?= $data['jam_pulang'] ?>">

    <select class="form-select" aria-label="Default select example">
        <option value="1">Hadir</option>
        <option value="2">Terlambat</option>
        <option value="3">Izin</option>
        <option value="4">Alpa</option>
    </select>
    <button name="simpan">Simpan</button>
</form>

<?php
if (isset($_POST['simpan'])) {
    mysqli_query(
        $koneksi,
        "UPDATE absensi SET
    jam_datang='$_POST[datang]',
    jam_pulang='$_POST[pulang]',
    status='$_POST[status]'
    WHERE NIS='$nis'"
    );
}
?>
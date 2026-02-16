```php
<?php
require 'koneksi.php';
require 'zklibrary.php';
require 'notifikasi.php';

date_default_timezone_set('Asia/Jakarta');

// ==========================
// SETTING JAM SEKOLAH
// ==========================
$jam_masuk = "07:00:00";
$jam_datang_awal = "06:00:00";
$batas_telat = "08:00:00";

$batas_pulang_awal = "14:00:00";
$batas_pulang_akhir = "15:00:00";

// ==========================
// KONEKSI FINGERPRINT
// ==========================
$zk = new ZKLibrary('192.168.1.201', 4370);

if (!$zk->connect()) {
  die("Gagal koneksi mesin");
}

$zk->disableDevice();
$logs = $zk->getAttendance();

foreach ($logs as $log) {

  $nis = $log[1];
  $waktu = $log[3];

  $tanggal = date('Y-m-d', strtotime($waktu));
  $jam = date('H:i:s', strtotime($waktu));

  // hanya proses hari ini
  if ($tanggal != date('Y-m-d')) continue;

  // cek siswa
  $cek_siswa = mysqli_query(
    $koneksi,
    "SELECT * FROM data WHERE NIS='$nis'"
  );
  if (mysqli_num_rows($cek_siswa) == 0) continue;

  $siswa = mysqli_fetch_assoc($cek_siswa);
  $nama = $siswa['nama'];
  $no_ortu = $siswa['no_hp'];

  // cek absensi hari ini
  $cek = mysqli_query(
    $koneksi,
    "SELECT * FROM absensi
         WHERE NIS='$nis'
         AND tanggal='$tanggal'"
  );

  $row = mysqli_fetch_assoc($cek);

  // ==========================
  // ABSEN DATANG
  // ==========================
  if (!$row) {

    if ($jam >= $jam_datang_awal && $jam <= $batas_telat) {

      $status = ($jam <= $jam_masuk)
        ? "Hadir"
        : "Telat";

      mysqli_query(
        $koneksi,
        "INSERT INTO absensi
                (NIS,tanggal,jam_datang,status)
                VALUES
                ('$nis','$tanggal','$jam','$status')"
      );

      $pesan =
        "Assalamu’alaikum\n\n" .
        "Ananda $nama hadir jam $jam\n" .
        "Status: $status\n\n" .
        "SMK Al-Maliki";

      kirimWA($no_ortu, $pesan);
    }

    continue;
  }

  // ==========================
  // ABSEN PULANG
  // ==========================
  if (
    $jam >= $batas_pulang_awal &&
    $jam <= $batas_pulang_akhir &&
    $row['jam_pulang'] == NULL
  ) {

    mysqli_query(
      $koneksi,
      "UPDATE absensi
             SET jam_pulang='$jam',
                 status_pulang='Pulang'
             WHERE NIS='$nis'
             AND tanggal='$tanggal'"
    );

    $pesan =
      "Ananda $nama pulang jam $jam\n" .
      "Terima kasih.";

    kirimWA($no_ortu, $pesan);
  }
}

// ==========================
// AUTO ALPA (jam 08:00)
// ==========================
if (date('H:i') >= "08:00") {

  mysqli_query(
    $koneksi,
    "INSERT IGNORE INTO absensi
        (NIS,tanggal,status)
        SELECT NIS, CURDATE(), 'Alpa'
        FROM data
        WHERE NIS NOT IN (
            SELECT NIS
            FROM absensi
            WHERE tanggal=CURDATE()
        )"
  );
}

// ==========================
// AUTO BOLOS PULANG (jam 15:00)
// ==========================
if (date('H:i') >= "15:00") {

  mysqli_query(
    $koneksi,
    "UPDATE absensi
         SET status_pulang='Bolos'
         WHERE tanggal=CURDATE()
         AND jam_pulang IS NULL"
  );
}

$zk->enableDevice();
$zk->disconnect();

echo "Sync sukses";
?>
```
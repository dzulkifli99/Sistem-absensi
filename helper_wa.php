<?php
// c:\xampp\htdocs\Sistem-absensi\helper_wa.php

/**
 * Fungsi untuk menaruh pesan ke dalam antrean (wa_queue).
 * Ini akan dipakai oleh synch_absen.php agar proses sinkronisasi cepat selesai.
 */
function queueWA($nomor, $pesan) {
    global $koneksi;
    if (!$koneksi) {
        // Coba konek ulang jika global koneksi tidak ditemukan
        include "koneksi.php";
    }
    
    $no = mysqli_real_escape_string($koneksi, trim($nomor));
    $msg = mysqli_real_escape_string($koneksi, trim($pesan));
    
    if (empty($no) || empty($msg)) return false;
    
    $sql = "INSERT INTO wa_queue (nomor, pesan, status) VALUES ('$no', '$msg', 'pending')";
    return mysqli_query($koneksi, $sql);
}

/**
 * Fungsi asli untuk mengeksekusi pengiriman WA via API Fonnte.
 * Fungsi ini HANYA boleh dipanggil oleh background processor (proses_antrean.php).
 */
function sendWADirectly($nomor, $pesan) {
    $token = "nEyzvzjcDdthSYUXJoMZ"; // Ganti token Anda di sini jika berubah

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.fonnte.com/send",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => array(
        'target' => $nomor,
        'message' => $pesan,
      ),
      CURLOPT_HTTPHEADER => array(
        "Authorization: $token"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    
    if ($err) {
        return false;
    } else {
        $result = json_decode($response, true);
        if (isset($result['status']) && $result['status'] == true) {
            return true;
        }
        return false; // Gagal terkirim (nomor invalid, token salah, dll)
    }
}
?>

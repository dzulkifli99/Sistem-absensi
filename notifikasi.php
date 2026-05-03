<?php
session_start();
if (!isset($_SESSION["is_login"])) {
    header("Location: login.php");
    exit();
}

include "koneksi.php";



// Hitung statistik
$q_pending = mysqli_query($koneksi, "SELECT COUNT(id) as jml FROM wa_queue WHERE status='pending'");
$jml_pending = mysqli_fetch_assoc($q_pending)['jml'] ?? 0;

$q_sent = mysqli_query($koneksi, "SELECT COUNT(id) as jml FROM wa_queue WHERE status='sent'");
$jml_sent = mysqli_fetch_assoc($q_sent)['jml'] ?? 0;

$q_failed = mysqli_query($koneksi, "SELECT COUNT(id) as jml FROM wa_queue WHERE status='failed'");
$jml_failed = mysqli_fetch_assoc($q_failed)['jml'] ?? 0;

// Ambil data antrean
$q_queue = mysqli_query($koneksi, "SELECT * FROM wa_queue ORDER BY id DESC LIMIT 500");

include "header.php";
include "sidebar.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Antrean WhatsApp - SMK Al-Maliki</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .progress-container {
            display: none;
            margin-top: 20px;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4"><i class="fa-brands fa-whatsapp text-success"></i> Sistem Antrean Pesan WA</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Antrean WhatsApp</li>
                </ol>

                <!-- Statistik -->
                <div class="row">
                    <div class="col-xl-4 col-md-6">
                        <div class="card bg-warning text-white mb-4 shadow">
                            <div class="card-body">
                                <h5><i class="fa-solid fa-clock"></i> Menunggu (Pending)</h5>
                                <h2><?= $jml_pending ?> Pesan</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <div class="card bg-success text-white mb-4 shadow">
                            <div class="card-body">
                                <h5><i class="fa-solid fa-check-double"></i> Berhasil (Sent)</h5>
                                <h2><?= $jml_sent ?> Pesan</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <div class="card bg-danger text-white mb-4 shadow">
                            <div class="card-body">
                                <h5><i class="fa-solid fa-circle-xmark"></i> Gagal (Failed)</h5>
                                <h2><?= $jml_failed ?> Pesan</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kontrol Pengiriman -->
                <div class="card mb-4 shadow">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-paper-plane me-1"></i> Kontrol Pengiriman</span>
                        <div>
                            <button id="btnBersihkan" class="btn btn-outline-light btn-sm me-2" onclick="bersihkanRiwayat()">
                                <i class="fa-solid fa-trash"></i> Bersihkan Riwayat
                            </button>
                            <button id="btnMulaiKirim" class="btn btn-primary btn-sm" onclick="mulaiKirimAntrean(<?= $jml_pending ?>)" <?= $jml_pending == 0 ? 'disabled' : '' ?>>
                                <i class="fa-solid fa-play"></i> Mulai Kirim Pesan
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="mb-1 text-muted">Tekan "Mulai Kirim Pesan" untuk memproses antrean. Sistem akan mengirim 1 pesan setiap 3 detik untuk mencegah pemblokiran dari WhatsApp.</p>
                        
                        <!-- Progress Bar -->
                        <div class="progress-container" id="progressArea">
                            <h6 id="progressText">Mengirim pesan 0 dari <?= $jml_pending ?>...</h6>
                            <div class="progress" style="height: 25px;">
                                <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                            </div>
                            <small id="progressDetail" class="text-muted mt-1 d-block">Mohon jangan tutup halaman ini selama proses berlangsung.</small>
                        </div>
                    </div>
                </div>

                <!-- Tabel Data -->
                <div class="card mb-4 shadow">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Daftar Antrean Pesan (Menampilkan 500 Terakhir)
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Nomor Tujuan</th>
                                    <th>Pesan</th>
                                    <th>Status</th>
                                    <th>Dibuat Pada</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($q_queue)): ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= htmlspecialchars($row['nomor']) ?></td>
                                        <td>
                                            <div style="max-height: 80px; overflow-y: auto; font-size:0.85rem; background:#f8f9fa; padding:5px; border-radius:5px;">
                                                <?= nl2br(htmlspecialchars($row['pesan'])) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($row['status'] == 'pending'): ?>
                                                <span class="badge bg-warning text-dark"><i class="fa-solid fa-clock"></i> Pending</span>
                                            <?php elseif ($row['status'] == 'sent'): ?>
                                                <span class="badge bg-success"><i class="fa-solid fa-check"></i> Sent</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger"><i class="fa-solid fa-xmark"></i> Failed</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('d M Y, H:i', strtotime($row['created_at'])) ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let isSending = false;
        let totalPending = 0;
        let processedCount = 0;

        function mulaiKirimAntrean(total) {
            if (total <= 0) return;
            
            isSending = true;
            totalPending = total;
            processedCount = 0;

            document.getElementById('btnMulaiKirim').disabled = true;
            document.getElementById('btnMulaiKirim').innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sedang Mengirim...';
            document.getElementById('progressArea').style.display = 'block';
            
            // Mulai loop rekursif
            kirimSatuPesan();
        }

        function kirimSatuPesan() {
            if (!isSending) return;

            fetch('proses_antrean.php')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        processedCount++;
                        updateProgress(data.message);
                        
                        // Jeda 3 detik (3000ms) sebelum mengirim pesan berikutnya
                        setTimeout(kirimSatuPesan, 3000);
                    } else if (data.status === 'empty') {
                        // Antrean habis
                        isSending = false;
                        updateProgress("Selesai! Semua antrean telah diproses.");
                        Swal.fire('Selesai', 'Semua pesan dalam antrean telah berhasil diproses!', 'success')
                            .then(() => location.reload());
                    } else {
                        // Error teknis
                        processedCount++;
                        updateProgress("Gagal memproses 1 pesan: " + data.message);
                        setTimeout(kirimSatuPesan, 3000); // Lanjut ke pesan berikutnya
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    isSending = false;
                    Swal.fire('Error', 'Terjadi kesalahan koneksi saat memproses antrean.', 'error');
                    document.getElementById('btnMulaiKirim').disabled = false;
                    document.getElementById('btnMulaiKirim').innerHTML = '<i class="fa-solid fa-play"></i> Lanjutkan Pengiriman';
                });
        }

        function updateProgress(detail) {
            let percentage = Math.min(100, Math.round((processedCount / totalPending) * 100));
            
            document.getElementById('progressText').innerText = `Mengirim pesan ${processedCount} dari ${totalPending}...`;
            
            let progressBar = document.getElementById('progressBar');
            progressBar.style.width = percentage + '%';
            progressBar.innerText = percentage + '%';
            progressBar.setAttribute('aria-valuenow', percentage);

            document.getElementById('progressDetail').innerText = detail;
        }

        function bersihkanRiwayat() {
            Swal.fire({
                title: 'Bersihkan Riwayat?',
                text: "Semua pesan yang berstatus 'Sent' atau 'Failed' akan dihapus dari database. Pesan 'Pending' akan tetap aman.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('proses_antrean.php?action=clean')
                        .then(res => res.text())
                        .then(res => {
                            Swal.fire('Dibersihkan!', 'Riwayat antrean telah dibersihkan.', 'success')
                                .then(() => location.reload());
                        });
                }
            })
        }
    </script>
</body>
</html>

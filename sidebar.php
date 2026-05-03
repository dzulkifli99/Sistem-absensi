<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$username_ses = $_SESSION['username'] ?? 'User';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> SMALKIS</title>
</head>

<body>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="dashboard.php">
                            <div class="sb-nav-link-icon">
                                <i class="fa-solid fa-house"></i></i>
                            </div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Interface</div>
                        <a
                            class="nav-link collapsed"
                            href="#"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseLayouts"
                            aria-expanded="false"
                            aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon">
                                <i class="fa-solid fa-address-book"></i>
                            </div>
                            Absensi
                            <div class="sb-sidenav-collapse-arrow">
                                <i class="fas fa-angle-down"></i>
                            </div>
                        </a>
                        <div
                            class="collapse"
                            id="collapseLayouts"
                            aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="datang.php">Absensi Datang</a>
                                <a class="nav-link" href="pulang.php">Absensi pulang</a>
                            </nav>
                        </div>

                        <a class="nav-link" href="rekap.php">
                            <div class="sb-nav-link-icon">
                                <i class="fa-solid fa-folder-open"></i>
                            </div>
                            Rekap Absensi
                        </a>
                        <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                    X TKJ A
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                    X TKJ B
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                    XI TKJ A
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                    XI TKJ B
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>

                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                    Error
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>

                            </nav>
                        </div>
                        <div class="sb-sidenav-menu-heading">Addons</div>
                        
                        <a class="nav-link" href="notifikasi.php">
                            <div class="sb-nav-link-icon">
                                <i class="fa-solid fa-message"></i></i>
                            </div>
                            Notifikasi
                        </a>

                        <a class="nav-link" href="data_siswa.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                            Data siswa
                        </a>

                        <div class="sb-sidenav-menu-heading">Pengaturan</div>
                        <a class="nav-link" href="setting.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-gear"></i></div>
                            Setting
                        </a>
                        <a class="nav-link" href="kelola_admin.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-user-shield"></i></div>
                            Kelola Admin
                        </a>

                        <hr class="mt-4 mb-0 text-white-50">
                        <a class="nav-link text-danger mt-2" href="logout.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-right-from-bracket text-danger"></i></div>
                            Logout
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as: <?= htmlspecialchars($username_ses) ?></div>
                </div>
            </nav>
        </div>
</body>

</html>

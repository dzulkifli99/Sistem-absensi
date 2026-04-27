<?php
<<<<<<< HEAD
if (session_status() === PHP_SESSION_NONE) {
    session_start();
=======
session_start();
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("location:index.php");
>>>>>>> 036358afdc33bc1df96efde6b36c3e1d64f63526
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMALKIS</title>
    <link rel="icon" href="assets/img/smalkis.png" type="image/png" sizes="192x192">
</head>

<body>
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="dashboard.php">SMK Al-Maliki</a>
        <!-- Sidebar Toggle-->
        <button
            class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0"
            id="sidebarToggle"
            href="#!">
            <i class="fas fa-bars"></i>
        </button>
        <!-- Navbar Spacer-->
        <div class="d-none d-md-inline-block ms-auto me-0 me-md-3 my-2 my-md-0"></div>
    </nav>
</body>

</html>

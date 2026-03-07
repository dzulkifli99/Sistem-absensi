<?php

session_start();
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("location:index.php");
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
        <!-- Navbar Search-->
        <form action="header.php" method="post"
            class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
            </div>

            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a
                        class="nav-link dropdown-toggle"
                        id="navbarDropdown"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul
                        class="dropdown-menu dropdown-menu-end"
                        aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="login.php"><i class="fa-solid fa-circle-user me-2"></i>Login</a></li>
                        <li><a class="dropdown-item" href="register.php"><i class="fa-solid fa-gear me-2"></i>Register</a></li>
                        <li><a class="dropdown-item" href="setting.php"><i class="fa-solid fa-gear me-2"></i>Setting</a></li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <!-- <li><a class="dropdown-item" href="index.php"><i class="fa-solid fa-circle-left me-2"></i>Logout</a></li> -->
                        <button class="btn btn-primary" id="logout" name="logout"><i class="fa-solid fa-circle-left me-2"></i>Logout</button>
                    </ul>
                </li>
            </ul>
        </form>
    </nav>
</body>

</html>
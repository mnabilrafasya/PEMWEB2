<?php
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <h2>Menu User</h2>
            <nav>
                <ul>
                    <li><a href="index.php">Beranda</a></li>
                    <li><a href="ganti_password.php">Ganti Password</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header class="dashboard-header">
                <h1>Selamat Datang, <?= $_SESSION['username'] ?></h1>
            </header>
            
            <div class="user-info">
                <div class="info-card">
                    <h3>Akun Anda</h3>
                    <p>Username: <?= $_SESSION['username'] ?></p>
                    <p>Role: User</p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
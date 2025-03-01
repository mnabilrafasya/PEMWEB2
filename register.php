<?php
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];

    // Cek username/email sudah ada
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Username atau email sudah terdaftar!";
    } else {
        // Enkripsi password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Simpan ke database
        $stmt = $conn->prepare("INSERT INTO users (username, password, nama, email) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $hashed_password, $nama, $email);
        
        if ($stmt->execute()) {
            header("Location: login.php?registrasi=berhasil");
            exit();
        } else {
            $error = "Gagal mendaftar!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Daftar Akun</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="form-box">
            <h1>Daftar Akun</h1>
            
            <?php if(isset($error)): ?>
                <div class="error-msg"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="username" required>
                </div>
                
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>

                <div class="form-group">
                    <label>Nama Lengkap:</label>
                    <input type="text" name="nama" required>
                </div>

                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" required>
                </div>
                
                <button type="submit" class="btn">Daftar</button>
                <a href="login.php" class="link">Sudah punya akun? Login</a>
            </form>
        </div>
    </div>
</body>
</html>
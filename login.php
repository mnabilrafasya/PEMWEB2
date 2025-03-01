<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            if ($user['role'] == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: user.php");
            }
            exit();
        }
    }
    header("Location: login.php?error=1");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h1>Login</h1>
            
            <?php if(isset($_GET['error'])): ?>
                <div class="error-msg">
                    Username atau password salah!
                </div>
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
                
                <button type="submit" class="btn">Login</button>
                <a href="lupa_password.php" class="link">Lupa Password?</a>
                <a href="register.php" class="link">Belum punya akun?</a>
                <a href="index.php" class="link">Kembali ke beranda</a>
            </form>
        </div>
    </div>
</body>
</html>
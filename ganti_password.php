<?php
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if ($new_pass != $confirm_pass) {
        $error = "Password baru tidak cocok!";
    } else {
        // Cek password lama
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (password_verify($old_pass, $user['password'])) {
            // Update password baru
            $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update_stmt->bind_param("si", $hashed_pass, $_SESSION['user_id']);
            $update_stmt->execute();
            $success = "Password berhasil diubah!";
        } else {
            $error = "Password lama salah!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ganti Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="form-box">
            <h1>Ganti Password</h1>
            
            <?php if(isset($error)): ?>
                <div class="error-msg"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if(isset($success)): ?>
                <div class="success-msg"><?= $success ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Password Lama:</label>
                    <input type="password" name="old_password" required>
                </div>

                <div class="form-group">
                    <label>Password Baru:</label>
                    <input type="password" name="new_password" required>
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password Baru:</label>
                    <input type="password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn">Ganti Password</button>
                <a href="index.php" class="link">Kembali ke Beranda</a>
            </form>
        </div>
    </div>
</body>
</html>
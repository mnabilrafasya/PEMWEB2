<?php
include 'koneksi.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Cek token valid
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Token tidak valid atau sudah kadaluarsa!");
    }
}

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if ($new_pass != $confirm_pass) {
        $error = "Password tidak cocok!";
    } else {
        // Update password
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE reset_token = ?");
        $stmt->bind_param("ss", $hashed_pass, $token);
        $stmt->execute();
        $success = "Password berhasil direset! <a href='login.php'>Login Sekarang</a>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="form-box">
            <h1>Reset Password</h1>
            
            <?php if(isset($error)): ?>
                <div class="error-msg"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if(isset($success)): ?>
                <div class="success-msg"><?= $success ?></div>
            <?php endif; ?>

            <?php if(isset($_GET['token'])): ?>
                <form method="POST">
                    <div class="form-group">
                        <label>Password Baru:</label>
                        <input type="password" name="new_password" required>
                    </div>

                    <div class="form-group">
                        <label>Konfirmasi Password Baru:</label>
                        <input type="password" name="confirm_password" required>
                    </div>
                    
                    <button type="submit" class="btn">Reset Password</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php
include 'koneksi.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Cek email terdaftar
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $token = bin2hex(random_bytes(50));
        $expires = date("Y-m-d H:i:s", strtotime("+10 hour"));
        
        // Simpan token ke database
        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expires, $email);
        $stmt->execute();

        // Kirim email
        $mail = new PHPMailer(true);
        try {
            // Konfigurasi SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'm23391064@gmail.com'; // Ganti dengan email Anda
            $mail->Password = 'felp suig flou efqq'; // Ganti dengan password email Anda
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->addAddress($email);

            // Isi email
            $mail->isHTML(true);
            $mail->Subject = 'Reset Password';
            $reset_link = "http://localhost/PEMWEB2/reset_password.php?token=$token";
            $mail->Body = "Klik link berikut untuk reset password: <a href='$reset_link'>$reset_link</a>";

            $mail->send();
            echo "Link reset password telah dikirim ke email Anda!";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage(); 
        }
    } else {
        echo "Email tidak terdaftar!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lupa Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="form-box">
            <h1>Lupa Password</h1>
            <form method="POST">
                <div class="form-group">
                    <label>Email Terdaftar:</label>
                    <input type="email" name="email" required>
                </div>
                <button type="submit" class="btn">Kirim Link Reset</button>
                <a href="login.php" class="link">Kembali ke Login</a>
            </form>
        </div>
    </div>
</body>
</html>
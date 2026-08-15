<?php
session_start();
require_once __DIR__ . '/config.php';

$error = '';
$success = '';
$resetLink = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if ($email === '') {
        $error = 'Email daalo.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM admin_users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        // Hamesha generic success message dikhao (email enumeration se bachne ke liye)
        $success = 'Agar ye email registered hai, reset link bhej diya gaya hai.';

        if ($admin) {
            $token   = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $upd = $pdo->prepare("UPDATE admin_users SET reset_token = ?, reset_expires = ? WHERE id = ?");
            $upd->execute([$token, $expires, $admin['id']]);

            $resetLink = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']
                . dirname($_SERVER['PHP_SELF']) . '/reset-password.php?token=' . $token;

            /**
             * PRODUCTION ME: yaha PHPMailer / SMTP se $resetLink ko email me bhejo.
             * Abhi ke liye (jab tak SMTP setup nahi hai) link neeche screen par
             * dikha diya ja raha hai taaki test kar sako. Isse baad me hata dena.
             */
            // mail($email, 'Password Reset - Gyaana Admin', "Reset link: $resetLink");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Forgot Password - Gyaana Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
<div class="auth-wrap">
    <div class="auth-card">
        <div class="logo-row">
            <i class="fa-solid fa-key" style="font-size:30px;color:#17b6c4;"></i>
            <h1>Forgot Password</h1>
            <span>Reset your admin password</span>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if ($resetLink): ?>
            <div class="alert alert-success" style="word-break:break-all;">
                <b>Dev/testing link</b> (SMTP set hone ke baad ye yaha nahi dikhega, email pe jayega):<br>
                <a href="<?= htmlspecialchars($resetLink) ?>"><?= htmlspecialchars($resetLink) ?></a>
            </div>
        <?php endif; ?>

        <form method="POST" action="forgot-password.php">
            <div class="form-group">
                <label>Registered Email</label>
                <input type="email" name="email" class="form-control" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary">Send Reset Link</button>
        </form>

        <div class="auth-links">
            <a href="login.php">&larr; Back to login</a>
        </div>
    </div>
</div>
</body>
</html>

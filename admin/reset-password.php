<?php
session_start();
require_once __DIR__ . '/config.php';

$token = $_GET['token'] ?? $_POST['token'] ?? '';
$error = '';
$success = '';
$validToken = false;

if ($token === '') {
    $error = 'Invalid ya missing reset link.';
} else {
    $stmt = $pdo->prepare("SELECT id, reset_expires FROM admin_users WHERE reset_token = ? LIMIT 1");
    $stmt->execute([$token]);
    $admin = $stmt->fetch();

    if (!$admin) {
        $error = 'Ye reset link invalid hai.';
    } elseif (strtotime($admin['reset_expires']) < time()) {
        $error = 'Ye reset link expire ho chuka hai. Dubara request karo.';
    } else {
        $validToken = true;
    }
}

if ($validToken && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $password  = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if (strlen($password) < 6) {
        $error = 'Password kam se kam 6 characters ka hona chahiye.';
    } elseif ($password !== $password2) {
        $error = 'Dono password match nahi kar rahe.';
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $upd = $pdo->prepare("UPDATE admin_users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
        $upd->execute([$hashed, $admin['id']]);
        $success = 'Password successfully change ho gaya! Ab login kar sakte ho.';
        $validToken = false; // form hide kar do
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Password - Gyaana Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
<div class="auth-wrap">
    <div class="auth-card">
        <div class="logo-row">
            <i class="fa-solid fa-lock" style="font-size:30px;color:#17b6c4;"></i>
            <h1>Reset Password</h1>
            <span>Set a new password</span>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if ($validToken): ?>
        <form method="POST" action="reset-password.php">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="password" class="form-control" required minlength="6">
            </div>
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="password2" class="form-control" required minlength="6">
            </div>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
        <?php endif; ?>

        <div class="auth-links">
            <a href="login.php">&larr; Back to login</a>
        </div>
    </div>
</div>
</body>
</html>

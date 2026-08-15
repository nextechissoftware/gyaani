<?php
session_start();
require_once __DIR__ . '/config.php';

// Already logged in? go straight to dashboard
if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '' ;

    if ($username === '' || $password === '') {
        $error = 'Username aur password dono bharo.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ? OR email = ? LIMIT 1");
        $stmt->execute([$username, $username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id']   = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Galat username ya password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login - Gyaana International School</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
<div class="auth-wrap">
    <div class="auth-card">
        <div class="logo-row">
            <i class="fa-solid fa-graduation-cap" style="font-size:34px;color:#17b6c4;"></i>
            <h1>Gyaana International School</h1>
            <span>Admin Panel</span>
        </div>
        <h2>Login to continue</h2>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="/admin/login">
            <div class="form-group">
                <label>Username or Email</label>
                <input type="text" name="username" class="form-control" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <div class="auth-links">
            <a href="forgot-password.php">Forgot password?</a>
        </div>
    </div>
</div>
</body>
</html>

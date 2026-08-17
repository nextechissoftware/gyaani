<?php
/**
 * SETUP SCRIPT
 * ------------------------------------------------------------
 * Ise sirf EK BAAR browser me open karo (e.g. https://yourdomain.com/admin/setup.php)
 * Ye required tables bana dega + ek default admin record insert kar dega.
 *
 * ⚠️ IMPORTANT: Setup ho jaane ke baad is file ko server se DELETE kar dena,
 * warna koi bhi ise dubara chala kar dikkat kar sakta hai.
 */

require_once __DIR__ . '/config.php';

$messages = [];
$hasError = false;

try {
    // 1. admin_users table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS admin_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(150) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            reset_token VARCHAR(100) DEFAULT NULL,
            reset_expires DATETIME DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    $messages[] = "✔ Table 'admin_users' ready.";

    // 2. gallery_events table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS gallery_events (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(150) NOT NULL,
            slug VARCHAR(170) NOT NULL UNIQUE,
            event_date DATE DEFAULT NULL,
            description TEXT,
            cover_image VARCHAR(255) DEFAULT NULL,
            status ENUM('active','inactive') NOT NULL DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    $messages[] = "✔ Table 'gallery_events' ready.";

    // 3. gallery_photos table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS gallery_photos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            event_id INT NOT NULL,
            image_path VARCHAR(255) NOT NULL,
            caption VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_event
                FOREIGN KEY (event_id) REFERENCES gallery_events(id)
                ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    $messages[] = "✔ Table 'gallery_photos' ready.";

    // 4. Default admin record (only if not already present)
    $check = $pdo->prepare("SELECT id FROM admin_users WHERE username = ?");
    $check->execute(['admin']);

    if (!$check->fetch()) {
        $defaultPassword = 'Gyaana@2026';   // login ke baad ise change kar lena
        $hashed = password_hash($defaultPassword, PASSWORD_DEFAULT);

        $insert = $pdo->prepare("
            INSERT INTO admin_users (name, username, email, password)
            VALUES (?, ?, ?, ?)
        ");
        $insert->execute(['Admin', 'admin', 'gyaanagausganj@gmail.com', $hashed]);

        $messages[] = "✔ Default admin created &rarr; username: <b>admin</b> &nbsp; password: <b>{$defaultPassword}</b>";
        $messages[] = "⚠ Login karte hi is password ko change kar lena (Dashboard &rarr; Profile).";
    } else {
        $messages[] = "ℹ Admin user already exists, skipped.";
    }

    // 5. Ensure upload folder exists
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
        $messages[] = "✔ Upload folder created at uploads/gallery/";
    } else {
        $messages[] = "ℹ Upload folder already exists.";
    }

} catch (PDOException $e) {
    $hasError = true;
    $messages[] = "✖ Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Setup - Gyaana International School</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
    body{font-family:'Segoe UI',Arial,sans-serif;background:#0f2540;color:#fff;padding:40px;}
    .box{max-width:640px;margin:0 auto;background:#16324a;border-radius:12px;padding:30px 35px;box-shadow:0 10px 30px rgba(0,0,0,.3);}
    h1{color:#17b6c4;font-size:22px;margin-top:0;}
    li{margin-bottom:10px;line-height:1.5;}
    a{color:#17b6c4;}
    .warn{background:#3a2a0f;border-left:4px solid #ffb020;padding:12px 16px;border-radius:6px;margin-top:20px;font-size:14px;}
</style>
</head>
<body>
<div class="box">
    <h1><?= $hasError ? '✖ Setup failed' : '✅ Setup complete' ?></h1>
    <ul>
        <?php foreach ($messages as $m): ?>
            <li><?= $m ?></li>
        <?php endforeach; ?>
    </ul>
    <?php if (!$hasError): ?>
        <p><a href="login.php">→ Go to Login page</a></p>
        <div class="warn">
            🔒 Security: Ab setup.php ko server se delete kar do, taaki dubara koi ise access na kar sake.
        </div>
    <?php endif; ?>
</div>
</body>
</html>

<?php
/**
 * Database Configuration
 * ------------------------------------------------------------
 * Hostinger pe hPanel > Databases se ye 4 cheeze milengi.
 * Wahi yaha daal do. Agar local (XAMPP/localhost) pe test kar rahe ho
 * to DB_USER = 'root', DB_PASS = '' aam taur par chalta hai.
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'u221387206_gyaana'); 
define('DB_USER', 'u221387206_gyaana');    
define('DB_PASS', 'Mili@88877');  

// Site base path (jaha ye admin folder site root ke andar hai)
// Example: agar admin panel https://gyaanainternationalschool.com/admin/ pe hai
// to ye '/admin' rakho. Root pe hai to '' rakho.
$docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? '');
$thisDir = str_replace('\\', '/', __DIR__);
define('BASE_PATH', rtrim(substr($thisDir, strlen($docRoot)), '/'));
 
// Upload folder (assets/gallery lives at project root, one level above /admin)
define('UPLOAD_DIR', dirname(__DIR__) . '/assets/gallery/');
define('UPLOAD_URL', '/assets/gallery/');


date_default_timezone_set('Asia/Kolkata');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed. Please check config.php credentials. (" . $e->getMessage() . ")");
}

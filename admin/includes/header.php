<?php
/**
 * Include this AFTER includes/auth.php
 * Expects optional $pageTitle variable to be set before including.
 */
$pageTitle = $pageTitle ?? 'Dashboard';
$currentFile = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($pageTitle) ?> - Gyaana Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <div class="brand">Gyaana <span>Admin</span></div>
        <nav>
            <a href="dashboard.php" class="<?= $currentFile === 'dashboard.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-gauge"></i> Dashboard
            </a>
            <a href="events.php" class="<?= in_array($currentFile, ['events.php','edit-event.php']) ? 'active' : '' ?>">
                <i class="fa-solid fa-calendar-days"></i> Gallery Events
            </a>
        </nav>
        <div class="logout-link">
            <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
    </aside>
    <div class="main">
        <div class="topbar">
            <h1><?= htmlspecialchars($pageTitle) ?></h1>
            <div class="user-chip">
                <i class="fa-solid fa-circle-user"></i>
                <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?>
            </div>
        </div>
        <div class="content">

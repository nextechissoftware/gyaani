<?php
require_once __DIR__ . '/includes/auth.php';

$totalEvents = $pdo->query("SELECT COUNT(*) FROM gallery_events")->fetchColumn();
$totalPhotos = $pdo->query("SELECT COUNT(*) FROM gallery_photos")->fetchColumn();
$activeEvents = $pdo->query("SELECT COUNT(*) FROM gallery_events WHERE status='active'")->fetchColumn();

$recentPhotos = $pdo->query("
    SELECT p.image_path, p.created_at, e.title
    FROM gallery_photos p
    JOIN gallery_events e ON e.id = p.event_id
    ORDER BY p.id DESC
    LIMIT 8
")->fetchAll();

$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/header.php';
?>

<div class="stats-grid">
    <div class="stat-card teal">
        <div class="num"><?= (int)$totalEvents ?></div>
        <div class="label"><i class="fa-solid fa-calendar-days"></i> Total Gallery Events</div>
    </div>
    <div class="stat-card">
        <div class="num"><?= (int)$activeEvents ?></div>
        <div class="label">Active Events</div>
    </div>
    <div class="stat-card">
        <div class="num"><?= (int)$totalPhotos ?></div>
        <div class="label">Total Photos Uploaded</div>
    </div>
</div>

<div class="card">
    <div class="top-actions">
        <h3 style="margin:0;color:#0f2540;">Recently Uploaded Photos</h3>
        <a href="gallery.php" class="btn btn-outline btn-sm"><i class="fa-solid fa-plus"></i> Manage Gallery</a>
    </div>

    <?php if (empty($recentPhotos)): ?>
        <p style="color:#8593a0;">Abhi tak koi photo upload nahi hui. <a href="gallery.php">Gallery page</a> se photos add karo.</p>
    <?php else: ?>
        <div class="photo-grid">
            <?php foreach ($recentPhotos as $p): ?>
                <div class="photo-item">
                    <img src="<?= htmlspecialchars(UPLOAD_URL . $p['image_path']) ?>" alt="">
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

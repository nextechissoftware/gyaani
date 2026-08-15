<?php
require_once __DIR__ . '/../admin/config.php';

$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM gallery_events WHERE slug = ? AND status = 'active'");
$stmt->execute([$slug]);
$event = $stmt->fetch();

if (!$event) {
    header('Location: gallery.php');
    exit;
}

/* Pagination: 15 photos per page */
$perPage = 15;
$page = max(1, (int)($_GET['page'] ?? 1));
$totalStmt = $pdo->prepare("SELECT COUNT(*) FROM gallery_photos WHERE event_id = ?");
$totalStmt->execute([$event['id']]);
$total = $totalStmt->fetchColumn();
$totalPages = max(1, ceil($total / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

$stmt = $pdo->prepare("
    SELECT * FROM gallery_photos
    WHERE event_id = :eid
    ORDER BY id ASC
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':eid', $event['id'], PDO::PARAM_INT);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$photos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($event['title']) ?> - Gallery - Gyaana International School</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    :root{--teal:#17b6c4;--navy:#0f2540;}
    body{font-family:'Poppins',sans-serif;color:#33475b;}
    .navbar-brand{font-weight:700;font-size:22px;color:var(--navy)!important;}
    .navbar-brand span{color:#e91e63;}
    .nav-link{font-weight:600;font-size:14px;color:var(--navy)!important;}
    .nav-link.active{color:var(--teal)!important;}
    .hero{
        background:linear-gradient(rgba(15,37,64,.75),rgba(15,37,64,.75)),url('assets/images/hero-bg.jpg') center/cover;
        padding:80px 0 60px;text-align:center;color:#fff;
    }
    .hero h1{font-weight:800;font-size:36px;}
    .hero .crumb a{color:#cfe3ec;}
    .hero .crumb span{color:var(--teal);}
    .photo-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;}
    .photo-grid a{display:block;border-radius:12px;overflow:hidden;box-shadow:0 6px 18px rgba(15,37,64,.08);}
    .photo-grid img{width:100%;height:190px;object-fit:cover;display:block;transition:.3s;}
    .photo-grid a:hover img{transform:scale(1.06);}
    .pagination .page-link{color:var(--navy);}
    .pagination .active .page-link{background:var(--teal);border-color:var(--teal);}
    footer{background:var(--navy);color:#cfe3ec;padding:30px 0;text-align:center;font-size:13px;margin-top:60px;}
    .back-link{color:var(--teal);font-weight:600;font-size:14px;}
    #lightbox{
        display:none;position:fixed;inset:0;background:rgba(10,20,35,.92);z-index:9999;
        align-items:center;justify-content:center;
    }
    #lightbox img{max-width:90%;max-height:85%;border-radius:8px;}
    #lightbox .close-btn{
        position:absolute;top:20px;right:30px;color:#fff;font-size:30px;cursor:pointer;
    }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand" href="index.php">Gyaana <span>International School</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto gap-lg-3">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                <li class="nav-item"><a class="nav-link" href="our-team.php">Our Team</a></li>
                <li class="nav-item"><a class="nav-link active" href="gallery.php">Gallery</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="mandatory-public-disclosure.php">Mandatory Public Disclosure</a></li>
            </ul>
        </div>
    </div>
</nav>

<section class="hero">
    <div class="container">
        <h1><?= htmlspecialchars($event['title']) ?></h1>
        <div class="crumb">
            <a href="index.php">Home</a> / <a href="gallery.php">Gallery</a> / <span><?= htmlspecialchars($event['title']) ?></span>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <a href="gallery.php" class="back-link mb-4 d-inline-block"><i class="fa-solid fa-arrow-left"></i> Back to all events</a>

        <?php if ($event['description']): ?>
            <p class="text-muted mb-4"><?= nl2br(htmlspecialchars($event['description'])) ?></p>
        <?php endif; ?>

        <?php if (empty($photos)): ?>
            <p class="text-muted py-5 text-center">Is event ke liye abhi koi photo upload nahi hui hai.</p>
        <?php else: ?>
        <div class="photo-grid">
            <?php foreach ($photos as $p): ?>
                <a href="#" class="lightbox-trigger" data-src="<?= htmlspecialchars(UPLOAD_URL . $p['image_path']) ?>">
                    <img src="<?= htmlspecialchars(UPLOAD_URL . $p['image_path']) ?>" loading="lazy" alt="<?= htmlspecialchars($event['title']) ?>">
                </a>
            <?php endforeach; ?>
        </div>

        <?php if ($totalPages > 1): ?>
        <nav class="mt-5">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?slug=<?= urlencode($slug) ?>&page=<?= $page - 1 ?>">&laquo;</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?slug=<?= urlencode($slug) ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?slug=<?= urlencode($slug) ?>&page=<?= $page + 1 ?>">&raquo;</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<footer>
    &copy; <?= date('Y') ?> Gyaana International School. All rights reserved.
</footer>

<div id="lightbox">
    <span class="close-btn">&times;</span>
    <img src="" alt="">
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = lightbox.querySelector('img');
    document.querySelectorAll('.lightbox-trigger').forEach(el => {
        el.addEventListener('click', e => {
            e.preventDefault();
            lightboxImg.src = el.dataset.src;
            lightbox.style.display = 'flex';
        });
    });
    lightbox.addEventListener('click', () => lightbox.style.display = 'none');
</script>
</body>
</html>

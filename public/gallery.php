<?php
/**
 * PUBLIC GALLERY PAGE
 * ------------------------------------------------------------
 * Ise site root me rakho (jaha index.php, about.php already hain).
 * Ye ../admin/config.php se DB read karta hai.
 * Path zaroorat ho to config path adjust kar lena.
 */
require_once __DIR__ . '/../admin/config.php';

/* Events grid pagination */
$perPage = 9;
$page = max(1, (int)($_GET['page'] ?? 1));
$total = $pdo->query("SELECT COUNT(*) FROM gallery_events WHERE status = 'active'")->fetchColumn();
$totalPages = max(1, ceil($total / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

$stmt = $pdo->prepare("
    SELECT e.*, (SELECT COUNT(*) FROM gallery_photos p WHERE p.event_id = e.id) AS photo_count
    FROM gallery_events e
    WHERE e.status = 'active'
    ORDER BY e.event_date DESC, e.id DESC
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$events = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Gallery - Gyaana International School</title>
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
        padding:90px 0 70px;text-align:center;color:#fff;
    }
    .hero h1{font-weight:800;font-size:42px;}
    .hero .crumb a{color:#cfe3ec;}
    .hero .crumb span{color:var(--teal);}
    .event-card{border:none;border-radius:14px;overflow:hidden;box-shadow:0 8px 24px rgba(15,37,64,.08);transition:.25s;height:100%;}
    .event-card:hover{transform:translateY(-6px);box-shadow:0 14px 34px rgba(15,37,64,.14);}
    .event-card img{height:190px;object-fit:cover;width:100%;}
    .event-card .body{padding:18px 20px;}
    .event-card h5{font-weight:700;color:var(--navy);}
    .event-card .meta{font-size:13px;color:#8593a0;margin-bottom:10px;}
    .event-card .btn-view{background:var(--teal);color:#fff;border-radius:8px;font-size:13px;font-weight:600;padding:8px 16px;}
    .event-card .btn-view:hover{background:#139aa6;color:#fff;}
    .no-cover{height:190px;background:#e9f6f7;display:flex;align-items:center;justify-content:center;color:var(--teal);font-size:34px;}
    .pagination .page-link{color:var(--navy);}
    .pagination .active .page-link{background:var(--teal);border-color:var(--teal);}
    footer{background:var(--navy);color:#cfe3ec;padding:30px 0;text-align:center;font-size:13px;margin-top:60px;}
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
        <h1>Gallery</h1>
        <div class="crumb"><a href="index.php">Home</a> / <a href="index.php">Pages</a> / <span>Gallery</span></div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <?php if (empty($events)): ?>
            <p class="text-center text-muted py-5">Gallery jald hi update ki jayegi. Please check back soon.</p>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($events as $ev): ?>
            <div class="col-md-6 col-lg-4">
                <div class="event-card">
                    <?php if ($ev['cover_image']): ?>
                        <img src="<?= htmlspecialchars(UPLOAD_URL . $ev['cover_image']) ?>" alt="<?= htmlspecialchars($ev['title']) ?>">
                    <?php else: ?>
                        <div class="no-cover"><i class="fa-solid fa-image"></i></div>
                    <?php endif; ?>
                    <div class="body">
                        <h5><?= htmlspecialchars($ev['title']) ?></h5>
                        <div class="meta">
                            <?php if ($ev['event_date']): ?>
                                <i class="fa-regular fa-calendar"></i> <?= date('d M Y', strtotime($ev['event_date'])) ?> &middot;
                            <?php endif; ?>
                            <?= (int)$ev['photo_count'] ?> photos
                        </div>
                        <a class="btn btn-view" href="gallery-event.php?slug=<?= urlencode($ev['slug']) ?>">
                            View Photos <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($totalPages > 1): ?>
        <nav class="mt-5">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>">&laquo;</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>">&raquo;</a>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

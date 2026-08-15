<?php
require_once __DIR__ . '/admin/config.php';

/* Simple public gallery: images only, with pagination. */
$perPage = 12;
$page = max(1, (int)($_GET['page'] ?? 1));

$total = (int)$pdo->query("SELECT COUNT(*) FROM gallery_photos")->fetchColumn();
$totalPages = max(1, (int)ceil($total / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

$stmt = $pdo->prepare("
    SELECT p.id, p.image_path, p.caption,
           e.title AS event_title, e.event_date,
           (SELECT COUNT(*) FROM gallery_photos p2 WHERE p2.event_id = p.event_id) AS event_photo_count
    FROM gallery_photos p
    LEFT JOIN gallery_events e ON e.id = p.event_id
    ORDER BY p.id DESC
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$photos = $stmt->fetchAll();

$eventCount = (int)$pdo->query("SELECT COUNT(*) FROM gallery_events")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Gallery - Gyaana International School</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <link href="img/logo.jpeg" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <style>
        :root {
            --gallery-purple: #6655f6;
            --gallery-blue: #243b88;
            --gallery-cyan: #06bbcc;
            --gallery-ink: #17203a;
            --gallery-soft: #f6f7fc;
        }

        body { background: var(--gallery-soft); }

        /* Premium gallery hero */
        .gallery-hero {
            position: relative;
            min-height: 330px;
            display: flex;
            align-items: center;
            overflow: visible;
            background:
                linear-gradient(110deg, rgba(10,48,101,.88), rgba(62,33,130,.78)),
                url('img/carousel-1.jpg') center/cover no-repeat;
            margin-bottom: 115px;
        }
        .gallery-hero::after {
            content: '';
            position: absolute;
            inset: auto 0 0;
            height: 90px;
            background: linear-gradient(to bottom, transparent, rgba(246,247,252,1));
        }
        .gallery-hero-content { position: relative; z-index: 2; }
        .gallery-kicker {
            display: inline-block;
            font-family: 'Nunito', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: #ffd43b;
            margin-bottom: 4px;
        }
        .gallery-hero h1 {
            font-size: clamp(42px, 6vw, 70px);
            font-weight: 800;
            line-height: 1;
            margin: 0 0 12px;
            text-shadow: 0 8px 25px rgba(0,0,0,.2);
        }
        .gallery-hero p {
            font-size: 17px;
            margin: 0;
            color: rgba(255,255,255,.9);
        }
        .gallery-hero-line {
            display: flex;
            align-items: center;
            gap: 12px;
            justify-content: center;
            margin-top: 18px;
            color: #fff;
        }
        .gallery-hero-line::before,
        .gallery-hero-line::after {
            content: '';
            width: 38px;
            height: 2px;
            background: rgba(255,255,255,.8);
        }

        /* Floating stats */
        .gallery-stats {
            position: absolute;
            z-index: 5;
            left: 50%;
            bottom: -43px;
            transform: translateX(-50%);
            width: min(790px, calc(100% - 30px));
            background: rgba(255,255,255,.97);
            border-radius: 18px;
            box-shadow: 0 15px 45px rgba(28,38,72,.14);
            padding: 22px 26px;
        }
        .gallery-stat {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 4px 12px;
        }
        .gallery-stat-icon {
            width: 48px;
            height: 48px;
            flex: 0 0 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0edff;
            color: var(--gallery-purple);
            font-size: 20px;
        }
        .gallery-stat strong {
            display: block;
            color: var(--gallery-ink);
            font-family: 'Nunito', sans-serif;
            font-size: 23px;
            line-height: 1.05;
        }
        .gallery-stat span {
            display: block;
            color: #69718a;
            font-size: 13px;
            margin-top: 4px;
        }

        /* Gallery intro */
        .gallery-section { padding: 0 0 70px; }
        .gallery-heading {
            text-align: center;
            max-width: 760px;
            margin: 0 auto 34px;
        }
        .gallery-heading .eyebrow {
            color: var(--gallery-purple);
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            font-size: 13px;
        }
        .gallery-heading h2 {
            font-size: clamp(28px, 4vw, 40px);
            color: var(--gallery-ink);
            font-weight: 800;
            margin: 7px 0 8px;
        }
        .gallery-heading p { color: #737b91; margin: 0; }

        /* Image cards */
        .gallery-card {
            position: relative;
            height: 285px;
            overflow: hidden;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 8px 26px rgba(28,38,72,.10);
            display: block;
            text-decoration: none;
        }
        .gallery-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform .55s cubic-bezier(.2,.7,.2,1);
        }
        .gallery-card::after {
            content: '';
            position: absolute;
            inset: 30% 0 0;
            background: linear-gradient(to bottom, transparent, rgba(8,13,35,.86));
            pointer-events: none;
        }
        .gallery-card:hover img { transform: scale(1.07); }
        .gallery-card:hover .gallery-zoom { transform: translateY(0); opacity: 1; }
        .gallery-zoom {
            position: absolute;
            top: 13px;
            right: 13px;
            z-index: 3;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background: var(--gallery-purple);
            box-shadow: 0 7px 18px rgba(0,0,0,.18);
            transform: translateY(-8px);
            opacity: .92;
            transition: .25s ease;
        }
        .gallery-card-info {
            position: absolute;
            z-index: 3;
            left: 18px;
            right: 18px;
            bottom: 17px;
            color: #fff;
        }
        .gallery-card-info h3 {
            color: #fff;
            font-size: 18px;
            font-weight: 800;
            margin: 0 0 7px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .gallery-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 12px;
            color: rgba(255,255,255,.9);
        }
        .gallery-meta span { display: inline-flex; align-items: center; gap: 5px; }

        .gallery-pagination { margin-top: 38px !important; }
        .gallery-pagination .page-link {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50% !important;
            margin: 0 4px;
            color: var(--gallery-purple);
            border: 1px solid #e5e7f0;
            background: #fff;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(30,40,80,.05);
        }
        .gallery-pagination .page-item.active .page-link {
            background: var(--gallery-blue);
            border-color: var(--gallery-blue);
            color: #fff;
        }
        .gallery-pagination .page-item.disabled .page-link { color: #b8bdca; }
        .gallery-pagination .page-link:hover:not(.disabled) {
            background: var(--gallery-purple);
            border-color: var(--gallery-purple);
            color: #fff;
        }

        .gallery-empty {
            min-height: 260px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            border-radius: 18px;
        }

        /* Lightbox */
        .gallery-lightbox { position: fixed; inset: 0; background: rgba(5,8,20,.94); z-index: 99999; display:flex; align-items:center; justify-content:center; padding:30px 80px; }
        .gallery-lightbox img { max-width:90vw; max-height:88vh; object-fit:contain; border-radius:10px; box-shadow:0 20px 70px rgba(0,0,0,.45); }
        .gallery-lightbox-btn, .gallery-lightbox-close { position:fixed; z-index:100001; border:0; color:#fff; background:rgba(255,255,255,.16); width:50px; height:50px; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:.2s; }
        .gallery-lightbox-btn:hover, .gallery-lightbox-close:hover { background:var(--gallery-purple); }
        .gallery-lightbox-prev { left:22px; top:50%; transform:translateY(-50%); }
        .gallery-lightbox-next { right:22px; top:50%; transform:translateY(-50%); }
        .gallery-lightbox-close { top:20px; right:22px; font-size:25px; }
        .gallery-lightbox-counter { position:fixed; bottom:20px; left:50%; transform:translateX(-50%); color:#fff; background:rgba(0,0,0,.5); padding:7px 14px; border-radius:20px; z-index:100001; font-size:13px; }

        @media (max-width: 991px) {
            .gallery-hero { margin-bottom: 105px; }
            .gallery-stats { padding: 18px 10px; }
            .gallery-stat { padding: 4px 6px; }
        }
        @media (max-width: 767px) {
            .gallery-hero { min-height: 300px; margin-bottom: 175px; }
            .gallery-stats { bottom: -125px; }
            .gallery-stat { margin-bottom: 10px; }
            .gallery-card { height: 250px; }
            .gallery-lightbox { padding: 20px 55px; }
            .gallery-lightbox-btn { width:42px; height:42px; }
            .gallery-lightbox-prev { left:7px; }
            .gallery-lightbox-next { right:7px; }
            .gallery-lightbox-close { right:9px; top:9px; }
        }
    </style>
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="index.php" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary">
                <img src="img/logo.jpeg" alt="logo" height="50" width="55">
                <span style="font-weight:700;"><span style="color:#9B3FAF;">G</span><span style="color:#F4C20D;">y</span><span style="color:#E53935;">a</span><span style="color:#1976D2;">a</span><span style="color:#29B6F6;">n</span><span style="color:#F9A825;">a</span></span>
                <span class="text-dark">International School</span>
            </h2>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="index.php" class="nav-item nav-link">Home</a>
                <a href="about.php" class="nav-item nav-link">About</a>
                <a href="team.php" class="nav-item nav-link">Our Team</a>
                <a href="gallery.php" class="nav-item nav-link active">Gallery</a>
                <a href="contact.php" class="nav-item nav-link">Contact</a>
                <a href="mandatory-public-disclosure.php" class="nav-item nav-link">Mandatory Public Disclosure</a>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Gallery Hero Start -->
    <section class="gallery-hero">
        <div class="container gallery-hero-content">
            <div class="row justify-content-center text-center">
                <div class="col-lg-9">
                    <span class="gallery-kicker">Our Memories</span>
                    <h1 class="text-white">Photo Gallery</h1>
                    <p>Glimpses of learning, celebrations, activities and achievements at Gyaana International School.</p>
                    <div class="gallery-hero-line"><i class="fa fa-camera"></i></div>
                </div>
            </div>
        </div>

        <div class="gallery-stats">
            <div class="row align-items-center">
                <div class="col-6 col-md-3">
                    <div class="gallery-stat">
                        <div class="gallery-stat-icon"><i class="fa fa-images"></i></div>
                        <div><strong><?= number_format($total) ?>+</strong><span>Photos</span></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="gallery-stat">
                        <div class="gallery-stat-icon"><i class="fa fa-calendar-alt"></i></div>
                        <div><strong><?= number_format($eventCount) ?>+</strong><span>Events</span></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="gallery-stat">
                        <div class="gallery-stat-icon"><i class="fa fa-layer-group"></i></div>
                        <div><strong><?= number_format($totalPages) ?></strong><span>Gallery Pages</span></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="gallery-stat">
                        <div class="gallery-stat-icon"><i class="fa fa-heart"></i></div>
                        <div><strong>100%</strong><span>School Memories</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Gallery Hero End -->

    <!-- Gallery Start -->
    <section class="gallery-section">
        <div class="container-xxl">
            <div class="container">
                <div class="gallery-heading">
                    <div class="eyebrow">School Life • Moments • Memories</div>
                    <h2>Explore Our School Memories</h2>
                    <p>Every picture tells a story. Browse moments from our school events, activities, celebrations and everyday learning.</p>
                </div>

                <?php if (empty($photos)): ?>
                    <div class="gallery-empty">
                        <p class="text-muted mb-0">Gallery jald hi update ki jayegi. Please check back soon.</p>
                    </div>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($photos as $photo): ?>
                            <?php
                                $eventTitle = trim($photo['event_title'] ?? '');
                                $eventTitle = $eventTitle !== '' ? $eventTitle : 'School Memories';
                                $eventDate = !empty($photo['event_date']) ? date('d M Y', strtotime($photo['event_date'])) : '';
                                $eventCountForCard = (int)($photo['event_photo_count'] ?? 0);
                                $imageUrl = UPLOAD_URL . $photo['image_path'];
                            ?>
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <a href="<?= htmlspecialchars($imageUrl) ?>"
                                   class="gallery-card"
                                   data-gallery="school-gallery"
                                   aria-label="Open <?= htmlspecialchars($eventTitle) ?> photo">
                                    <img src="<?= htmlspecialchars($imageUrl) ?>"
                                         alt="<?= htmlspecialchars($photo['caption'] ?: $eventTitle) ?>"
                                         loading="lazy">
                                    <span class="gallery-zoom"><i class="fa fa-search-plus"></i></span>
                                    <span class="gallery-card-info">
                                        <h3><?= htmlspecialchars($eventTitle) ?></h3>
                                        <span class="gallery-meta">
                                            <?php if ($eventDate): ?>
                                                <span><i class="fa fa-calendar-alt"></i> <?= htmlspecialchars($eventDate) ?></span>
                                            <?php endif; ?>
                                            <?php if ($eventCountForCard > 0): ?>
                                                <span><i class="fa fa-images"></i> <?= $eventCountForCard ?></span>
                                            <?php endif; ?>
                                        </span>
                                    </span>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if ($totalPages > 1): ?>
                        <nav class="gallery-pagination" aria-label="Gallery pagination">
                            <ul class="pagination justify-content-center mb-0">
                                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= max(1, $page - 1) ?>" aria-label="Previous">&#10094;</a>
                                </li>

                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= min($totalPages, $page + 1) ?>" aria-label="Next">&#10095;</a>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <!-- Gallery End -->

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Quick Links</h4>
                    <a class="btn btn-link" href="index.php">Home</a>
                    <a class="btn btn-link" href="about.php">About Us</a>
                    <a class="btn btn-link" href="gallery.php">Gallery</a>
                    <a class="btn btn-link" href="privacy_policy.php">Privacy Policy</a>
                    <a class="btn btn-link" href="disclaimer.php">Disclaimer</a>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Contact Us</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Kahli, Gausganj, Hardoi, UP (241305)</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+91 7522852280</p>
                    <p class="mb-2 d-flex"><i class="fa fa-envelope me-3"></i><span style="overflow-wrap:anywhere;">principal@gyaanainternationalschool.com</span></p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-outline-light btn-social" href="#"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-outline-light btn-social" href="#"><i class="fab fa-instagram"></i></a>
                        <a class="btn btn-outline-light btn-social" href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">School Timings</h4>
                    <p><i class="fa fa-clock me-2"></i>Monday - Friday</p>
                    <p>08:00 AM - 02:30 PM</p>
                    <p class="mt-3"><i class="fa fa-calendar me-2"></i>Saturday</p>
                    <p>08:00 AM - 12:00 PM</p>
                    <p class="mt-3"><i class="fa fa-ban me-2"></i>Sunday Holiday</p>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Admissions</h4>
                    <p>Admissions are open for Nursery to Class VIII. Join Gyaana International School and provide your child with a strong foundation for a bright future.</p>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; 2026 <a class="border-bottom" href="#">Gyaana International School</a>. All Rights Reserved.
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <span>Designed &amp; Developed by <a class="border-bottom" href="https://nextechis.in" target="_blank">Nextechis Software Solutions</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>

    <!-- Gallery Lightbox -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const galleryLinks = Array.from(document.querySelectorAll('[data-gallery="school-gallery"]'));
        if (!galleryLinks.length) return;

        let currentIndex = 0;
        let lightbox = null;

        function openLightbox(index) {
            currentIndex = index;
            lightbox = document.createElement('div');
            lightbox.className = 'gallery-lightbox';
            lightbox.innerHTML = `
                <button type="button" class="gallery-lightbox-btn gallery-lightbox-prev" aria-label="Previous image">&#10094;</button>
                <img src="" alt="Gallery image">
                <button type="button" class="gallery-lightbox-btn gallery-lightbox-next" aria-label="Next image">&#10095;</button>
                <button type="button" class="gallery-lightbox-close" aria-label="Close">&times;</button>
                <div class="gallery-lightbox-counter"></div>
            `;
            document.body.appendChild(lightbox);

            const image = lightbox.querySelector('img');
            const prev = lightbox.querySelector('.gallery-lightbox-prev');
            const next = lightbox.querySelector('.gallery-lightbox-next');
            const close = lightbox.querySelector('.gallery-lightbox-close');
            const counter = lightbox.querySelector('.gallery-lightbox-counter');

            function update() {
                const link = galleryLinks[currentIndex];
                image.src = link.href;
                const sourceImage = link.querySelector('img');
                image.alt = sourceImage ? sourceImage.alt : 'Gyaana International School Gallery';
                counter.textContent = `${currentIndex + 1} / ${galleryLinks.length}`;
                const hide = galleryLinks.length <= 1;
                prev.style.display = hide ? 'none' : 'flex';
                next.style.display = hide ? 'none' : 'flex';
            }

            function previous(e) {
                if (e) e.stopPropagation();
                currentIndex = (currentIndex - 1 + galleryLinks.length) % galleryLinks.length;
                update();
            }

            function following(e) {
                if (e) e.stopPropagation();
                currentIndex = (currentIndex + 1) % galleryLinks.length;
                update();
            }

            function closeLightbox() {
                if (lightbox) lightbox.remove();
                lightbox = null;
                document.removeEventListener('keydown', keyboard);
            }

            function keyboard(e) {
                if (e.key === 'ArrowLeft') previous();
                if (e.key === 'ArrowRight') following();
                if (e.key === 'Escape') closeLightbox();
            }

            prev.addEventListener('click', previous);
            next.addEventListener('click', following);
            close.addEventListener('click', function (e) { e.stopPropagation(); closeLightbox(); });
            image.addEventListener('click', function (e) { e.stopPropagation(); });
            lightbox.addEventListener('click', function (e) {
                if (e.target === lightbox) closeLightbox();
            });
            document.addEventListener('keydown', keyboard);
            update();
        }

        galleryLinks.forEach(function (link, index) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                openLightbox(index);
            });
        });
    });
    </script>
</body>
</html>

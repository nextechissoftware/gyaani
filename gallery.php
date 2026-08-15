<?php
require_once __DIR__ . '/admin/config.php';

/* Simple public gallery: images only, with pagination. */
$perPage = 12;
$page = max(1, (int)($_GET['page'] ?? 1));

$total = (int)$pdo->query("SELECT COUNT(*) FROM gallery_photos")->fetchColumn();
$totalPages = max(1, (int)ceil($total / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

$stmt = $pdo->prepare("SELECT id, image_path, caption FROM gallery_photos ORDER BY id DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$photos = $stmt->fetchAll();
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
        .gallery-item {
            position: relative;
            overflow: hidden;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .08);
            height: 280px;
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform .35s ease;
        }
        .gallery-item:hover img {
            transform: scale(1.05);
        }
        .gallery-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, .28);
            opacity: 0;
            transition: opacity .25s ease;
        }
        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }
        .gallery-overlay i {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #06BBCC;
            color: #fff;
            font-size: 18px;
        }
        .gallery-empty {
            min-height: 260px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .gallery-pagination .page-link {
            color: #06BBCC;
            border-color: #e8eef1;
        }
        .gallery-pagination .page-item.active .page-link {
            background: #06BBCC;
            border-color: #06BBCC;
            color: #fff;
        }
        .gallery-pagination .page-item.disabled .page-link {
            color: #adb5bd;
        }
/* Gallery Lightbox */
.gallery-lightbox {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.92);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 25px 80px;
}

.gallery-lightbox img {
    max-width: 90vw;
    max-height: 88vh;
    object-fit: contain;
    border-radius: 5px;
    user-select: none;
}

.gallery-lightbox-btn {
    position: fixed;
    top: 50%;
    transform: translateY(-50%);
    width: 52px;
    height: 52px;
    border: none;
    border-radius: 50%;
    background: rgba(255,255,255,0.18);
    color: #fff;
    font-size: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 100001;
    transition: all .2s ease;
}

.gallery-lightbox-btn:hover {
    background: #06BBCC;
}

.gallery-lightbox-prev {
    left: 20px;
}

.gallery-lightbox-next {
    right: 20px;
}

.gallery-lightbox-close {
    position: fixed;
    top: 20px;
    right: 25px;
    width: 45px;
    height: 45px;
    border: none;
    border-radius: 50%;
    background: rgba(255,255,255,0.18);
    color: #fff;
    font-size: 25px;
    cursor: pointer;
    z-index: 100001;
}

.gallery-lightbox-close:hover {
    background: #dc3545;
}

.gallery-lightbox-counter {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    color: #fff;
    background: rgba(0,0,0,.55);
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 14px;
    z-index: 100001;
}

@media (max-width: 768px) {
    .gallery-lightbox {
        padding: 20px 55px;
    }

    .gallery-lightbox-btn {
        width: 42px;
        height: 42px;
        font-size: 24px;
    }

    .gallery-lightbox-prev {
        left: 8px;
    }

    .gallery-lightbox-next {
        right: 8px;
    }

    .gallery-lightbox-close {
        top: 10px;
        right: 10px;
    }

    .gallery-lightbox img {
        max-width: 95vw;
        max-height: 82vh;
    }
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

    <!-- Header Start -->
    <div class="container-fluid bg-primary py-5 mb-5 page-header">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <h1 class="display-3 text-white animated slideInDown">Gallery</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a class="text-white" href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a class="text-white" href="#">Pages</a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page">Gallery</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Gallery Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <?php if (empty($photos)): ?>
                <div class="gallery-empty">
                    <p class="text-muted mb-0">Gallery jald hi update ki jayegi. Please check back soon.</p>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($photos as $photo): ?>
                        <div class="col-lg-4 col-md-6 wow fadeInUp">
                            <a href="<?= htmlspecialchars(UPLOAD_URL . $photo['image_path']) ?>" class="gallery-item d-block" data-gallery="school-gallery" aria-label="Open gallery image">
                                <img src="<?= htmlspecialchars(UPLOAD_URL . $photo['image_path']) ?>" alt="<?= htmlspecialchars($photo['caption'] ?: 'Gyaana International School Gallery') ?>" loading="lazy">
                                <span class="gallery-overlay"><i class="fa fa-search-plus"></i></span>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($totalPages > 1): ?>
                    <nav class="gallery-pagination mt-5" aria-label="Gallery pagination">
                        <ul class="pagination justify-content-center mb-0">
                            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= max(1, $page - 1) ?>" aria-label="Previous">&laquo;</a>
                            </li>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= min($totalPages, $page + 1) ?>" aria-label="Next">&raquo;</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
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

    <!-- Simple image viewer; no event pages or event UI. -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const galleryLinks = Array.from(
        document.querySelectorAll('[data-gallery="school-gallery"]')
    );

    if (!galleryLinks.length) return;

    let currentIndex = 0;
    let lightbox = null;
    let lightboxImage = null;
    let counter = null;

    function openLightbox(index) {
        currentIndex = index;

        lightbox = document.createElement('div');
        lightbox.className = 'gallery-lightbox';

        lightbox.innerHTML = `
            <button class="gallery-lightbox-btn gallery-lightbox-prev"
                    aria-label="Previous image">
                &#10094;
            </button>

            <img src="" alt="Gallery Image">

            <button class="gallery-lightbox-btn gallery-lightbox-next"
                    aria-label="Next image">
                &#10095;
            </button>

            <button class="gallery-lightbox-close"
                    aria-label="Close">
                &times;
            </button>

            <div class="gallery-lightbox-counter"></div>
        `;

        document.body.appendChild(lightbox);

        lightboxImage = lightbox.querySelector('img');
        counter = lightbox.querySelector('.gallery-lightbox-counter');

        const prevBtn = lightbox.querySelector('.gallery-lightbox-prev');
        const nextBtn = lightbox.querySelector('.gallery-lightbox-next');
        const closeBtn = lightbox.querySelector('.gallery-lightbox-close');

        function updateImage() {
            const link = galleryLinks[currentIndex];

            lightboxImage.src = link.href;

            const img = link.querySelector('img');
            lightboxImage.alt = img
                ? img.alt
                : 'Gyaana International School Gallery';

            counter.textContent =
                (currentIndex + 1) + ' / ' + galleryLinks.length;

            // Hide arrows when only one image
            if (galleryLinks.length <= 1) {
                prevBtn.style.display = 'none';
                nextBtn.style.display = 'none';
            } else {
                prevBtn.style.display = 'flex';
                nextBtn.style.display = 'flex';
            }
        }

        function previousImage(e) {
            if (e) e.stopPropagation();

            currentIndex =
                (currentIndex - 1 + galleryLinks.length)
                % galleryLinks.length;

            updateImage();
        }

        function nextImage(e) {
            if (e) e.stopPropagation();

            currentIndex =
                (currentIndex + 1)
                % galleryLinks.length;

            updateImage();
        }

        function closeLightbox() {
            if (lightbox) {
                lightbox.remove();
                lightbox = null;
            }

            document.removeEventListener('keydown', keyboardNavigation);
        }

        function keyboardNavigation(e) {
            if (e.key === 'ArrowLeft') {
                previousImage();
            }

            if (e.key === 'ArrowRight') {
                nextImage();
            }

            if (e.key === 'Escape') {
                closeLightbox();
            }
        }

        prevBtn.addEventListener('click', previousImage);
        nextBtn.addEventListener('click', nextImage);
        closeBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            closeLightbox();
        });

        lightbox.addEventListener('click', function (e) {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });

        lightboxImage.addEventListener('click', function (e) {
            e.stopPropagation();
        });

        document.addEventListener('keydown', keyboardNavigation);

        updateImage();
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

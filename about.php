<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>About Us - Gyaana International School</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="description" content="Learn more about Gyaana International School, our approach to education, campus life and values.">

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
        /* =========================================================
           ABOUT PAGE REDESIGN
           Existing Gyaana teal + navy theme is intentionally kept.
           ========================================================= */
        .about-page {
            --about-teal: #06BBCC;
            --about-teal-dark: #079aaa;
            --about-navy: #181d38;
            --about-soft: #effbfc;
            --about-border: #dceff2;
            background: #fff;
        }

        .about-page .page-header {
            margin-bottom: 0 !important;
        }

        .about-page .intro-features {
            position: relative;
            z-index: 5;
            margin-top: -1px;
            padding-top: 0;
        }

        .about-page .feature-grid {
            margin-top: 0;
            transform: translateY(0);
        }

        .about-page .feature-card {
            height: 100%;
            min-height: 255px;
            padding: 38px 25px 30px;
            background: linear-gradient(180deg, #f3fcfd 0%, #edf9fb 100%);
            border: 1px solid rgba(6, 187, 204, .10);
            border-bottom: 3px solid var(--about-teal);
            border-radius: 4px;
            text-align: center;
            transition: all .3s ease;
        }

        .about-page .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 40px rgba(24, 29, 56, .10);
            background: #fff;
        }

        .about-page .feature-icon {
            width: 68px;
            height: 68px;
            margin: 0 auto 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            color: var(--about-teal);
            background: rgba(6, 187, 204, .10);
            font-size: 31px;
            transition: .3s ease;
        }

        .about-page .feature-card:hover .feature-icon {
            color: #fff;
            background: var(--about-teal);
            transform: rotate(-4deg);
        }

        .about-page .feature-card h5 {
            color: var(--about-navy);
            font-size: 18px;
            margin-bottom: 12px;
        }

        .about-page .feature-card p {
            color: #536176;
            line-height: 1.65;
            margin: 0;
            font-size: 14px;
        }

        .about-page .about-main {
            padding-top: 75px !important;
            padding-bottom: 70px !important;
        }

        .about-page .about-photo-wrap {
            position: relative;
            padding: 0 18px 18px 0;
        }

        .about-page .about-photo {
            width: 100%;
            height: 510px;
            object-fit: cover;
            border-radius: 6px;
            display: block;
            box-shadow: 0 18px 45px rgba(24, 29, 56, .12);
        }

        .about-page .photo-label {
            position: absolute;
            left: 20px;
            bottom: 38px;
            max-width: 245px;
            padding: 18px 22px;
            background: rgba(6, 187, 204, .94);
            color: #fff;
            border-radius: 4px;
            box-shadow: 0 12px 28px rgba(6, 187, 204, .25);
        }

        .about-page .photo-label strong {
            display: block;
            font-family: "Nunito", sans-serif;
            font-size: 22px;
            line-height: 1.15;
        }

        .about-page .section-kicker {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            color: var(--about-teal);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .about-page .section-kicker::after {
            content: "";
            width: 42px;
            height: 2px;
            background: var(--about-teal);
        }

        .about-page .about-heading {
            color: var(--about-navy);
            font-size: clamp(30px, 4vw, 42px);
            line-height: 1.15;
            margin-bottom: 22px;
        }

        .about-page .about-copy {
            color: #536176;
            line-height: 1.8;
            font-size: 15px;
        }

        .about-page .check-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 13px 22px;
            margin: 25px 0 30px;
        }

        .about-page .check-item {
            display: flex;
            align-items: center;
            gap: 9px;
            color: #39465c;
            font-size: 14px;
            font-weight: 500;
        }

        .about-page .check-item i {
            color: var(--about-teal);
            font-size: 16px;
        }

        .about-page .campus-section {
            background: #f8fcfd;
            border-top: 1px solid #eef6f7;
            border-bottom: 1px solid #eef6f7;
        }

        .about-page .campus-intro {
            max-width: 420px;
        }

        .about-page .campus-title {
            color: var(--about-navy);
            font-size: 34px;
            line-height: 1.2;
        }

        .about-page .campus-text {
            color: #607087;
            line-height: 1.75;
        }

        .about-page .campus-gallery {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px;
        }

        .about-page .campus-item {
            position: relative;
            height: 205px;
            overflow: hidden;
            border-radius: 7px;
            box-shadow: 0 10px 25px rgba(24, 29, 56, .08);
        }

        .about-page .campus-item:nth-child(2),
        .about-page .campus-item:nth-child(3) {
            height: 235px;
        }

        .about-page .campus-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .5s ease;
        }

        .about-page .campus-item:hover img {
            transform: scale(1.07);
        }

        .about-page .campus-overlay {
            position: absolute;
            inset: auto 0 0 0;
            padding: 42px 16px 14px;
            background: linear-gradient(transparent, rgba(24, 29, 56, .82));
            color: #fff;
            font-weight: 700;
            font-size: 15px;
        }

        .about-page .stats-strip {
            margin: 65px 0 0;
            padding: 30px 20px;
            background: linear-gradient(135deg, #effbfc, #f7fdfe);
            border: 1px solid var(--about-border);
            border-radius: 8px;
        }

        .about-page .stat {
            text-align: center;
            padding: 8px 18px;
            border-right: 1px solid #d5eaed;
        }

        .about-page .stat:last-child {
            border-right: 0;
        }

        .about-page .stat-icon {
            color: var(--about-teal);
            font-size: 30px;
            margin-bottom: 6px;
        }

        .about-page .stat-number {
            color: var(--about-navy);
            font-family: "Nunito", sans-serif;
            font-size: 29px;
            font-weight: 800;
            display: block;
            line-height: 1.1;
        }

        .about-page .stat-label {
            color: #66758a;
            font-size: 13px;
            margin-top: 4px;
        }

        .about-page .admission-box {
            margin-top: 55px;
            padding: 28px 35px;
            background: #effbfc;
            border: 1px solid var(--about-border);
            border-radius: 8px;
        }

        .about-page .admission-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(6, 187, 204, .12);
            color: var(--about-teal);
            font-size: 27px;
        }

        .about-page .admission-box h3 {
            color: var(--about-teal);
            font-size: 27px;
            margin-bottom: 4px;
        }

        .about-page .admission-box p {
            color: #536176;
            margin: 0;
        }

        .about-page .admission-btn {
            white-space: nowrap;
            padding: 13px 25px;
            border-radius: 4px;
            font-weight: 700;
            background: var(--about-teal);
            border-color: var(--about-teal);
        }

        .about-page .admission-btn:hover {
            background: var(--about-teal-dark);
            border-color: var(--about-teal-dark);
        }

        @media (max-width: 991.98px) {
            .about-page .about-photo {
                height: 430px;
            }

            .about-page .campus-intro {
                max-width: none;
                margin-bottom: 30px;
            }

            .about-page .stat:nth-child(2) {
                border-right: 0;
            }
        }

        @media (max-width: 575.98px) {
            .about-page .feature-card {
                min-height: auto;
            }

            .about-page .about-main {
                padding-top: 55px !important;
            }

            .about-page .about-photo {
                height: 360px;
            }

            .about-page .photo-label {
                left: 10px;
                bottom: 28px;
            }

            .about-page .check-list {
                grid-template-columns: 1fr;
            }

            .about-page .campus-gallery {
                grid-template-columns: 1fr;
            }

            .about-page .campus-item,
            .about-page .campus-item:nth-child(2),
            .about-page .campus-item:nth-child(3) {
                height: 230px;
            }

            .about-page .stats-strip {
                padding: 20px 10px;
            }

            .about-page .stat {
                border-right: 0;
                border-bottom: 1px solid #d5eaed;
                padding: 18px 10px;
            }

            .about-page .stat:last-child {
                border-bottom: 0;
            }

            .about-page .admission-box {
                padding: 25px 20px;
                text-align: center;
            }

            .about-page .admission-icon {
                margin: 0 auto 15px;
            }

            .about-page .admission-btn {
                margin-top: 18px;
            }
        }
    </style>
</head>

<body class="about-page">

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
                <img src="img/logo.jpeg" alt="Gyaana International School logo" height="50" width="55">
                <span style="font-weight:700;">
                    <span style="color:#9B3FAF;">G</span><span style="color:#F4C20D;">y</span><span style="color:#E53935;">a</span><span style="color:#1976D2;">a</span><span style="color:#29B6F6;">n</span><span style="color:#F9A825;">a</span>
                </span>
                <span class="text-dark">International School</span>
            </h2>
        </a>

        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="index.php" class="nav-item nav-link">Home</a>
                <a href="about.php" class="nav-item nav-link active">About</a>
                <a href="team.php" class="nav-item nav-link">Our Team</a>
                <a href="gallery.php" class="nav-item nav-link">Gallery</a>
                <a href="contact.php" class="nav-item nav-link">Contact</a>
                <a href="mandatory-public-disclosure.php" class="nav-item nav-link">Mandatory Public Disclosure</a>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Header Start -->
    <div class="container-fluid bg-primary py-5 mb-0 page-header">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <h1 class="display-3 text-white animated slideInDown">About Us</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center mb-0">
                            <li class="breadcrumb-item"><a class="text-white" href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a class="text-white" href="#">Pages</a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page">About</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Feature Cards Start -->
    <section class="intro-features">
        <div class="container py-5">
            <div class="row g-4 feature-grid">
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa fa-chalkboard-teacher"></i></div>
                        <h5>Experienced Faculty</h5>
                        <p>Dedicated and qualified teachers committed to providing quality education and nurturing every student's potential.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa fa-school"></i></div>
                        <h5>Modern Campus</h5>
                        <p>A safe, vibrant, and well-equipped learning environment designed to inspire creativity and academic excellence.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa fa-futbol"></i></div>
                        <h5>Sports &amp; Activities</h5>
                        <p>Encouraging physical fitness, teamwork, leadership, and creativity through sports and extracurricular activities.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.4s">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa fa-book-reader"></i></div>
                        <h5>Smart Learning</h5>
                        <p>Interactive classrooms and a rich library that make learning engaging, innovative, and student-centered.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Feature Cards End -->

    <!-- Welcome Start -->
    <section class="about-main">
        <div class="container">
            <div class="row g-5 align-items-center">

                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="about-photo-wrap">
                        <img class="about-photo" src="img/about.jpg" alt="Gyaana International School">
                        <div class="photo-label">
                            <strong>Building Bright Futures</strong>
                            <span>Every Day</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="section-kicker">About Us</div>
                    <h2 class="about-heading">Welcome to Gyaana International School</h2>

                    <p class="about-copy mb-4">
                        At Gyaana International School, we believe that education is not limited to textbooks and classrooms.
                        Our aim is to create a nurturing and inspiring environment where every child is encouraged to learn,
                        explore, think creatively, and develop confidence.
                    </p>

                    <p class="about-copy mb-4">
                        With dedicated teachers, modern learning practices, and a strong emphasis on values and character
                        development, we strive to provide our students with a balanced education that prepares them for
                        academic success as well as the challenges of the future.
                    </p>

                    <div class="check-list">
                        <div class="check-item"><i class="fas fa-check-circle"></i>Experienced &amp; Dedicated Faculty</div>
                        <div class="check-item"><i class="fas fa-check-circle"></i>Student-Centered Learning</div>
                        <div class="check-item"><i class="fas fa-check-circle"></i>Academic Excellence</div>
                        <div class="check-item"><i class="fas fa-check-circle"></i>Strong Moral Values</div>
                        <div class="check-item"><i class="fas fa-check-circle"></i>Holistic Child Development</div>
                        <div class="check-item"><i class="fas fa-check-circle"></i>Safe &amp; Nurturing Environment</div>
                    </div>

                    <a href="team.php" class="btn btn-primary py-3 px-5">Meet Our Team <i class="fa fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </section>
    <!-- Welcome End -->

    <!-- Campus Life Start -->
    <section class="campus-section py-5">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="campus-intro">
                        <div class="section-kicker">Life at Gyaana</div>
                        <h2 class="campus-title mb-3">Our Campus Life</h2>
                        <p class="campus-text mb-4">
                            A glimpse of learning, creativity, celebration, and endless opportunities
                            that help our students grow with confidence.
                        </p>
                        <a href="gallery.php" class="btn btn-primary py-3 px-4">
                            Explore Gallery <i class="fa fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.25s">
                    <div class="campus-gallery">

                        <!-- Existing photos from the website's img folder -->
                        <div class="campus-item">
                            <img src="img/WhatsApp Image 2026-08-06 at 10.28.20 AM (1).jpeg" alt="Students participating in a school activity">
                            <div class="campus-overlay">Learning &amp; Creativity</div>
                        </div>

                        <div class="campus-item">
                            <img src="img/WhatsApp Image 2026-08-06 at 10.27.56 AM.jpeg" alt="Students participating in outdoor activities">
                            <div class="campus-overlay">Sports &amp; Fitness</div>
                        </div>

                        <div class="campus-item">
                            <img src="img/WhatsApp Image 2026-08-06 at 10.28.02 AM.jpeg" alt="Students learning together">
                            <div class="campus-overlay">Student Development</div>
                        </div>

                        <div class="campus-item">
                            <img src="img/WhatsApp Image 2026-08-06 at 10.27.57 AM.jpeg" alt="Students celebrating a school activity">
                            <div class="campus-overlay">Celebrations &amp; Activities</div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="stats-strip">
                <div class="row g-0">
                    <div class="col-lg-3 col-6">
                        <div class="stat">
                            <div class="stat-icon"><i class="fa fa-users"></i></div>
                            <span class="stat-number">500+</span>
                            <div class="stat-label">Happy Students</div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="stat">
                            <div class="stat-icon"><i class="fa fa-graduation-cap"></i></div>
                            <span class="stat-number">30+</span>
                            <div class="stat-label">Experienced Teachers</div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="stat">
                            <div class="stat-icon"><i class="fa fa-trophy"></i></div>
                            <span class="stat-number">20+</span>
                            <div class="stat-label">Awards &amp; Achievements</div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="stat">
                            <div class="stat-icon"><i class="fa fa-school"></i></div>
                            <span class="stat-number">5+</span>
                            <div class="stat-label">Years of Excellence</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admission CTA -->
            <div class="admission-box">
                <div class="row align-items-center g-3">
                    <div class="col-auto">
                        <div class="admission-icon"><i class="fa fa-school"></i></div>
                    </div>

                    <div class="col">
                        <h3>Admissions Open</h3>
                        <p>Give your child the best start in life. Join Gyaana International School and be a part of a community that cares.</p>
                    </div>

                    <div class="col-auto">
                        <a href="contact.php" class="btn btn-primary admission-btn">
                            ENQUIRE NOW <i class="fa fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Campus Life End -->

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer pt-5 mt-0 wow fadeIn" data-wow-delay="0.1s">
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
                    <p class="mb-2 d-flex">
                        <i class="fa fa-envelope me-3"></i>
                        <span style="overflow-wrap:anywhere;">gyaanagausganj@gmail.com</span>
                    </p>
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
                    <div class="fb-page-embed">
                        <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2F100077246062188&tabs=timeline&width=280&height=500&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=false"
                            width="280" height="500" style="border:none;overflow:hidden;max-width:100%;" scrolling="no" frameborder="0"
                            allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" loading="lazy"></iframe>
                        <a href="https://www.facebook.com/people/Gyaana-International-School/100077246062188/" target="_blank" rel="noopener" class="d-block text-white mt-2" style="font-size:0.9rem;">
                            <i class="fab fa-facebook-f me-1"></i> View our Facebook Page
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; 2026 <a class="border-bottom" href="#">Gyaana International School</a>.
                        All Rights Reserved.
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <span>
                            Designed &amp; Developed by
                            <a class="border-bottom" href="https://nextechis.in" target="_blank">Nextechis Software Solutions</a>
                        </span>
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
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK KIP Kuliah — Sistem Seleksi Penerima Beasiswa</title>
    <meta name="description" content="Sistem Pendukung Keputusan untuk seleksi penerima beasiswa KIP Kuliah menggunakan metode AHP dan SAW secara transparan dan objektif.">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* ============================================ */
        /*  LANDING PAGE STYLES                        */
        /* ============================================ */

        body {
            overflow-x: hidden;
            display: block;
        }

        /* --- Navbar --- */
        .landing-nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 0 40px;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(11, 15, 25, 0.65);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-glass);
            transition: var(--transition);
        }

        .landing-nav.scrolled {
            background: rgba(11, 15, 25, 0.9);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .nav-logo h2 {
            font-family: var(--font-heading);
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: 0.5px;
        }

        .nav-logo span {
            color: var(--primary);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
            list-style: none;
        }

        .nav-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.92rem;
            font-weight: 500;
            padding: 8px 18px;
            border-radius: 8px;
            transition: var(--transition);
        }

        .nav-links a:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.06);
        }

        .nav-links .nav-cta {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 14px var(--primary-glow);
            margin-left: 8px;
        }

        .nav-links .nav-cta:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        /* --- Sections --- */
        .landing-section {
            padding: 120px 60px 80px;
            max-width: 1280px;
            margin: 0 auto;
        }

        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.25);
            color: var(--primary);
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 0.82rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }

        .section-heading {
            font-family: var(--font-heading);
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 14px;
            line-height: 1.3;
        }

        .section-desc {
            color: var(--text-muted);
            font-size: 1.05rem;
            line-height: 1.7;
            max-width: 600px;
        }

        /* --- Hero --- */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 72px;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, transparent 70%);
            top: -100px;
            right: -100px;
            pointer-events: none;
            animation: float-glow 8s ease-in-out infinite alternate;
        }

        .hero::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.15) 0%, transparent 70%);
            bottom: -50px;
            left: -50px;
            pointer-events: none;
            animation: float-glow 10s ease-in-out infinite alternate-reverse;
        }

        @keyframes float-glow {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, -30px) scale(1.1); }
        }

        .hero-inner {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        .hero-text h1 {
            font-family: var(--font-heading);
            font-size: 3.4rem;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 24px;
            color: #fff;
        }

        .hero-text h1 .highlight {
            background: linear-gradient(135deg, var(--primary) 0%, hsl(270, 80%, 65%) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-text p {
            color: var(--text-muted);
            font-size: 1.15rem;
            line-height: 1.8;
            margin-bottom: 36px;
            max-width: 520px;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .hero-actions .btn {
            padding: 14px 32px;
            font-size: 1rem;
            border-radius: 12px;
        }

        /* Hero illustration */
        .hero-illustration {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .hero-card {
            background: var(--bg-glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-glass);
            border-radius: 20px;
            padding: 36px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            animation: float-card 6s ease-in-out infinite;
        }

        @keyframes float-card {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }

        .hero-card-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-glass);
        }

        .hero-card-header .icon-box {
            width: 48px;
            height: 48px;
            background: var(--primary-glow);
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--primary);
        }

        .hero-card-header div h3 {
            font-family: var(--font-heading);
            font-weight: 600;
            font-size: 1.1rem;
        }

        .hero-card-header div p {
            color: var(--text-muted);
            font-size: 0.82rem;
            margin-top: 2px;
        }

        .hero-card-steps {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .hero-card-step {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            transition: var(--transition);
        }

        .hero-card-step:hover {
            background: rgba(255, 255, 255, 0.06);
            transform: translateX(4px);
        }

        .hero-card-step .step-num {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--primary);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 700;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .hero-card-step span {
            font-size: 0.92rem;
            font-weight: 500;
            color: var(--text-main);
        }

        /* --- About --- */
        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 24px;
            margin-top: 50px;
        }

        .about-card {
            background: var(--bg-glass);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border-glass);
            border-radius: 16px;
            padding: 32px 28px;
            transition: var(--transition);
        }

        .about-card:hover {
            transform: translateY(-6px);
            border-color: var(--border-glass-focus);
            box-shadow: 0 12px 40px rgba(99, 102, 241, 0.1);
        }

        .about-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }

        .about-card h3 {
            font-family: var(--font-heading);
            font-size: 1.15rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .about-card p {
            color: var(--text-muted);
            font-size: 0.92rem;
            line-height: 1.7;
        }

        /* --- Criteria --- */
        .criteria-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
            margin-top: 50px;
        }

        .criteria-card {
            background: var(--bg-glass);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border-glass);
            border-radius: 16px;
            padding: 30px 26px;
            text-align: center;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .criteria-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), hsl(270, 80%, 65%));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .criteria-card:hover::before {
            opacity: 1;
        }

        .criteria-card:hover {
            transform: translateY(-6px);
            border-color: var(--border-glass-focus);
        }

        .criteria-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: var(--primary-glow);
            color: var(--primary);
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 18px;
        }

        .criteria-card h3 {
            font-family: var(--font-heading);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .criteria-card p {
            color: var(--text-muted);
            font-size: 0.88rem;
            line-height: 1.6;
        }

        .criteria-card .criteria-type {
            display: inline-block;
            margin-top: 14px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .type-benefit {
            background: var(--success-bg);
            color: var(--success);
        }

        .type-cost {
            background: var(--danger-bg);
            color: var(--danger);
        }

        /* --- Alur Pendaftaran --- */
        .alur-timeline {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
            position: relative;
            padding: 0 20px;
        }

        .alur-timeline::before {
            content: '';
            position: absolute;
            top: 35px;
            left: 10%;
            right: 10%;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), hsl(270, 80%, 65%), var(--success));
            z-index: 0;
            border-radius: 2px;
        }

        .alur-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            flex: 1;
            position: relative;
            z-index: 1;
        }

        .alur-num {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: var(--bg-app);
            border: 3px solid var(--primary);
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--primary);
            margin-bottom: 20px;
            box-shadow: 0 0 20px var(--primary-glow);
            transition: var(--transition);
        }

        .alur-step:hover .alur-num {
            background: var(--primary);
            color: #fff;
            transform: scale(1.1);
        }

        .alur-step h4 {
            font-family: var(--font-heading);
            font-size: 1.05rem;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .alur-step p {
            color: var(--text-muted);
            font-size: 0.85rem;
            max-width: 160px;
            line-height: 1.5;
        }

        /* --- FAQ --- */
        .faq-list {
            margin-top: 50px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            max-width: 800px;
        }

        .faq-item {
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: 14px;
            overflow: hidden;
            transition: var(--transition);
        }

        .faq-item:hover {
            border-color: var(--border-glass-focus);
        }

        .faq-question {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.98rem;
            color: var(--text-main);
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            font-family: var(--font-body);
            transition: var(--transition);
        }

        .faq-question:hover {
            color: var(--primary);
        }

        .faq-question i {
            transition: transform 0.3s;
            flex-shrink: 0;
            color: var(--text-muted);
        }

        .faq-item.open .faq-question i {
            transform: rotate(180deg);
            color: var(--primary);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s ease, padding 0.35s ease;
        }

        .faq-item.open .faq-answer {
            max-height: 300px;
        }

        .faq-answer-inner {
            padding: 0 24px 20px;
            color: var(--text-muted);
            font-size: 0.92rem;
            line-height: 1.75;
        }

        /* --- CTA Banner --- */
        .cta-banner {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(139, 92, 246, 0.1) 100%);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 24px;
            padding: 60px;
            text-align: center;
            margin-top: 40px;
            position: relative;
            overflow: hidden;
        }

        .cta-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 50%, rgba(99, 102, 241, 0.08) 0%, transparent 50%);
            pointer-events: none;
        }

        .cta-banner h2 {
            font-family: var(--font-heading);
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 14px;
            position: relative;
        }

        .cta-banner p {
            color: var(--text-muted);
            font-size: 1.05rem;
            margin-bottom: 30px;
            position: relative;
        }

        .cta-banner .btn {
            position: relative;
            padding: 14px 36px;
            font-size: 1rem;
        }

        /* --- Footer --- */
        .landing-footer {
            text-align: center;
            padding: 40px 60px;
            border-top: 1px solid var(--border-glass);
            color: var(--text-dim);
            font-size: 0.85rem;
        }

        .landing-footer a {
            color: var(--primary);
            text-decoration: none;
        }

        /* --- Scroll Animation --- */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* --- Mobile Nav Toggle --- */
        .nav-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-main);
            cursor: pointer;
            padding: 8px;
        }

        /* --- Responsive --- */
        @media (max-width: 992px) {
            .landing-section { padding: 80px 30px 60px; }

            .hero-inner {
                grid-template-columns: 1fr;
                gap: 40px;
                text-align: center;
            }

            .hero-text h1 { font-size: 2.4rem; }

            .hero-text p {
                margin-left: auto;
                margin-right: auto;
            }

            .hero-actions { justify-content: center; }

            .hero-illustration {
                order: -1;
            }

            .hero-card {
                max-width: 380px;
            }

            .about-grid {
                grid-template-columns: 1fr;
            }

            .alur-timeline {
                flex-direction: column;
                gap: 40px;
                align-items: flex-start;
                padding-left: 50px;
            }

            .alur-timeline::before {
                width: 3px;
                height: calc(100% - 70px);
                top: 35px;
                left: 54px;
                right: auto;
            }

            .alur-step {
                flex-direction: row;
                text-align: left;
                gap: 20px;
            }

            .alur-num {
                width: 56px;
                height: 56px;
                flex-shrink: 0;
                margin-bottom: 0;
            }

            .nav-links {
                display: none;
                position: absolute;
                top: 72px;
                left: 0;
                right: 0;
                flex-direction: column;
                background: rgba(11, 15, 25, 0.95);
                backdrop-filter: blur(20px);
                padding: 20px;
                border-bottom: 1px solid var(--border-glass);
                gap: 4px;
            }

            .nav-links.open {
                display: flex;
            }

            .nav-links a {
                padding: 12px 20px;
                width: 100%;
            }

            .nav-toggle {
                display: block;
            }

            .cta-banner {
                padding: 40px 24px;
            }
        }

        @media (max-width: 600px) {
            .landing-nav { padding: 0 20px; }
            .landing-section { padding: 70px 20px 50px; }
            .hero-text h1 { font-size: 2rem; }
            .section-heading { font-size: 1.7rem; }
            .criteria-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <!-- ==================== NAVBAR ==================== -->
    <nav class="landing-nav" id="navbar">
        <a href="/" class="nav-logo">
            <i data-lucide="graduation-cap" style="color: var(--primary); width: 30px; height: 30px;"></i>
            <h2>SPK <span>KIP</span></h2>
        </a>

        <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
            <i data-lucide="menu" style="width: 24px; height: 24px;"></i>
        </button>

        <ul class="nav-links" id="navLinks">
            <li><a href="#beranda">Beranda</a></li>
            <li><a href="#tentang">Tentang</a></li>
            <li><a href="#kriteria">Kriteria</a></li>
            <li><a href="#alur">Alur</a></li>
            <li><a href="#faq">FAQ</a></li>
            <li><a href="{{ route('login') }}" class="nav-cta">Login</a></li>
        </ul>
    </nav>

    <!-- ==================== HERO ==================== -->
    <section class="landing-section hero" id="beranda">
        <div class="hero-inner">
            <div class="hero-text">
                <div class="section-tag">
                    <i data-lucide="sparkles" style="width: 14px; height: 14px;"></i>
                    Sistem Pendukung Keputusan
                </div>
                <h1>
                    Seleksi Penerima<br>
                    <span class="highlight">KIP Kuliah</span><br>
                    Transparan & Objektif
                </h1>
                <p>
                    Membantu proses seleksi secara akurat dan adil menggunakan metode <strong style="color: var(--text-main);">Analytical Hierarchy Process (AHP)</strong> dan <strong style="color: var(--text-main);">Simple Additive Weighting (SAW)</strong> untuk menentukan penerima beasiswa KIP Kuliah yang tepat sasaran.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('register') }}" class="btn btn-primary">
                        <i data-lucide="user-plus" style="width: 18px; height: 18px;"></i>
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-secondary">
                        <i data-lucide="search" style="width: 18px; height: 18px;"></i>
                        Cek Status
                    </a>
                </div>
            </div>

            <div class="hero-illustration">
                <div class="hero-card">
                    <div class="hero-card-header">
                        <div class="icon-box">
                            <i data-lucide="workflow" style="width: 24px; height: 24px;"></i>
                        </div>
                        <div>
                            <h3>Proses Seleksi AHP-SAW</h3>
                            <p>Multi-Criteria Decision Making</p>
                        </div>
                    </div>
                    <div class="hero-card-steps">
                        <div class="hero-card-step">
                            <div class="step-num">1</div>
                            <span>Penentuan kriteria & pembobotan AHP</span>
                        </div>
                        <div class="hero-card-step">
                            <div class="step-num">2</div>
                            <span>Input data pendaftar & nilai kriteria</span>
                        </div>
                        <div class="hero-card-step">
                            <div class="step-num">3</div>
                            <span>Normalisasi matriks keputusan SAW</span>
                        </div>
                        <div class="hero-card-step">
                            <div class="step-num">4</div>
                            <span>Perankingan & pengumuman hasil</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== TENTANG ==================== -->
    <section class="landing-section" id="tentang">
        <div class="reveal">
            <div class="section-tag">
                <i data-lucide="info" style="width: 14px; height: 14px;"></i>
                Tentang Sistem
            </div>
            <h2 class="section-heading">Apa Itu SPK KIP Kuliah?</h2>
            <p class="section-desc">
                Sistem ini dirancang untuk membantu proses seleksi penerima beasiswa KIP Kuliah secara transparan dan terukur berdasarkan berbagai kriteria akademik dan ekonomi yang telah ditetapkan.
            </p>
        </div>

        <div class="about-grid reveal">
            <div class="about-card">
                <div class="about-icon" style="background: var(--primary-glow); color: var(--primary);">
                    <i data-lucide="scale" style="width: 26px; height: 26px;"></i>
                </div>
                <h3>Metode AHP</h3>
                <p>Analytical Hierarchy Process digunakan untuk menentukan bobot setiap kriteria penilaian melalui perbandingan berpasangan yang konsisten.</p>
            </div>
            <div class="about-card">
                <div class="about-icon" style="background: var(--success-bg); color: var(--success);">
                    <i data-lucide="calculator" style="width: 26px; height: 26px;"></i>
                </div>
                <h3>Metode SAW</h3>
                <p>Simple Additive Weighting menormalisasi nilai pendaftar dan menghitung skor preferensi akhir untuk perankingan yang adil.</p>
            </div>
            <div class="about-card">
                <div class="about-icon" style="background: var(--warning-bg); color: var(--warning);">
                    <i data-lucide="shield-check" style="width: 26px; height: 26px;"></i>
                </div>
                <h3>Transparan & Objektif</h3>
                <p>Setiap langkah proses seleksi tercatat dan terukur, memastikan hasil yang adil tanpa unsur subjektivitas manusia.</p>
            </div>
        </div>
    </section>

    <!-- ==================== KRITERIA ==================== -->
    <section class="landing-section" id="kriteria">
        <div class="reveal">
            <div class="section-tag">
                <i data-lucide="list-checks" style="width: 14px; height: 14px;"></i>
                Kriteria Penilaian
            </div>
            <h2 class="section-heading">Parameter Seleksi Penerima</h2>
            <p class="section-desc">
                Setiap calon penerima KIP dinilai berdasarkan kriteria-kriteria berikut yang telah ditetapkan dan dibobotkan menggunakan metode AHP.
            </p>
        </div>

        <div class="criteria-grid reveal">
            @php
                $criteriaIcons = [
                    'C1' => 'wallet',
                    'C2' => 'users',
                    'C3' => 'trophy',
                    'C4' => 'home',
                    'C5' => 'map-pin',
                    'C6' => 'heart-pulse',
                    'C7' => 'book-open',
                    'C8' => 'briefcase',
                ];
                $criteriaDescriptions = [
                    'C1' => 'Besar pendapatan total kedua orang tua per bulan sebagai indikator kemampuan ekonomi.',
                    'C2' => 'Jumlah anggota keluarga yang masih menjadi tanggungan orang tua.',
                    'C3' => 'Capaian prestasi akademik siswa sebagai indikator potensi.',
                    'C4' => 'Kondisi fisik rumah tinggal sebagai indikator kesejahteraan.',
                    'C5' => 'Jarak tempat tinggal ke kampus sebagai faktor pertimbangan.',
                    'C6' => 'Kondisi kesehatan orang tua atau keluarga pendaftar.',
                    'C7' => 'Status pendidikan terakhir orang tua pendaftar.',
                    'C8' => 'Jenis pekerjaan dan status pekerjaan orang tua.',
                ];
            @endphp

            @foreach($criteria as $c)
                <div class="criteria-card">
                    <div class="criteria-icon">
                        <i data-lucide="{{ $criteriaIcons[$c->code] ?? 'file-text' }}" style="width: 28px; height: 28px;"></i>
                    </div>
                    <h3>{{ $c->name }}</h3>
                    <p>{{ $criteriaDescriptions[$c->code] ?? 'Kriteria penilaian untuk seleksi KIP Kuliah.' }}</p>
                    <span class="criteria-type {{ $c->type === 'benefit' ? 'type-benefit' : 'type-cost' }}">
                        {{ $c->type === 'benefit' ? '▲ Benefit' : '▼ Cost' }}
                    </span>
                </div>
            @endforeach
        </div>
    </section>

    <!-- ==================== ALUR PENDAFTARAN ==================== -->
    <section class="landing-section" id="alur">
        <div class="reveal">
            <div class="section-tag">
                <i data-lucide="route" style="width: 14px; height: 14px;"></i>
                Alur Pendaftaran
            </div>
            <h2 class="section-heading">Langkah-Langkah Seleksi</h2>
            <p class="section-desc">
                Proses seleksi KIP Kuliah berjalan secara sistematis dari pendaftaran hingga pengumuman hasil.
            </p>
        </div>

        <div class="alur-timeline reveal">
            <div class="alur-step">
                <div class="alur-num">
                    <i data-lucide="user-plus" style="width: 28px; height: 28px;"></i>
                </div>
                <div>
                    <h4>Registrasi Akun</h4>
                    <p>Buat akun melalui halaman pendaftaran sistem</p>
                </div>
            </div>
            <div class="alur-step">
                <div class="alur-num">
                    <i data-lucide="file-edit" style="width: 28px; height: 28px;"></i>
                </div>
                <div>
                    <h4>Lengkapi Data</h4>
                    <p>Isi formulir dan upload berkas persyaratan</p>
                </div>
            </div>
            <div class="alur-step">
                <div class="alur-num">
                    <i data-lucide="scan-search" style="width: 28px; height: 28px;"></i>
                </div>
                <div>
                    <h4>Verifikasi</h4>
                    <p>Admin memverifikasi data dan melakukan penilaian</p>
                </div>
            </div>
            <div class="alur-step">
                <div class="alur-num">
                    <i data-lucide="award" style="width: 28px; height: 28px;"></i>
                </div>
                <div>
                    <h4>Pengumuman</h4>
                    <p>Hasil seleksi diumumkan melalui dashboard</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== FAQ ==================== -->
    <section class="landing-section" id="faq">
        <div class="reveal">
            <div class="section-tag">
                <i data-lucide="help-circle" style="width: 14px; height: 14px;"></i>
                Frequently Asked Questions
            </div>
            <h2 class="section-heading">Pertanyaan Umum</h2>
            <p class="section-desc">
                Temukan jawaban dari pertanyaan yang sering diajukan tentang sistem seleksi KIP Kuliah.
            </p>
        </div>

        <div class="faq-list reveal">
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <span>Apa itu KIP Kuliah?</span>
                    <i data-lucide="chevron-down" style="width: 20px; height: 20px;"></i>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        KIP Kuliah (Kartu Indonesia Pintar Kuliah) adalah bantuan biaya pendidikan dari pemerintah bagi lulusan SMA/SMK/sederajat yang memiliki potensi akademik baik namun memiliki keterbatasan ekonomi untuk membiayai pendidikan di perguruan tinggi.
                    </div>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <span>Bagaimana cara mendaftar KIP Kuliah melalui sistem ini?</span>
                    <i data-lucide="chevron-down" style="width: 20px; height: 20px;"></i>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        Anda cukup membuat akun melalui halaman registrasi, lalu login dan lengkapi formulir pengajuan KIP pada menu "Pengajuan KIP" di dashboard. Isi semua data yang diperlukan sesuai dengan kondisi sebenarnya.
                    </div>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <span>Apa itu metode AHP dan SAW?</span>
                    <i data-lucide="chevron-down" style="width: 20px; height: 20px;"></i>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        AHP (Analytical Hierarchy Process) digunakan untuk menentukan bobot kepentingan masing-masing kriteria. SAW (Simple Additive Weighting) kemudian digunakan untuk menormalisasi dan menghitung skor akhir setiap pendaftar guna menentukan perankingan.
                    </div>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <span>Kapan hasil seleksi diumumkan?</span>
                    <i data-lucide="chevron-down" style="width: 20px; height: 20px;"></i>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        Hasil seleksi akan diumumkan setelah admin selesai melakukan proses perankingan dan mengunci hasil melalui sistem. Anda dapat memeriksa status kelulusan pada halaman Dashboard setelah login.
                    </div>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <span>Apakah proses seleksi bisa dijamin adil?</span>
                    <i data-lucide="chevron-down" style="width: 20px; height: 20px;"></i>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        Ya. Dengan menggunakan metode AHP-SAW, seluruh proses keputusan didasarkan pada perhitungan matematis yang terukur dan transparan. Konsistensi penilaian juga divalidasi melalui Consistency Ratio (CR ≤ 0.10).
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================== CTA ==================== -->
    <section class="landing-section">
        <div class="cta-banner reveal">
            <h2>Siap Mendaftar KIP Kuliah?</h2>
            <p>Daftarkan diri Anda sekarang dan raih kesempatan mendapatkan bantuan biaya pendidikan.</p>
            <a href="{{ route('register') }}" class="btn btn-primary">
                <i data-lucide="rocket" style="width: 18px; height: 18px;"></i>
                Mulai Pendaftaran
            </a>
        </div>
    </section>

    <!-- ==================== FOOTER ==================== -->
    <footer class="landing-footer">
        <p>&copy; {{ date('Y') }} SPK KIP Kuliah — Sistem Pendukung Keputusan dengan metode AHP & SAW.</p>
    </footer>

    <!-- ==================== SCRIPTS ==================== -->
    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        });

        // Mobile nav toggle
        const navToggle = document.getElementById('navToggle');
        const navLinks = document.getElementById('navLinks');
        navToggle.addEventListener('click', () => {
            navLinks.classList.toggle('open');
        });

        // Close mobile nav on link click
        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('open');
            });
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // FAQ Toggle
        function toggleFaq(btn) {
            const item = btn.parentElement;
            const wasOpen = item.classList.contains('open');
            // Close all
            document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
            // Toggle current
            if (!wasOpen) item.classList.add('open');
            // Re-init icons
            lucide.createIcons();
        }

        // Scroll reveal animation
        const revealElements = document.querySelectorAll('.reveal');
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.15 });

        revealElements.forEach(el => revealObserver.observe(el));
    </script>

</body>
</html>

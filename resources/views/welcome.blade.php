<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SINFAS - Sistem Informasi Fasilitas & Sarana Prasarana</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #1e40af;
            --primary-dark: #1e3a8a;
            --primary-light: #3b82f6;
            --accent: #2563eb;
            --accent-glow: rgba(37, 99, 235, 0.35);
            --bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #1e40af 100%);
            --surface-white: #ffffff;
            --surface-soft: #f8fafc;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
            scroll-behavior: smooth;
        }

        body {
            background-color: #f8fafc;
            color: var(--text-dark);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            background: rgba(15, 23, 42, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .nav-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            text-decoration: none;
        }

        .logo-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 50%;
            border: 2px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            font-weight: 900;
            color: white;
            letter-spacing: -0.5px;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
        }

        .brand-text h1 {
            font-family: 'Outfit', sans-serif;
            color: white;
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1;
        }

        .brand-text span {
            color: #93c5fd;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
            margin-top: 2px;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 2rem;
            list-style: none;
        }

        .nav-menu a {
            color: #cbd5e1;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .nav-menu a:hover {
            color: #ffffff;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-nav-ghost {
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 0.6rem 1.4rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-nav-ghost:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
        }

        .btn-nav-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            padding: 0.65rem 1.6rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.9rem;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.35);
            transition: all 0.2s ease;
        }

        .btn-nav-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.5);
        }

        /* ===== HERO SECTION ===== */
        .hero {
            position: relative;
            background: var(--bg-gradient);
            padding: 9rem 2rem 6rem;
            overflow: hidden;
            color: white;
        }

        .hero-bg-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .shape-circle-1 {
            position: absolute;
            top: -10%;
            right: -5%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.25) 0%, rgba(0, 0, 0, 0) 70%);
            border-radius: 50%;
        }

        .shape-circle-2 {
            position: absolute;
            bottom: -20%;
            left: -10%;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, rgba(0, 0, 0, 0) 70%);
            border-radius: 50%;
        }

        .hero-container {
            max-width: 1280px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 4rem;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.4rem 1.1rem;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #93c5fd;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(10px);
        }

        .hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 3.2rem;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 1.5rem;
            letter-spacing: -1px;
        }

        .hero-title span {
            background: linear-gradient(135deg, #60a5fa, #93c5fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.15rem;
            color: #cbd5e1;
            margin-bottom: 2.2rem;
            max-width: 580px;
            font-weight: 400;
        }

        .hero-cta {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            padding: 1rem 2.2rem;
            border-radius: 14px;
            font-weight: 700;
            font-size: 1.05rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
            transition: all 0.25s ease;
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(37, 99, 235, 0.6);
        }

        .btn-hero-secondary {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: white;
            padding: 1rem 2rem;
            border-radius: 14px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            backdrop-filter: blur(10px);
            transition: all 0.25s ease;
        }

        .btn-hero-secondary:hover {
            background: rgba(255, 255, 255, 0.18);
            border-color: white;
        }

        .hero-trust {
            display: flex;
            align-items: center;
            gap: 1.8rem;
            color: #94a3b8;
            font-size: 0.88rem;
            font-weight: 600;
        }

        .hero-trust-item {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        /* Hero Preview Card Visual */
        .hero-visual {
            position: relative;
        }

        .hero-card-main {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 2rem;
            padding-bottom: 2.8rem;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            color: var(--text-dark);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transform: perspective(1000px) rotateY(-5deg) rotateX(2deg);
            transition: transform 0.5s ease;
        }

        .hero-card-main:hover {
            transform: perspective(1000px) rotateY(0deg) rotateX(0deg);
        }

        .card-header-mock {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .mock-user-info {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .mock-avatar {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #eff6ff;
            color: #1e40af;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .mock-badge-status {
            background: #dcfce7;
            color: #15803d;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
        }

        .mock-item-preview {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.2rem;
        }

        .mock-item-icon {
            font-size: 2.2rem;
            background: white;
            width: 55px;
            height: 55px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .mock-floating-badge {
            position: absolute;
            bottom: -30px;
            right: -20px;
            background: white;
            color: var(--text-dark);
            padding: 0.85rem 1.4rem;
            border-radius: 18px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.18);
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-weight: 700;
            font-size: 0.88rem;
            border: 1px solid #e2e8f0;
            animation: floatAnim 3s ease-in-out infinite alternate;
        }

        @keyframes floatAnim {
            0% { transform: translateY(0); }
            100% { transform: translateY(-10px); }
        }

        /* ===== STATS BAR ===== */
        .stats-bar {
            background: white;
            margin-top: -3rem;
            position: relative;
            z-index: 10;
            max-width: 1100px;
            margin-left: auto;
            margin-right: auto;
            border-radius: 24px;
            padding: 2rem 3rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            border: 1px solid #e2e8f0;
        }

        .stat-box {
            text-align: center;
        }

        .stat-number {
            font-family: 'Outfit', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            color: #1e40af;
            line-height: 1;
            margin-bottom: 0.3rem;
        }

        .stat-desc {
            font-size: 0.88rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        /* ===== SECTION COMMON ===== */
        .section-padding {
            padding: 6rem 2rem;
        }

        .section-header {
            text-align: center;
            max-width: 650px;
            margin: 0 auto 4rem;
        }

        .section-tag {
            color: #2563eb;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
            display: block;
        }

        .section-title {
            font-family: 'Outfit', sans-serif;
            font-size: 2.3rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 1rem;
            line-height: 1.25;
        }

        .section-subtitle {
            color: var(--text-muted);
            font-size: 1.05rem;
        }

        /* ===== FEATURES SECTION ===== */
        .features-grid {
            max-width: 1280px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 35px rgba(37, 99, 235, 0.12);
            border-color: #bfdbfe;
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            background: #eff6ff;
            color: #2563eb;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 1.4rem;
        }

        .feature-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.6rem;
        }

        .feature-text {
            color: var(--text-muted);
            font-size: 0.92rem;
            line-height: 1.55;
        }

        /* ===== WORKFLOW SECTION ===== */
        .workflow-section {
            background: #f1f5f9;
        }

        .workflow-grid {
            max-width: 1280px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.8rem;
            position: relative;
        }

        .workflow-card {
            background: white;
            border-radius: 20px;
            padding: 2.2rem 1.5rem 1.8rem;
            text-align: center;
            border: 1px solid #e2e8f0;
            position: relative;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        }

        .step-num {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.9rem;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
        }

        .workflow-icon {
            font-size: 2.4rem;
            margin-top: 0.5rem;
            margin-bottom: 1rem;
        }

        .workflow-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }

        .workflow-desc {
            font-size: 0.88rem;
            color: var(--text-muted);
        }

        /* ===== FACILITIES PREVIEW GRID ===== */
        .facilities-grid {
            max-width: 1280px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .facility-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .facility-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
        }

        .facility-img {
            height: 170px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .facility-body {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .facility-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.3rem;
        }

        .facility-category {
            font-size: 0.8rem;
            color: #2563eb;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .facility-footer {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .facility-stok {
            font-size: 0.85rem;
            color: #16a34a;
            font-weight: 700;
        }

        .btn-pinjam-sm {
            background: #1e40af;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-pinjam-sm:hover {
            background: #1e3a8a;
        }

        /* ===== FAQ ACCORDION ===== */
        .faq-container {
            max-width: 800px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .faq-item {
            background: white;
            border-radius: 16px;
            padding: 1.4rem 1.8rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        }

        .faq-question {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }

        .faq-answer {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        /* ===== CTA BANNER ===== */
        .cta-banner {
            max-width: 1280px;
            margin: 0 auto 6rem;
            background: linear-gradient(135deg, #1e1b4b 0%, #1e40af 100%);
            border-radius: 28px;
            padding: 4rem 3rem;
            text-align: center;
            color: white;
            box-shadow: 0 20px 50px rgba(30, 64, 175, 0.3);
            position: relative;
            overflow: hidden;
        }

        .cta-title {
            font-family: 'Outfit', sans-serif;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .cta-desc {
            font-size: 1.1rem;
            color: #cbd5e1;
            max-width: 600px;
            margin: 0 auto 2.2rem;
        }

        /* ===== FOOTER ===== */
        .footer {
            background: #0f172a;
            color: #94a3b8;
            padding: 4rem 2rem 2rem;
            border-top: 1px solid #1e293b;
        }

        .footer-container {
            max-width: 1280px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-brand h2 {
            font-family: 'Outfit', sans-serif;
            color: white;
            font-size: 1.5rem;
            margin-bottom: 0.8rem;
        }

        .footer-brand p {
            font-size: 0.9rem;
            line-height: 1.6;
            max-width: 320px;
        }

        .footer-col h4 {
            color: white;
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 1.2rem;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.7rem;
        }

        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .footer-links a:hover {
            color: white;
        }

        .footer-bottom {
            max-width: 1280px;
            margin: 0 auto;
            padding-top: 2rem;
            border-top: 1px solid #1e293b;
            text-align: center;
            font-size: 0.85rem;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .hero-container { grid-template-columns: 1fr; text-align: center; }
            .hero-subtitle { margin-left: auto; margin-right: auto; }
            .hero-cta { justify-content: center; }
            .hero-trust { justify-content: center; }
            .hero-visual { display: none; }
            .stats-bar { grid-template-columns: repeat(2, 1fr); margin-top: 2rem; }
            .features-grid, .workflow-grid { grid-template-columns: repeat(2, 1fr); }
            .facilities-grid { grid-template-columns: repeat(2, 1fr); }
            .footer-container { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 640px) {
            .nav-menu { display: none; }
            .hero-title { font-size: 2.2rem; }
            .stats-bar { grid-template-columns: 1fr; }
            .features-grid, .workflow-grid, .facilities-grid { grid-template-columns: 1fr; }
            .footer-container { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('landing') }}" class="brand-logo">
                <div class="logo-icon">SF</div>
                <div class="brand-text">
                    <h1>SINFAS</h1>
                    <span>Sistem Informasi Fasilitas</span>
                </div>
            </a>

            <ul class="nav-menu">
                <li><a href="#beranda">Beranda</a></li>
                <li><a href="#fitur">Keunggulan</a></li>
                <li><a href="#alur">Cara Kerja</a></li>
                <li><a href="#fasilitas">Katalog Fasilitas</a></li>
                <li><a href="#faq">FAQ</a></li>
            </ul>

            <div class="nav-actions">
                @auth
                    @php
                        $user = auth()->user();
                        $dashboardRoute = route('user.dashboard');
                        if ($user->role === 'admin_sistem') {
                            $dashboardRoute = route('system-admin.dashboard');
                        } elseif ($user->role === 'admin_sarana') {
                            $dashboardRoute = route('admin.dashboard');
                        }
                    @endphp
                    <a href="{{ $dashboardRoute }}" class="btn-nav-primary">
                        Ke Dashboard →
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-nav-ghost">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-nav-primary">Daftar Akun</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero" id="beranda">
        <div class="hero-bg-shapes">
            <div class="shape-circle-1"></div>
            <div class="shape-circle-2"></div>
        </div>

        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">
                    Sistem Pengelolaan Fasilitas & Sarana Prasarana Sekolah
                </div>

                <h1 class="hero-title">
                    Pinjam Sarana Sekolah Jadi Lebih <span>Mudah, Cepat & Transparan</span>
                </h1>

                <p class="hero-subtitle">
                    SINFAS adalah platform terpadu untuk mempermudah Siswa dan Guru dalam mengajukan peminjaman fasilitas sekolah secara online dengan verifikasi real-time oleh Admin Sarana.
                </p>

                <div class="hero-cta">
                    @auth
                        <a href="{{ $dashboardRoute }}" class="btn-hero-primary">
                            Masuk Ke Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-hero-primary">
                            Ajukan Peminjaman Sekarang
                        </a>
                    @endauth
                    <a href="#alur" class="btn-hero-secondary">
                        📖 Lihat Cara Kerja
                    </a>
                </div>

                <div class="hero-trust">
                    <div class="hero-trust-item">
                        <span style="color:#22c55e;">✓</span> 100% Bebas Antrean
                    </div>
                    <div class="hero-trust-item">
                        <span style="color:#22c55e;">✓</span> Verifikasi Cepat
                    </div>
                    <div class="hero-trust-item">
                        <span style="color:#22c55e;">✓</span> Riwayat Transparan
                    </div>
                </div>
            </div>

            <!-- HERO VISUAL MOCKUP -->
            <div class="hero-visual">
                <div class="hero-card-main">
                    <div class="card-header-mock">
                        <div class="mock-user-info">
                            <div class="mock-avatar">📋</div>
                            <div>
                                <strong style="font-size:0.95rem; display:block;">Peminjaman Proyektor HD</strong>
                                <small style="color:#64748b;">Diajukan oleh: Siswa / Guru</small>
                            </div>
                        </div>
                        <span class="mock-badge-status">🟢 Disetujui Admin</span>
                    </div>

                    <div class="mock-item-preview">
                        <div class="mock-item-icon">📹</div>
                        <div>
                            <strong style="font-size:1rem; color:#0f172a;">Proyektor Epson EB-X05</strong>
                            <p style="font-size:0.85rem; color:#64748b;">Kode: BRG001 • Kondisi Baik</p>
                        </div>
                    </div>

                    <div style="display:flex; justify-content:space-between; font-size:0.85rem; color:#475569; background:#f1f5f9; padding:0.8rem 1rem; border-radius:12px;">
                        <span>📅 Status: <strong>Sedang Dipinjam</strong></span>
                        <span style="color:#2563eb; font-weight:700;">Verifikasi Real-time</span>
                    </div>
                </div>

                <div class="mock-floating-badge">
                    <span style="font-size:1.4rem;">⚡</span>
                    <div>
                        <span style="display:block; line-height:1.2;">Stok Sarana Selalu Up-To-Date</span>
                        <small style="color:#64748b; font-weight:500;">Terverifikasi Admin Sarana</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- STATS COUNTER BAR -->
    <div class="stats-bar">
        <div class="stat-box">
            <div class="stat-number">{{ $totalBarang ?? 0 }}+</div>
            <div class="stat-desc">Unit Sarana & Fasilitas</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $totalKategori ?? 0 }}+</div>
            <div class="stat-desc">Kategori Fasilitas Sekolah</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $totalDipinjam ?? 0 }}+</div>
            <div class="stat-desc">Peminjaman Aktif</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">100%</div>
            <div class="stat-desc">Online & Terintegrasi</div>
        </div>
    </div>

    <!-- FEATURES SECTION -->
    <section class="section-padding" id="fitur">
        <div class="section-header">
            <span class="section-tag">Keunggulan Utama</span>
            <h2 class="section-title">Mengapa Harus Menggunakan SINFAS?</h2>
            <p class="section-subtitle">SINFAS memberikan solusi pengelolaan sarana prasarana modern untuk kenyamanan kegiatan belajar mengajar.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3 class="feature-title">Pengajuan Online Cepat</h3>
                <p class="feature-text">Tidak perlu lagi mengisi formulir kertas secara manual. Cukup pilih sarana dan ajukan peminjaman dalam hitungan detik.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🔍</div>
                <h3 class="feature-title">Pencarian & Katalog Lengkap</h3>
                <p class="feature-text">Katalog sarana dikelompokkan dengan rapi berdasarkan kategori, memudahkan pencarian barang yang dibutuhkan.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🛡️</div>
                <h3 class="feature-title">Verifikasi Admin Real-Time</h3>
                <p class="feature-text">Admin Sarana memverifikasi persetujuan peminjaman dan kondisi barang (baik/rusak) secara akurat dan tepat waktu.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3 class="feature-title">Riwayat & Laporan Siap Cetak</h3>
                <p class="feature-text">Seluruh data transaksi peminjaman dan pengembalian terdata secara otomatis dan siap dicetak sebagai laporan resmi.</p>
            </div>
        </div>
    </section>

    <!-- WORKFLOW SECTION -->
    <section class="section-padding workflow-section" id="alur">
        <div class="section-header">
            <span class="section-tag">Alur Penggunaan</span>
            <h2 class="section-title">4 Langkah Mudah Meminjam Sarana</h2>
            <p class="section-subtitle">Proses simpel yang dapat diikuti oleh Siswa dan Guru dalam mengajukan peminjaman fasilitas.</p>
        </div>

        <div class="workflow-grid">
            <div class="workflow-card">
                <div class="step-num">1</div>
                <div class="workflow-icon">🔎</div>
                <h3 class="workflow-title">Pilih Fasilitas</h3>
                <p class="workflow-desc">Cari dan temukan barang yang ingin dipinjam pada katalog fasilitas SINFAS.</p>
            </div>

            <div class="workflow-card">
                <div class="step-num">2</div>
                <div class="workflow-icon">📝</div>
                <h3 class="workflow-title">Isi Form Pinjam</h3>
                <p class="workflow-desc">Tentukan tanggal peminjaman, tanggal pengembalian, dan sertakan alasan keperluan.</p>
            </div>

            <div class="workflow-card">
                <div class="step-num">3</div>
                <div class="workflow-icon">⚡</div>
                <h3 class="workflow-title">Verifikasi Admin</h3>
                <p class="workflow-desc">Admin Sarana akan meninjau dan menyetujui pengajuan peminjaman Anda.</p>
            </div>

            <div class="workflow-card">
                <div class="step-num">4</div>
                <div class="workflow-icon">📦</div>
                <h3 class="workflow-title">Ambil & Kembalikan</h3>
                <p class="workflow-desc">Ambil sarana di ruang prasarana dan kembalikan sesuai jadwal yang disepakati.</p>
            </div>
        </div>
    </section>

    <!-- FEATURED FACILITIES SECTION -->
    <section class="section-padding" id="fasilitas">
        <div class="section-header">
            <span class="section-tag">Katalog Sarana</span>
            <h2 class="section-title">Fasilitas & Sarana Terbaru</h2>
            <p class="section-subtitle">Daftar sarana prasarana sekolah yang siap dipinjam untuk menunjang kegiatan Anda.</p>
        </div>

        <div class="facilities-grid">
            @forelse($featuredBarang as $barang)
                <div class="facility-card">
                    <div class="facility-img">
                        @if($barang->gambar)
                            <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" style="max-width:100%; max-height:100%; object-fit:contain;">
                        @else
                            📦
                        @endif
                    </div>
                    <div class="facility-body">
                        <span class="facility-category">📁 {{ $barang->kategori->nama_kategori ?? 'Umum' }}</span>
                        <h3 class="facility-title">{{ $barang->nama_barang }}</h3>
                        <p style="font-size:0.85rem; color:#64748b;">Kode: {{ $barang->kode_barang }} • {{ $barang->merk_model ?? '-' }}</p>

                        <div class="facility-footer">
                            <span class="facility-stok">✓ Tersedia {{ $barang->jumlah_baik }} unit</span>
                            @auth
                                <a href="{{ route('user.peminjaman.create', $barang->kode_barang) }}" class="btn-pinjam-sm">Pinjam</a>
                            @else
                                <a href="{{ route('login') }}" class="btn-pinjam-sm">Login untuk Pinjam</a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align:center; padding:3rem; background:white; border-radius:20px; color:#64748b;">
                    <h3>Belum Ada Fasilitas Ditampilkan</h3>
                    <p>Fasilitas akan muncul setelah Admin Sarana menginputkan data sarana.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- FAQ SECTION -->
    <section class="section-padding" style="background:#f8fafc;" id="faq">
        <div class="section-header">
            <span class="section-tag">Pertanyaan Umum</span>
            <h2 class="section-title">Sering Ditanyakan (FAQ)</h2>
            <p class="section-subtitle">Informasi seputar penggunaan sistem informasi fasilitas sekolah.</p>
        </div>

        <div class="faq-container">
            <div class="faq-item">
                <h3 class="faq-question">❓ Siapa saja yang bisa meminjam fasilitas melalui SINFAS?</h3>
                <p class="faq-answer">Seluruh Siswa dan Guru yang telah memiliki akun terdaftar dengan NIS/NIP di sekolah dapat meminjam sarana prasarana yang tersedia.</p>
            </div>

            <div class="faq-item">
                <h3 class="faq-question">❓ Berapa lama proses verifikasi peminjaman oleh Admin Sarana?</h3>
                <p class="faq-answer">Verifikasi dilakukan secara real-time. Admin Sarana akan menerima notifikasi pengajuan dan menyetujuinya setelah melakukan pengecekan stok fisik.</p>
            </div>

            <div class="faq-item">
                <h3 class="faq-question">❓ Bagaimana jika barang terlambat dikembalikan?</h3>
                <p class="faq-answer">Sistem SINFAS akan secara otomatis menandai status peminjaman sebagai "Terlambat" dan menampilkan peringatan pada dashboard Admin Sarana.</p>
            </div>

            <div class="faq-item">
                <h3 class="faq-question">❓ Apa yang harus dilakukan jika terjadi kendala pada barang yang dipinjam?</h3>
                <p class="faq-answer">Pengguna wajib melaporkan kondisi barang saat mengajukan pengembalian agar Admin Sarana dapat mencatat kondisi fisik barang (baik/rusak ringan/rusak berat).</p>
            </div>
        </div>
    </section>

    <!-- CTA BANNER -->
    <div style="padding: 0 2rem;">
        <div class="cta-banner">
            <h2 class="cta-title">Siap Meminjam Fasilitas Sekolah?</h2>
            <p class="cta-desc">Dapatkan kemudahan akses peminjaman proyektor, audio, laboratorium, dan sarana lainnya dalam satu genggaman.</p>
            @auth
                <a href="{{ $dashboardRoute }}" class="btn-hero-primary" style="background:white; color:#1e40af;">
                    Masuk Ke Dashboard Anda
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-hero-primary" style="background:white; color:#1e40af;">
                    Masuk & Ajukan Peminjaman
                </a>
            @endauth
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-brand">
                <div style="display:flex; align-items:center; gap:0.8rem; margin-bottom:1rem;">
                    <div style="width:40px; height:40px; background:linear-gradient(135deg, #2563eb, #1d4ed8); border-radius:50%; border:2px solid white; display:flex; align-items:center; justify-content:center; color:white; font-weight:900; font-size:1rem;">SF</div>
                    <h2 style="margin:0; font-family:'Outfit', sans-serif; color:white;">SINFAS</h2>
                </div>
                <p>Sistem Informasi Fasilitas & Sarana Prasarana Sekolah. Solusi digital terpadu pengelolaan sarana prasarana sekolah yang efisien, transparan, dan terpercaya.</p>
            </div>

            <div class="footer-col">
                <h4>Navigasi</h4>
                <ul class="footer-links">
                    <li><a href="#beranda">Beranda</a></li>
                    <li><a href="#fitur">Keunggulan</a></li>
                    <li><a href="#alur">Alur Kerja</a></li>
                    <li><a href="#fasilitas">Katalog Facilities</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Akses Akun</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('login') }}">Login Siswa / Guru</a></li>
                    <li><a href="{{ route('register') }}">Daftar Akun Baru</a></li>
                    <li><a href="{{ route('login') }}">Login Admin Sarana</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Bantuan & Kontak</h4>
                <ul class="footer-links">
                    <li><a href="#faq">FAQ</a></li>
                    <li><a href="#">Ruang Prasarana Sekolah</a></li>
                    <li><a href="#">Jam Operasional: 07:00 - 16:00</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} SINFAS - Sistem Informasi Fasilitas. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>

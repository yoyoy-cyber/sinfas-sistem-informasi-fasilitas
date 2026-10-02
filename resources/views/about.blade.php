<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang SINFAS - Sistem Informasi Fasilitas</title>
    <meta name="description" content="SINFAS adalah Sistem Informasi Fasilitas digital untuk pengelolaan peminjaman sarana dan prasarana sekolah secara efisien dan transparan.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --navy: #020146;
            --navy-2: #030164;
        }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: #030164;
            color: #1e293b;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* NAVBAR */
        .navbar {
            background: #020146;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 24px rgba(0,0,0,0.25);
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .navbar-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            text-decoration: none;
            color: white;
        }
        .brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 0.8rem;
            color: white;
            box-shadow: 0 4px 12px rgba(59,130,246,0.4);
            flex-shrink: 0;
        }
        .brand-text h1 { font-size: 1.2rem; font-weight: 800; letter-spacing: 0.5px; color: white; line-height: 1.1; }
        .brand-text p { font-size: 0.62rem; opacity: 0.65; color: white; }
        .navbar-actions { display: flex; align-items: center; gap: 0.75rem; }
        .btn-nav {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.2rem;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }
        .btn-nav-ghost { color: rgba(255,255,255,0.8); background: rgba(255,255,255,0.08); }
        .btn-nav-ghost:hover { background: rgba(255,255,255,0.15); color: white; }
        .btn-nav-primary { background: var(--primary); color: white; }
        .btn-nav-primary:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,0.4); }

        /* HERO */
        .hero {
            position: relative;
            min-height: 520px;
            display: flex;
            align-items: center;
            overflow: hidden;
            background: linear-gradient(135deg, #020146 0%, #030164 50%, #0a0a8f 100%);
        }
        .hero-bg-orbs { position: absolute; inset: 0; pointer-events: none; overflow: hidden; }
        .orb { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.25; }
        .orb-1 { width: 500px; height: 500px; background: #3b82f6; top: -150px; right: -100px; animation: floatOrb 8s ease-in-out infinite; }
        .orb-2 { width: 350px; height: 350px; background: #8b5cf6; bottom: -100px; left: -50px; animation: floatOrb 10s ease-in-out infinite reverse; }
        .orb-3 { width: 250px; height: 250px; background: #06b6d4; top: 50%; left: 45%; animation: floatOrb 6s ease-in-out infinite 2s; }
        @keyframes floatOrb { 0%,100% { transform: translate(0,0) scale(1); } 50% { transform: translate(20px,-30px) scale(1.05); } }
        .hero-content { position: relative; z-index: 1; max-width: 1200px; margin: 0 auto; padding: 5rem 2rem; text-align: center; }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: rgba(59,130,246,0.2); border: 1px solid rgba(59,130,246,0.4);
            color: #93c5fd; padding: 0.4rem 1rem; border-radius: 100px;
            font-size: 0.8rem; font-weight: 600; margin-bottom: 1.5rem; letter-spacing: 0.5px;
        }
        .hero-badge-dot { width: 7px; height: 7px; background: #60a5fa; border-radius: 50%; animation: pulse 2s ease-in-out infinite; }
        @keyframes pulse { 0%,100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.5; transform: scale(0.8); } }
        .hero-logo {
            display: inline-flex; align-items: center; justify-content: center;
            width: 96px; height: 96px;
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
            border-radius: 24px; font-size: 2rem; font-weight: 900; color: white;
            margin: 0 auto 1.5rem;
            box-shadow: 0 20px 50px rgba(59,130,246,0.4);
            animation: heroLogoFloat 4s ease-in-out infinite;
        }
        @keyframes heroLogoFloat { 0%,100% { transform: translateY(0) rotate(0deg); } 25% { transform: translateY(-8px) rotate(2deg); } 75% { transform: translateY(-4px) rotate(-2deg); } }
        .hero-title { font-size: clamp(2.5rem, 6vw, 4rem); font-weight: 900; color: white; line-height: 1.1; margin-bottom: 0.5rem; letter-spacing: -1px; }
        .hero-title span { background: linear-gradient(135deg, #60a5fa, #a78bfa); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; }
        .hero-subtitle { font-size: 1.05rem; color: rgba(255,255,255,0.6); margin-bottom: 2rem; font-style: italic; }
        .hero-desc { font-size: 1.1rem; color: rgba(255,255,255,0.75); max-width: 620px; margin: 0 auto 2.5rem; line-height: 1.7; }
        .hero-cta { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
        .btn-hero {
            display: inline-flex; align-items: center; gap: 0.6rem;
            padding: 0.85rem 2rem; border-radius: 14px;
            font-size: 1rem; font-weight: 700; text-decoration: none; transition: all 0.25s;
            cursor: pointer; border: none;
        }
        .btn-hero-primary { background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; box-shadow: 0 8px 25px rgba(59,130,246,0.4); }
        .btn-hero-primary:hover { transform: translateY(-3px); box-shadow: 0 12px 35px rgba(59,130,246,0.55); }
        .btn-hero-outline { background: rgba(255,255,255,0.08); color: white; border: 1.5px solid rgba(255,255,255,0.2); backdrop-filter: blur(10px); }
        .btn-hero-outline:hover { background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.4); transform: translateY(-3px); }

        /* STATS STRIP */
        .stats-strip { background: white; padding: 1.5rem 2rem; box-shadow: 0 4px 24px rgba(0,0,0,0.1); }
        .stats-strip-inner { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.5rem; }
        .stat-item { text-align: center; padding: 1rem; }
        .stat-number { font-size: 2.2rem; line-height: 1; margin-bottom: 0.3rem; }
        .stat-label { font-size: 0.82rem; color: #64748b; font-weight: 500; }

        /* SECTIONS */
        .section { padding: 5rem 2rem; }
        .section-inner { max-width: 1200px; margin: 0 auto; }
        .section-tag { display: inline-block; background: rgba(59,130,246,0.15); color: #60a5fa; padding: 0.3rem 0.9rem; border-radius: 100px; font-size: 0.78rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 0.75rem; }
        .section-title { font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 800; color: white; line-height: 1.2; margin-bottom: 0.8rem; }
        .section-title span { background: linear-gradient(135deg, #60a5fa, #a78bfa); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; }
        .section-desc { font-size: 1rem; color: rgba(255,255,255,0.65); max-width: 560px; line-height: 1.7; }

        /* ABOUT */
        .about-section { background: linear-gradient(180deg, #030164 0%, #020146 100%); }
        .about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; }
        .about-card-main {
            background: linear-gradient(135deg, rgba(255,255,255,0.08), rgba(255,255,255,0.03));
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 24px; padding: 2.5rem;
            backdrop-filter: blur(20px); position: relative; overflow: hidden;
        }
        .about-card-main::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #3b82f6, #8b5cf6, #06b6d4); }
        .about-icon-row { display: flex; gap: 1rem; margin-bottom: 1.5rem; }
        .about-icon-box { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; }
        .about-card-title { font-size: 1.3rem; font-weight: 700; color: white; margin-bottom: 0.5rem; }
        .about-card-text { font-size: 0.9rem; color: rgba(255,255,255,0.65); line-height: 1.6; }
        .floating-badge {
            position: absolute; color: white; padding: 0.6rem 1rem;
            border-radius: 12px; font-size: 0.8rem; font-weight: 700;
            display: flex; align-items: center; gap: 0.4rem;
            animation: floatBadge 3s ease-in-out infinite;
        }
        @keyframes floatBadge { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
        .fb-1 { bottom: -18px; right: 24px; background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 8px 20px rgba(16,185,129,0.35); }
        .fb-2 { top: -16px; right: 60px; background: linear-gradient(135deg, #f59e0b, #d97706); box-shadow: 0 8px 20px rgba(245,158,11,0.35); animation-delay: 1s; }

        /* FEATURES */
        .features-section { background: #020146; }
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-top: 3rem; }
        .feature-card {
            background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px; padding: 2rem; transition: all 0.3s; position: relative; overflow: hidden;
        }
        .feature-card::before { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, var(--card-color, #3b82f6), transparent); opacity: 0; transition: opacity 0.3s; }
        .feature-card:hover { transform: translateY(-6px); border-color: rgba(255,255,255,0.18); box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
        .feature-card:hover::before { opacity: 0.07; }
        .feature-icon { width: 54px; height: 54px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.25rem; position: relative; z-index: 1; }
        .feature-card h3 { font-size: 1.05rem; font-weight: 700; color: white; margin-bottom: 0.6rem; position: relative; z-index: 1; }
        .feature-card p { font-size: 0.88rem; color: rgba(255,255,255,0.6); line-height: 1.65; position: relative; z-index: 1; }

        /* HOW IT WORKS */
        .how-section { background: linear-gradient(180deg, #030164 0%, #020146 100%); }
        .steps-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 2rem; margin-top: 3rem; position: relative; }
        .steps-grid::before { content: ''; position: absolute; top: 36px; left: 10%; right: 10%; height: 2px; background: linear-gradient(90deg, transparent, rgba(59,130,246,0.4), transparent); pointer-events: none; }
        .step-card { text-align: center; position: relative; }
        .step-number { width: 72px; height: 72px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #2563eb); display: flex; align-items: center; justify-content: center; font-size: 1.6rem; font-weight: 900; color: white; margin: 0 auto 1.25rem; box-shadow: 0 8px 25px rgba(59,130,246,0.4); position: relative; z-index: 1; }
        .step-title { font-size: 1rem; font-weight: 700; color: white; margin-bottom: 0.5rem; }
        .step-desc { font-size: 0.85rem; color: rgba(255,255,255,0.6); line-height: 1.6; }

        /* ROLES */
        .roles-section { background: #020146; }
        .roles-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-top: 3rem; }
        .role-card { border-radius: 20px; padding: 2rem; position: relative; overflow: hidden; transition: transform 0.3s; }
        .role-card:hover { transform: translateY(-5px); }
        .role-card-siswa { background: linear-gradient(135deg, rgba(59,130,246,0.15), rgba(59,130,246,0.05)); border: 1px solid rgba(59,130,246,0.25); }
        .role-card-guru { background: linear-gradient(135deg, rgba(16,185,129,0.15), rgba(16,185,129,0.05)); border: 1px solid rgba(16,185,129,0.25); }
        .role-card-admin { background: linear-gradient(135deg, rgba(245,158,11,0.15), rgba(245,158,11,0.05)); border: 1px solid rgba(245,158,11,0.25); }
        .role-emoji { font-size: 2.5rem; margin-bottom: 1rem; }
        .role-badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 100px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 0.75rem; }
        .badge-blue { background: rgba(59,130,246,0.2); color: #93c5fd; }
        .badge-green { background: rgba(16,185,129,0.2); color: #6ee7b7; }
        .badge-amber { background: rgba(245,158,11,0.2); color: #fcd34d; }
        .role-card h3 { font-size: 1.2rem; font-weight: 700; color: white; margin-bottom: 0.75rem; }
        .role-list { list-style: none; display: flex; flex-direction: column; gap: 0.5rem; }
        .role-list li { display: flex; align-items: flex-start; gap: 0.5rem; font-size: 0.88rem; color: rgba(255,255,255,0.7); line-height: 1.4; }
        .role-list li::before { content: '✓'; color: #60a5fa; font-weight: 700; flex-shrink: 0; margin-top: 0.05rem; }
        .role-card-guru .role-list li::before { color: #6ee7b7; }
        .role-card-admin .role-list li::before { color: #fcd34d; }

        /* CTA */
        .cta-section { background: linear-gradient(135deg, #030164, #020146); padding: 5rem 2rem; text-align: center; }
        .cta-inner { max-width: 700px; margin: 0 auto; }
        .cta-inner h2 { font-size: clamp(2rem, 5vw, 3rem); font-weight: 900; color: white; margin-bottom: 1rem; line-height: 1.2; }
        .cta-inner p { font-size: 1.05rem; color: rgba(255,255,255,0.65); margin-bottom: 2.5rem; line-height: 1.7; }
        .cta-buttons { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }

        /* FOOTER */
        .footer { background: #010030; color: rgba(255,255,255,0.5); text-align: center; padding: 1.5rem 2rem; font-size: 0.82rem; border-top: 1px solid rgba(255,255,255,0.06); }
        .footer span { color: rgba(255,255,255,0.8); font-weight: 600; }

        /* DIVIDER */
        .section-divider { height: 1px; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent); max-width: 1200px; margin: 0 auto; }

        @media (max-width: 768px) {
            .about-grid { grid-template-columns: 1fr; gap: 2.5rem; }
            .steps-grid::before { display: none; }
            .hero-content { padding: 3.5rem 1rem; }
            .hero-cta { flex-direction: column; align-items: center; }
            .navbar-inner { padding: 0 1rem; }
            .btn-hero { width: 100%; max-width: 320px; justify-content: center; }
        }
    </style>
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="{{ route('about') }}" class="navbar-brand">
                <div class="brand-icon">SF</div>
                <div class="brand-text">
                    <h1>SINFAS</h1>
                    <p>Sistem Informasi Fasilitas</p>
                </div>
            </a>
            <div class="navbar-actions">
                @auth
                    @if(Auth::user()->role === 'admin_sarana')
                        <a href="{{ route('admin.dashboard') }}" class="btn-nav btn-nav-ghost">Dashboard Admin</a>
                    @elseif(Auth::user()->role === 'admin_sistem')
                        <a href="{{ route('system-admin.dashboard') }}" class="btn-nav btn-nav-ghost">Dashboard</a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="btn-nav btn-nav-ghost">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                            Dashboard
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn-nav btn-nav-ghost">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-nav btn-nav-primary">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- HERO --}}
    <section class="hero">
        <div class="hero-bg-orbs">
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>
        </div>
        <div class="hero-content">
            <div class="hero-logo">SF</div>
            <div class="hero-badge">
                <div class="hero-badge-dot"></div>
                Sistem Digital Terpadu
            </div>
            <h1 class="hero-title"><span>SINFAS</span></h1>
            <p class="hero-subtitle">Sistem Informasi Fasilitas</p>
            <p class="hero-desc">
                Platform digital terintegrasi untuk pengelolaan peminjaman sarana dan prasarana
                sekolah secara efisien, transparan, dan mudah diakses kapan saja.
            </p>
            <div class="hero-cta">
                @auth
                    @if(in_array(Auth::user()->role, ['admin_sarana', 'admin_sistem']))
                        <a href="{{ route('admin.dashboard') }}" class="btn-hero btn-hero-primary">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                            Ke Dashboard
                        </a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="btn-hero btn-hero-primary">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                            Ke Dashboard
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn-hero btn-hero-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="btn-hero btn-hero-outline">Sudah Punya Akun? Masuk</a>
                @endauth
            </div>
        </div>
    </section>

    {{-- STATS STRIP --}}
    <div class="stats-strip">
        <div class="stats-strip-inner">
            <div class="stat-item">
                <div class="stat-number">📦</div>
                <div class="stat-label">Manajemen Inventaris</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">⚡</div>
                <div class="stat-label">Proses Cepat &amp; Mudah</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">🔍</div>
                <div class="stat-label">Tracking Real-Time</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">📊</div>
                <div class="stat-label">Laporan Lengkap</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">🔒</div>
                <div class="stat-label">Sistem Aman &amp; Terstruktur</div>
            </div>
        </div>
    </div>

    {{-- ABOUT --}}
    <section class="section about-section" id="tentang">
        <div class="section-inner">
            <div class="about-grid">
                <div>
                    <div class="section-tag">Tentang Kami</div>
                    <h2 class="section-title">Apa itu <span>SINFAS</span>?</h2>
                    <p class="section-desc" style="margin-bottom: 1.5rem;">
                        SINFAS (Sistem Informasi Fasilitas) adalah platform digital yang dirancang khusus
                        untuk mempermudah pengelolaan peminjaman fasilitas dan peralatan sekolah.
                    </p>
                    <p class="section-desc" style="margin-bottom: 1.5rem;">
                        Dengan SINFAS, siswa dan guru dapat mengajukan peminjaman peralatan seperti
                        laptop, proyektor, mikrofon, headset, dan berbagai fasilitas lainnya secara online —
                        tanpa perlu antri atau mengurus berkas fisik.
                    </p>
                    <p class="section-desc">
                        Admin sarana dapat memonitor, menyetujui, atau menolak pengajuan, serta
                        memantau status semua fasilitas yang sedang dipinjam atau dikembalikan secara real-time.
                    </p>
                </div>
                <div style="position: relative;">
                    <div class="about-card-main">
                        <div class="floating-badge fb-2">
                            <span>✨</span> Sistem Modern
                        </div>
                        <div class="about-icon-row">
                            <div class="about-icon-box" style="background: rgba(59,130,246,0.15);">🖥️</div>
                            <div class="about-icon-box" style="background: rgba(16,185,129,0.15);">📱</div>
                            <div class="about-icon-box" style="background: rgba(245,158,11,0.15);">☁️</div>
                        </div>
                        <div class="about-card-title">Platform Berbasis Web</div>
                        <div class="about-card-text">
                            Dapat diakses dari perangkat apa pun — komputer, laptop, maupun ponsel —
                            selama terhubung ke internet. Dirancang responsif dan ramah pengguna.
                        </div>
                        <div style="height: 1px; background: rgba(255,255,255,0.08); margin: 1.5rem 0;"></div>
                        <div class="about-icon-row">
                            <div class="about-icon-box" style="background: rgba(139,92,246,0.15);">🔐</div>
                        </div>
                        <div class="about-card-title">Akses Berbasis Peran</div>
                        <div class="about-card-text">
                            Setiap pengguna memiliki hak akses yang disesuaikan dengan perannya:
                            Siswa, Guru, atau Admin Sarana — menjamin keamanan dan ketertiban data.
                        </div>
                        <div class="floating-badge fb-1">
                            <span>✅</span> Terverifikasi Admin
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    {{-- FEATURES --}}
    <section class="section features-section" id="fitur">
        <div class="section-inner">
            <div class="section-tag">Fitur Unggulan</div>
            <h2 class="section-title">Semua yang Kamu <span>Butuhkan</span></h2>
            <p class="section-desc">Dirancang untuk memenuhi kebutuhan pengelolaan fasilitas sekolah dari A sampai Z.</p>
            <div class="features-grid">
                <div class="feature-card" style="--card-color: #3b82f6;">
                    <div class="feature-icon" style="background: rgba(59,130,246,0.15);">📋</div>
                    <h3>Pengajuan Peminjaman Online</h3>
                    <p>Ajukan peminjaman fasilitas kapan saja dan di mana saja. Isi formulir digital, pilih tanggal, dan tunggu persetujuan admin.</p>
                </div>
                <div class="feature-card" style="--card-color: #10b981;">
                    <div class="feature-icon" style="background: rgba(16,185,129,0.15);">📊</div>
                    <h3>Tracking Status Real-Time</h3>
                    <p>Pantau status pengajuanmu secara langsung — mulai dari menunggu, disetujui, sedang dipinjam, hingga dikembalikan.</p>
                </div>
                <div class="feature-card" style="--card-color: #f59e0b;">
                    <div class="feature-icon" style="background: rgba(245,158,11,0.15);">🔄</div>
                    <h3>Proses Pengembalian Digital</h3>
                    <p>Laporkan pengembalian barang secara digital. Admin memverifikasi kondisi barang dan status langsung terupdate.</p>
                </div>
                <div class="feature-card" style="--card-color: #8b5cf6;">
                    <div class="feature-icon" style="background: rgba(139,92,246,0.15);">🗂️</div>
                    <h3>Manajemen Inventaris</h3>
                    <p>Admin dapat mengelola data seluruh barang/fasilitas: menambah, mengedit, atau menonaktifkan item dengan mudah.</p>
                </div>
                <div class="feature-card" style="--card-color: #06b6d4;">
                    <div class="feature-icon" style="background: rgba(6,182,212,0.15);">🔍</div>
                    <h3>Pencarian &amp; Filter Cepat</h3>
                    <p>Cari fasilitas berdasarkan nama atau kategori. Filter berdasarkan ketersediaan dan urutkan sesuai preferensi.</p>
                </div>
                <div class="feature-card" style="--card-color: #ef4444;">
                    <div class="feature-icon" style="background: rgba(239,68,68,0.15);">📄</div>
                    <h3>Laporan Siap Cetak</h3>
                    <p>Admin dapat menghasilkan laporan komprehensif mengenai aktivitas peminjaman untuk keperluan dokumentasi dan evaluasi.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    {{-- HOW IT WORKS --}}
    <section class="section how-section" id="cara-pakai">
        <div class="section-inner">
            <div style="text-align: center;">
                <div class="section-tag">Cara Penggunaan</div>
                <h2 class="section-title" style="text-align: center;">Mudah dalam <span>4 Langkah</span></h2>
                <p class="section-desc" style="margin: 0 auto; text-align: center;">Proses peminjaman yang sederhana dan terstruktur</p>
            </div>
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <div class="step-title">Daftar &amp; Login</div>
                    <div class="step-desc">Buat akun dengan NIS/NIP dan email, lalu masuk ke sistem SINFAS.</div>
                </div>
                <div class="step-card">
                    <div class="step-number">2</div>
                    <div class="step-title">Pilih Fasilitas</div>
                    <div class="step-desc">Telusuri daftar fasilitas yang tersedia, cari berdasarkan nama atau kategori.</div>
                </div>
                <div class="step-card">
                    <div class="step-number">3</div>
                    <div class="step-title">Ajukan Peminjaman</div>
                    <div class="step-desc">Isi formulir peminjaman dengan tanggal dan keperluan. Pengajuan dikirim ke admin.</div>
                </div>
                <div class="step-card">
                    <div class="step-number">4</div>
                    <div class="step-title">Gunakan &amp; Kembalikan</div>
                    <div class="step-desc">Setelah disetujui, gunakan fasilitas dan laporkan pengembalian melalui sistem.</div>
                </div>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    {{-- ROLES --}}
    <section class="section roles-section" id="pengguna">
        <div class="section-inner">
            <div class="section-tag">Siapa Penggunanya?</div>
            <h2 class="section-title">Tiga Tipe <span>Pengguna</span></h2>
            <p class="section-desc">Sistem dirancang untuk memenuhi kebutuhan semua pihak di lingkungan sekolah.</p>
            <div class="roles-grid">
                <div class="role-card role-card-siswa">
                    <div class="role-emoji">🧑‍🎓</div>
                    <div class="role-badge badge-blue">SISWA</div>
                    <h3>Siswa</h3>
                    <ul class="role-list">
                        <li>Melihat katalog fasilitas yang tersedia</li>
                        <li>Mengajukan peminjaman fasilitas</li>
                        <li>Memantau status pengajuan secara real-time</li>
                        <li>Melaporkan pengembalian barang</li>
                        <li>Melihat riwayat peminjaman pribadi</li>
                    </ul>
                </div>
                <div class="role-card role-card-guru">
                    <div class="role-emoji">👨‍🏫</div>
                    <div class="role-badge badge-green">GURU</div>
                    <h3>Guru</h3>
                    <ul class="role-list">
                        <li>Melihat katalog fasilitas yang tersedia</li>
                        <li>Mengajukan peminjaman fasilitas</li>
                        <li>Memantau status pengajuan secara real-time</li>
                        <li>Melaporkan pengembalian barang</li>
                        <li>Mengelola profil akun</li>
                    </ul>
                </div>
                <div class="role-card role-card-admin">
                    <div class="role-emoji">🛡️</div>
                    <div class="role-badge badge-amber">ADMIN</div>
                    <h3>Admin Sarana</h3>
                    <ul class="role-list">
                        <li>Mengelola data seluruh fasilitas/barang</li>
                        <li>Menyetujui atau menolak pengajuan peminjaman</li>
                        <li>Memverifikasi pengembalian barang</li>
                        <li>Memantau kondisi dan ketersediaan fasilitas</li>
                        <li>Menghasilkan laporan siap cetak</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="cta-section">
        <div class="cta-inner">
            <h2>Siap Menggunakan <span style="background: linear-gradient(135deg, #60a5fa, #a78bfa); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">SINFAS</span>?</h2>
            <p>Bergabung dan nikmati kemudahan peminjaman fasilitas sekolah secara digital. Proses mudah, cepat, dan transparan.</p>
            <div class="cta-buttons">
                @auth
                    @if(in_array(Auth::user()->role, ['admin_sarana', 'admin_sistem']))
                        <a href="{{ route('admin.dashboard') }}" class="btn-hero btn-hero-primary">Kembali ke Dashboard</a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="btn-hero btn-hero-primary">🏠 Kembali ke Dashboard</a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn-hero btn-hero-primary">✨ Daftar Sekarang — Gratis!</a>
                    <a href="{{ route('login') }}" class="btn-hero btn-hero-outline">Sudah Punya Akun? Masuk</a>
                @endauth
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="footer">
        <p>© {{ date('Y') }} <span>SINFAS</span> — Sistem Informasi Fasilitas. Dikembangkan untuk kemudahan pengelolaan sarana sekolah.</p>
    </footer>

</body>
</html>

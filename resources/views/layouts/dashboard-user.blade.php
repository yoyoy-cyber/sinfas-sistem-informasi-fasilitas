<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SINFAS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            min-height: 100vh;
            background: #030164;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ===== TOP NAVBAR ===== */
        .navbar {
            background: #020146;
            color: white;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        .navbar-inner {
            max-width: 1400px;
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
            gap: 0.6rem;
            text-decoration: none;
            color: white;
            flex-shrink: 0;
        }

        .navbar-brand-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 0.8rem;
            letter-spacing: -0.5px;
        }

        .navbar-brand h1 {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .navbar-brand p {
            font-size: 0.65rem;
            opacity: 0.7;
            margin-top: -2px;
        }

        /* ===== NAV MENU (Desktop) ===== */
        .navbar-menu {
            display: flex;
            align-items: center;
            list-style: none;
            gap: 0.25rem;
        }

        .navbar-menu a {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 1rem;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.2s;
            font-weight: 500;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .navbar-menu a:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .navbar-menu a.active {
            background: rgba(255,255,255,0.18);
            color: white;
            font-weight: 600;
        }

        .navbar-menu a svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
            flex-shrink: 0;
        }

        /* ===== NAV RIGHT (profile + logout) ===== */
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
        }

        .nav-profile-btn {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.35rem 0.7rem 0.35rem 0.35rem;
            background: rgba(255,255,255,0.1);
            border-radius: 25px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            color: white;
        }

        .nav-profile-btn:hover {
            background: rgba(255,255,255,0.2);
        }

        .nav-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .nav-profile-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .nav-profile-name {
            font-size: 0.8rem;
            font-weight: 600;
            line-height: 1.2;
        }

        .nav-profile-role {
            font-size: 0.65rem;
            opacity: 0.7;
            text-transform: capitalize;
        }

        .nav-logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            color: #fca5a5;
        }

        .nav-logout-btn:hover {
            background: #ef4444;
            color: white;
            border-color: #ef4444;
            transform: translateY(-1px);
        }

        .nav-logout-btn svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }

        /* ===== HAMBURGER (Mobile) ===== */
        .navbar-toggle {
            display: none;
            flex-direction: column;
            justify-content: center;
            gap: 5px;
            width: 36px;
            height: 36px;
            padding: 6px;
            background: rgba(255,255,255,0.1);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .navbar-toggle:hover {
            background: rgba(255,255,255,0.2);
        }

        .navbar-toggle span {
            display: block;
            width: 100%;
            height: 2px;
            background: white;
            border-radius: 2px;
            transition: all 0.3s;
        }

        .navbar-toggle.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .navbar-toggle.active span:nth-child(2) {
            opacity: 0;
        }

        .navbar-toggle.active span:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -5px);
        }

        /* ===== MOBILE DROPDOWN MENU ===== */
        .navbar-mobile-menu {
            display: none;
            background: #1a3352;
            border-top: 1px solid rgba(255,255,255,0.08);
            padding: 0.75rem 1rem;
            animation: slideDown 0.25s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .navbar-mobile-menu.active {
            display: block;
        }

        .navbar-mobile-menu a {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.75rem 1rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.15s;
            font-weight: 500;
            font-size: 0.92rem;
        }

        .navbar-mobile-menu a:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .navbar-mobile-menu a.active {
            background: rgba(255,255,255,0.15);
            color: white;
            font-weight: 600;
        }

        .navbar-mobile-menu a svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
        }

        .mobile-divider {
            height: 1px;
            background: rgba(255,255,255,0.08);
            margin: 0.5rem 0;
        }

        .mobile-profile-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.6rem 1rem;
        }

        .mobile-profile-left {
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }

        .mobile-profile-left .nav-avatar {
            width: 36px;
            height: 36px;
            font-size: 0.9rem;
        }

        .mobile-profile-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: white;
        }

        .mobile-profile-role {
            font-size: 0.72rem;
            color: rgba(255,255,255,0.6);
            text-transform: capitalize;
        }

        .mobile-logout-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .mobile-logout-btn:hover {
            background: #dc2626;
        }

        .mobile-logout-btn svg {
            width: 15px;
            height: 15px;
            fill: currentColor;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            flex: 1;
            min-height: calc(100vh - 64px);
            background: #030164;
        }

        /* ===== TOP BAR (Greeting) ===== */
        .top-bar {
            background: white;
            padding: 1.5rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .top-bar-greeting {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.3rem;
        }

        .top-bar-greeting span {
            color: #2563eb;
        }

        .top-bar-date {
            font-size: 0.9rem;
            color: #94a3b8;
        }

        .content-area {
            padding: 2rem;
        }

        /* ===== CUSTOM LOGOUT MODAL ===== */
        .logout-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            backdrop-filter: blur(4px);
        }

        .logout-modal-overlay.active {
            display: flex;
        }

        .logout-modal {
            background: #e5e7eb;
            border-radius: 20px;
            width: 500px;
            max-width: 90%;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            animation: modalPop 0.3s ease;
            border: 3px solid #1f2937;
        }

        @keyframes modalPop {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .logout-modal-header {
            background: #dc2626;
            padding: 1rem;
            text-align: center;
        }

        .logout-modal-header h3 {
            color: white;
            font-size: 1.3rem;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .logout-modal-body {
            padding: 2.5rem 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .warning-icon { flex-shrink: 0; }
        .warning-icon svg { width: 90px; height: 90px; }

        .logout-modal-body p {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1f2937;
            line-height: 1.4;
        }

        .logout-modal-footer {
            padding: 1.5rem 2rem 2rem;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        .btn-modal-ya {
            background: #2563eb;
            color: white;
            border: none;
            padding: 0.8rem 2.5rem;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            letter-spacing: 1px;
        }

        .btn-modal-ya:hover {
            background: #1d4ed8;
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
        }

        .btn-modal-tidak {
            background: #dc2626;
            color: white;
            border: none;
            padding: 0.8rem 2.5rem;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            letter-spacing: 1px;
        }

        .btn-modal-tidak:hover {
            background: #b91c1c;
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4);
        }

        /* ===== PAGE HEADER & BACK BUTTON ===== */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            width: 100%;
        }

        .page-title-section {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .page-title-section h2, .page-header h2 {
            color: white;
            font-size: 1.8rem;
            font-weight: 800;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .btn-back {
            background: white;
            color: #1e3a5f;
            border: none;
            padding: 0.75rem 1.4rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            white-space: nowrap;
            margin-left: auto;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            background: #f8fafc;
            color: #1e40af;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .navbar-menu {
                display: none;
            }

            .navbar-right {
                display: none;
            }

            .navbar-toggle {
                display: flex;
            }

            .navbar-inner {
                padding: 0 1rem;
            }

            .content-area {
                padding: 1rem;
            }

            .top-bar {
                padding: 1rem;
            }

            .top-bar-greeting {
                font-size: 1.2rem;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.8rem;
            }

            .page-title-section h2, .page-header h2 {
                font-size: 1.4rem;
            }

            .btn-back {
                margin-left: 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="wrapper">
        <!-- ===== NAVBAR ===== -->
        <nav class="navbar">
            <div class="navbar-inner">
                <!-- Brand -->
                <a href="{{ route('about') }}" class="navbar-brand">
                    <div class="navbar-brand-icon">SF</div>
                    <div>
                        <h1>SINFAS</h1>
                        <p>Sistem Informasi Fasilitas</p>
                    </div>
                </a>

                <!-- Nav Menu (Desktop) -->
                <ul class="navbar-menu">
                    <li>
                        <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.status') }}" class="{{ request()->routeIs('user.status*') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24"><path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/></svg>
                            Status Pengajuan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.pengembalian.index') }}" class="{{ request()->routeIs('user.pengembalian.*') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24"><path d="M19 8l-4 4h3c0 3.31-2.69 6-6 6-1.01 0-1.97-.25-2.8-.7l-1.46 1.46C8.97 19.54 10.43 20 12 20c4.42 0 8-3.58 8-8h3l-4-4zM6 12c0-3.31 2.69-6 6-6 1.01 0 1.97.25 2.8.7l1.46-1.46C15.03 4.46 13.57 4 12 4 7.58 4 4 7.58 4 12H1l4 4 4-4H6z"/></svg>
                            Pengembalian
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.profil') }}" class="{{ request()->routeIs('user.profil') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                            Profil
                        </a>
                    </li>
                </ul>

                <!-- Right Section (Desktop) -->
                <div class="navbar-right">
                    <a href="{{ route('user.profil') }}" class="nav-profile-btn">
                        <div class="nav-avatar">
                            {{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 1)) }}
                        </div>
                        <div class="nav-profile-info">
                            <span class="nav-profile-name">{{ Auth::user()->nama_lengkap }}</span>
                            <span class="nav-profile-role">{{ str_replace('_', ' ', Auth::user()->role) }}</span>
                        </div>
                    </a>
                    <button type="button" class="nav-logout-btn" onclick="openLogoutModal()" title="Logout">
                        <svg viewBox="0 0 24 24"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>
                    </button>
                </div>

                <!-- Hamburger (Mobile) -->
                <button class="navbar-toggle" id="navbarToggle" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>

            <!-- Mobile Dropdown Menu -->
            <div class="navbar-mobile-menu" id="navbarMobileMenu">
                <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('user.status') }}" class="{{ request()->routeIs('user.status*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/></svg>
                    Status Pengajuan
                </a>
                <a href="{{ route('user.pengembalian.index') }}" class="{{ request()->routeIs('user.pengembalian.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M19 8l-4 4h3c0 3.31-2.69 6-6 6-1.01 0-1.97-.25-2.8-.7l-1.46 1.46C8.97 19.54 10.43 20 12 20c4.42 0 8-3.58 8-8h3l-4-4zM6 12c0-3.31 2.69-6 6-6 1.01 0 1.97.25 2.8.7l1.46-1.46C15.03 4.46 13.57 4 12 4 7.58 4 4 7.58 4 12H1l4 4 4-4H6z"/></svg>
                    Pengembalian
                </a>
                <a href="{{ route('user.profil') }}" class="{{ request()->routeIs('user.profil') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    Profil
                </a>

                <div class="mobile-divider"></div>

                <div class="mobile-profile-section">
                    <div class="mobile-profile-left">
                        <div class="nav-avatar">
                            {{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 1)) }}
                        </div>
                        <div>
                            <div class="mobile-profile-name">{{ Auth::user()->nama_lengkap }}</div>
                            <div class="mobile-profile-role">{{ str_replace('_', ' ', Auth::user()->role) }}</div>
                        </div>
                    </div>
                    <button type="button" class="mobile-logout-btn" onclick="openLogoutModal()">
                        <svg viewBox="0 0 24 24"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>
                        Logout
                    </button>
                </div>
            </div>
        </nav>

        <main class="main-content">
            @if(request()->routeIs('user.dashboard'))
            <!-- TOP BAR (Greeting) -->
            <div class="top-bar">
                <div class="top-bar-greeting">Halo <span>{{ Auth::user()->nama_lengkap }}</span>, mau pinjam apa hari ini? 👋</div>
                <div class="top-bar-date">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</div>
            </div>
            @endif

            <!-- CONTENT AREA -->
            <div class="content-area">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- CUSTOM LOGOUT MODAL -->
    <div class="logout-modal-overlay" id="logoutModal">
        <div class="logout-modal">
            <div class="logout-modal-header">
                <h3>PERINGATAN!!!</h3>
            </div>
            <div class="logout-modal-body">
                <div class="warning-icon">
                    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <polygon points="50,8 95,88 5,88" fill="#facc15" stroke="#dc2626" stroke-width="5" stroke-linejoin="round"/>
                        <text x="50" y="72" font-size="50" font-weight="900" text-anchor="middle" fill="#1f2937">!</text>
                    </svg>
                </div>
                <p>Apakah anda yakin ingin keluar dari halaman ini?</p>
            </div>
            <div class="logout-modal-footer">
                <button class="btn-modal-ya" onclick="confirmLogout()">YA</button>
                <button class="btn-modal-tidak" onclick="closeLogoutModal()">TIDAK</button>
            </div>
        </div>
    </div>

    <!-- LOGOUT FORM (Hidden) -->
    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    @yield('scripts')

    <script>
        // Logout modal
        function openLogoutModal() {
            document.getElementById('logoutModal').classList.add('active');
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.remove('active');
        }

        function confirmLogout() {
            document.getElementById('logoutForm').submit();
        }

        document.getElementById('logoutModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLogoutModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLogoutModal();
            }
        });

        // Mobile hamburger toggle
        const navbarToggle = document.getElementById('navbarToggle');
        const navbarMobileMenu = document.getElementById('navbarMobileMenu');

        navbarToggle.addEventListener('click', function() {
            this.classList.toggle('active');
            navbarMobileMenu.classList.toggle('active');
        });

        // Close mobile menu when clicking a link
        document.querySelectorAll('.navbar-mobile-menu a').forEach(function(link) {
            link.addEventListener('click', function() {
                navbarToggle.classList.remove('active');
                navbarMobileMenu.classList.remove('active');
            });
        });
    </script>
</body>
</html>
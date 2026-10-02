<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SINFAS Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #1e3a5f 100%);
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR NAVY ===== */
        .sidebar {
            width: 260px;
            background: #1e3a5f;
            color: white;
            padding: 1.5rem 1.25rem;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            height: 100vh;
            height: 100dvh;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }

        /* ===== MOBILE TOP BAR ===== */
        .mobile-topbar {
            display: none;
            background: #1e3a5f;
            color: white;
            padding: 0.85rem 1.2rem;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .mobile-topbar-brand {
            font-size: 1.1rem;
            font-weight: 800;
            color: white;
        }

        .hamburger-btn {
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 5px;
            width: 38px;
            height: 38px;
            padding: 7px;
            background: rgba(255,255,255,0.12);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .hamburger-btn:hover {
            background: rgba(255,255,255,0.22);
        }

        .hamburger-btn span {
            display: block;
            width: 100%;
            height: 2px;
            background: white;
            border-radius: 2px;
            transition: all 0.3s;
        }

        .hamburger-btn.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .hamburger-btn.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger-btn.active span:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -5px);
        }

        /* ===== SIDEBAR OVERLAY (Mobile) ===== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            backdrop-filter: blur(2px);
        }

        .sidebar-overlay.active {
            display: block;
        }

        .sidebar-brand {
            margin-bottom: 1.5rem;
            flex-shrink: 0;
        }

        .sidebar-brand-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-brand h1 {
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 0.2rem;
            letter-spacing: 0.5px;
        }

        .sidebar-brand p {
            font-size: 0.8rem;
            opacity: 0.8;
            line-height: 1.2;
        }

        .sidebar-close-btn {
            display: none;
            background: rgba(255,255,255,0.15);
            border: none;
            border-radius: 8px;
            width: 32px;
            height: 32px;
            color: white;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            padding: 4px;
            transition: background 0.2s;
        }

        .sidebar-close-btn:hover {
            background: rgba(255,255,255,0.25);
        }

        .sidebar-close-btn svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
        }

        .sidebar-menu {
            list-style: none;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .sidebar-menu li {
            margin-bottom: 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.8rem 1rem;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.2s;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.12);
            color: white;
        }

        .sidebar-menu a.active {
            background: rgba(255,255,255,0.22);
            color: white;
            font-weight: 600;
        }

        .sidebar-menu a svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
            flex-shrink: 0;
        }

        .sidebar-footer {
            margin-top: auto;
            padding-top: 1rem;
            flex-shrink: 0;
            border-top: 1px solid rgba(255,255,255,0.12);
        }

        .btn-logout-sidebar {
            width: 100%;
            background: #ef4444;
            color: white;
            border: none;
            padding: 0.8rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-logout-sidebar:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 2rem;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #1e3a5f 100%);
        }

        /* ===== STATS CARDS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 2rem 1.5rem;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1rem;
            color: #64748b;
            font-weight: 600;
        }

        /* ===== CONTENT CARD ===== */
        .content-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .card-header h3 {
            font-size: 1.3rem;
            color: #1e293b;
            font-weight: 700;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #94a3b8;
        }

        .empty-state svg {
            width: 80px;
            height: 80px;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        .empty-state h3 {
            color: #1e293b;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #64748b;
            font-size: 0.95rem;
        }

        /* ===== MODAL BASE STYLES ===== */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 99999;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(4px);
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-box {
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

        .modal-header {
            padding: 1rem;
            text-align: center;
        }

        .modal-header h3 {
            color: white;
            font-size: 1.3rem;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .modal-header.header-success { background: #16a34a; }
        .modal-header.header-warning { background: #f59e0b; }
        .modal-header.header-danger { background: #dc2626; }

        .modal-body {
            padding: 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .modal-body.column-layout {
            flex-direction: column;
            align-items: flex-start;
        }

        .warning-icon { flex-shrink: 0; }
        .warning-icon svg { width: 90px; height: 90px; }

        .modal-body p {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1f2937;
            line-height: 1.4;
            margin: 0;
        }

        .modal-body .info-text {
            font-size: 0.95rem;
            font-weight: 500;
            color: #475569;
            margin-top: 0.5rem;
        }

        .modal-body textarea {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #d1d5db;
            border-radius: 10px;
            font-size: 0.95rem;
            font-family: inherit;
            resize: vertical;
            min-height: 100px;
            outline: none;
            transition: border-color 0.2s;
            margin-top: 1rem;
        }

        .modal-body textarea:focus {
            border-color: #1e40af;
        }

        .modal-footer {
            padding: 1.5rem 2rem 2rem;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        .btn-modal {
            padding: 0.8rem 2.5rem;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            letter-spacing: 1px;
            border: none;
        }

        .btn-modal:hover {
            transform: scale(1.05);
        }

        .btn-modal-ya { background: #2563eb; color: white; }
        .btn-modal-ya:hover { background: #1d4ed8; box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4); }

        .btn-modal-approve { background: #16a34a; color: white; }
        .btn-modal-approve:hover { background: #15803d; box-shadow: 0 4px 15px rgba(22, 163, 74, 0.4); }

        .btn-modal-reject { background: #dc2626; color: white; }
        .btn-modal-reject:hover { background: #b91c1c; box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4); }

        .btn-modal-delete { background: #dc2626; color: white; }
        .btn-modal-delete:hover { background: #b91c1c; box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4); }

        .btn-modal-tidak { background: #6b7280; color: white; }
        .btn-modal-tidak:hover { background: #4b5563; box-shadow: 0 4px 15px rgba(107, 114, 128, 0.4); }

        @media (max-width: 1200px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            /* Sidebar hidden by default on mobile */
            .sidebar {
                top: 0;
                left: 0;
                bottom: 0;
                height: 100vh;
                height: 100dvh;
                padding: 1.25rem 1rem 1.5rem 1rem;
                transform: translateX(-100%);
                z-index: 1001;
                box-shadow: 4px 0 25px rgba(0,0,0,0.35);
            }

            /* Sidebar visible when toggled */
            .sidebar.open {
                transform: translateX(0);
            }

            .mobile-topbar {
                display: flex;
            }

            .sidebar-close-btn {
                display: flex;
            }

            .main-content {
                margin-left: 0;
            }

            .stats-grid { grid-template-columns: 1fr; }

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

            .main-content {
                padding: 1rem;
            }
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
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            white-space: nowrap;
            margin-left: auto;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            background: #f8fafc;
            color: #1e40af;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- MOBILE TOP BAR -->
    <div class="mobile-topbar" id="mobileTopbar">
        <span class="mobile-topbar-brand">SINFAS ADMIN</span>
        <button class="hamburger-btn" id="hamburgerBtn" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>

    <!-- SIDEBAR OVERLAY -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="wrapper">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="sidebar-brand-header">
                    <a href="{{ route('about') }}" style="text-decoration: none; color: inherit; display: flex; flex-direction: column;">
                        <h1>SINFAS ADMIN</h1>
                        <p>Pengelolaan Sarana &amp; Prasarana</p>
                    </a>
                    <button type="button" class="sidebar-close-btn" onclick="closeSidebar()" aria-label="Tutup Menu">
                        <svg viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                    </button>
                </div>
            </div>

            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.barang.index') }}" class="{{ request()->routeIs('admin.barang.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 9h-2V7h-2v5H6v2h2v5h2v-5h2v-2z"/></svg>
                        Data Barang
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.peminjaman.index') }}" class="{{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                        Peminjaman
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.pengembalian.index') }}" class="{{ request()->routeIs('admin.pengembalian.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M19 8l-4 4h3c0 3.31-2.69 6-6 6-1.01 0-1.97-.25-2.8-.7l-1.46 1.46C8.97 19.54 10.43 20 12 20c4.42 0 8-3.58 8-8h3l-4-4zM6 12c0-3.31 2.69-6 6-6 1.01 0 1.97.25 2.8.7l1.46-1.46C15.03 4.46 13.57 4 12 4 7.58 4 4 7.58 4 12H1l4 4 4-4H6z"/></svg>
                        Pengembalian
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.laporan.index') }}" class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                        Laporan Siap Cetak
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                <button type="button" class="btn-logout-sidebar" onclick="openLogoutModal()">
                    <svg viewBox="0 0 24 24" style="width: 18px; height: 18px; fill: currentColor;">
                        <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                    </svg>
                    Logout
                </button>
            </div>
        </aside>

        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <!-- ===== MODAL LOGOUT ===== -->
    <div class="modal-overlay" id="logoutModal">
        <div class="modal-box">
            <div class="modal-header header-danger">
                <h3>PERINGATAN!!!</h3>
            </div>
            <div class="modal-body">
                <div class="warning-icon">
                    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <polygon points="50,8 95,88 5,88" fill="#facc15" stroke="#dc2626" stroke-width="5" stroke-linejoin="round"/>
                        <text x="50" y="72" font-size="50" font-weight="900" text-anchor="middle" fill="#1f2937">!</text>
                    </svg>
                </div>
                <p>Apakah anda yakin ingin keluar dari halaman ini?</p>
            </div>
            <div class="modal-footer">
                <button class="btn-modal btn-modal-ya" onclick="confirmLogout()">YA</button>
                <button class="btn-modal btn-modal-tidak" onclick="closeLogoutModal()">TIDAK</button>
            </div>
        </div>
    </div>

    <!-- ===== MODAL SETUJUI PEMINJAMAN ===== -->
    <div class="modal-overlay" id="approveModal">
        <div class="modal-box">
            <div class="modal-header header-success">
                <h3>KONFIRMASI PERSETUJUAN</h3>
            </div>
            <div class="modal-body">
                <div class="warning-icon">
                    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="50" cy="50" r="42" fill="#16a34a" stroke="#15803d" stroke-width="4"/>
                        <path d="M30 50 L45 65 L70 35" stroke="white" stroke-width="8" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div>
                    <p id="approveText">Setujui peminjaman ini?</p>
                    <p class="info-text">Barang akan otomatis dipinjamkan kepada peminjam.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-modal btn-modal-approve" onclick="confirmApprove()">YA, SETUJUI</button>
                <button class="btn-modal btn-modal-tidak" onclick="closeApproveModal()">BATAL</button>
            </div>
        </div>
    </div>

    <!-- ===== MODAL TOLAK PEMINJAMAN ===== -->
    <div class="modal-overlay" id="rejectModal">
        <div class="modal-box">
            <div class="modal-header header-danger">
                <h3>TOLAK PEMINJAMAN</h3>
            </div>
            <div class="modal-body column-layout">
                <div style="display: flex; align-items: center; gap: 1rem; width: 100%;">
                    <div class="warning-icon">
                        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <polygon points="50,8 95,88 5,88" fill="#facc15" stroke="#dc2626" stroke-width="5" stroke-linejoin="round"/>
                            <text x="50" y="72" font-size="50" font-weight="900" text-anchor="middle" fill="#1f2937">!</text>
                        </svg>
                    </div>
                    <p id="rejectText" style="font-size: 1.1rem;">Tolak peminjaman ini?</p>
                </div>
                <div style="width: 100%;">
                    <label style="font-size: 0.9rem; font-weight: 600; color: #1f2937; display: block; margin-bottom: 0.5rem;">Alasan Penolakan <span style="color: #dc2626;">*</span></label>
                    <textarea id="rejectAlasan" placeholder="Tuliskan alasan penolakan di sini..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-modal btn-modal-reject" onclick="confirmReject()">KIRIM PENOLAKAN</button>
                <button class="btn-modal btn-modal-tidak" onclick="closeRejectModal()">BATAL</button>
            </div>
        </div>
    </div>

    <!-- ===== MODAL HAPUS BARANG ===== -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-box">
            <div class="modal-header header-danger">
                <h3>KONFIRMASI HAPUS</h3>
            </div>
            <div class="modal-body">
                <div class="warning-icon">
                    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <polygon points="50,8 95,88 5,88" fill="#facc15" stroke="#dc2626" stroke-width="5" stroke-linejoin="round"/>
                        <text x="50" y="72" font-size="50" font-weight="900" text-anchor="middle" fill="#1f2937">!</text>
                    </svg>
                </div>
                <p id="deleteText">Yakin ingin menghapus barang ini?</p>
            </div>
            <div class="modal-footer">
                <button class="btn-modal btn-modal-delete" onclick="confirmDelete()">YA, HAPUS</button>
                <button class="btn-modal btn-modal-tidak" onclick="closeDeleteModal()">BATAL</button>
            </div>
        </div>
    </div>

    <!-- LOGOUT FORM (Hidden) -->
    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- FORM SETUJUI (Hidden) -->
    <form id="approveForm" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- FORM TOLAK (Hidden) -->
    <form id="rejectForm" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="alasan" id="rejectAlasanInput">
    </form>

    <!-- FORM HAPUS (Hidden) -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    @yield('scripts')

    <script>
        // ===== HAMBURGER / SIDEBAR TOGGLE (Mobile) =====
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const sidebarEl = document.querySelector('.sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function openSidebar() {
            hamburgerBtn.classList.add('active');
            sidebarEl.classList.add('open');
            sidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            hamburgerBtn.classList.remove('active');
            sidebarEl.classList.remove('open');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        hamburgerBtn.addEventListener('click', function() {
            if (sidebarEl.classList.contains('open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });

        sidebarOverlay.addEventListener('click', closeSidebar);

        // Close sidebar on link click (mobile navigation)
        document.querySelectorAll('.sidebar-menu a').forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    closeSidebar();
                }
            });
        });

        // ===== LOGOUT MODAL =====
        function openLogoutModal() {
            document.getElementById('logoutModal').classList.add('active');
        }
        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.remove('active');
        }
        function confirmLogout() {
            document.getElementById('logoutForm').submit();
        }

        // ===== APPROVE MODAL =====
        function openApproveModal(url, peminjamName) {
            document.getElementById('approveForm').action = url;
            document.getElementById('approveText').innerText = 'Setujui peminjaman dari: ' + peminjamName + '?';
            document.getElementById('approveModal').classList.add('active');
        }
        function closeApproveModal() {
            document.getElementById('approveModal').classList.remove('active');
        }
        function confirmApprove() {
            document.getElementById('approveForm').submit();
        }

        // ===== REJECT MODAL =====
        function openRejectModal(url, peminjamName) {
            document.getElementById('rejectForm').action = url;
            document.getElementById('rejectText').innerText = 'Tolak peminjaman dari: ' + peminjamName;
            document.getElementById('rejectAlasan').value = '';
            document.getElementById('rejectModal').classList.add('active');
            setTimeout(() => document.getElementById('rejectAlasan').focus(), 300);
        }
        function closeRejectModal() {
            document.getElementById('rejectModal').classList.remove('active');
        }
        function confirmReject() {
            const alasan = document.getElementById('rejectAlasan').value.trim();
            if (!alasan) {
                document.getElementById('rejectAlasan').style.borderColor = '#dc2626';
                document.getElementById('rejectAlasan').focus();
                return;
            }
            document.getElementById('rejectAlasanInput').value = alasan;
            document.getElementById('rejectForm').submit();
        }

        // ===== DELETE MODAL =====
        function openDeleteModal(url, itemName) {
            document.getElementById('deleteForm').action = url;
            document.getElementById('deleteText').innerText = 'Yakin ingin menghapus "' + itemName + '"? Data tidak dapat dikembalikan!';
            document.getElementById('deleteModal').classList.add('active');
        }
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }
        function confirmDelete() {
            document.getElementById('deleteForm').submit();
        }

        // Close modal when clicking outside
        document.querySelectorAll('.modal-overlay').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                }
            });
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-overlay').forEach(modal => {
                    modal.classList.remove('active');
                });
            }
        });

        // Reset textarea border on input
        document.getElementById('rejectAlasan').addEventListener('input', function() {
            this.style.borderColor = '#d1d5db';
        });
    </script>
</body>
</html>
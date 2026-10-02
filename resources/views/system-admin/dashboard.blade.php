@extends('layouts.dashboard-admin')

@section('title', 'Dashboard Super Admin')

@section('styles')
<style>
    /* ===== FLOATING NAVBAR ===== */
    .navbar {
        background: white;
        padding: 0.9rem 2rem;
        margin: 1.5rem 2rem 0;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        position: sticky;
        top: 1.5rem;
        z-index: 1000;
        backdrop-filter: blur(10px);
    }

    .navbar-left {
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .logo-badge {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        font-weight: 900;
        letter-spacing: -0.5px;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.35);
    }

    .logo-details {
        display: flex;
        flex-direction: column;
    }

    .logo-text {
        font-size: 1.35rem;
        font-weight: 900;
        color: #111;
        letter-spacing: 0.5px;
        line-height: 1.1;
    }

    .logo-subtitle {
        font-size: 0.75rem;
        color: #777;
        font-weight: 500;
    }

    .navbar-right {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .nav-link-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 1.1rem;
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .nav-link-btn:hover {
        background: #2563eb;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }

    .user-profile-badge {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        padding: 0.35rem 0.8rem 0.35rem 0.35rem;
        background: #f8fafc;
        border-radius: 30px;
        border: 1px solid #eef2f6;
    }

    .profile-circle {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: white;
        font-size: 0.95rem;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
    }

    .user-info-text {
        display: flex;
        flex-direction: column;
        text-align: left;
    }

    .user-name {
        font-size: 0.85rem;
        font-weight: 700;
        color: #222;
        max-width: 130px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-role {
        font-size: 0.7rem;
        color: #2563eb;
        font-weight: 700;
    }

    .nav-divider {
        width: 1px;
        height: 28px;
        background: #e2e8f0;
        margin: 0 0.2rem;
    }

    .btn-logout {
        background: #f44336;
        color: white;
        border: none;
        padding: 0.55rem 1.1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        gap: 0.45rem;
        box-shadow: 0 4px 12px rgba(244, 67, 54, 0.25);
    }

    .btn-logout:hover {
        background: #d32f2f;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(244, 67, 54, 0.35);
    }

    .btn-logout svg {
        width: 16px;
        height: 16px;
        fill: white;
    }

    /* ===== MAIN CONTENT ===== */
    .main-content {
        padding: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* ===== FLASH NOTIFICATIONS ===== */
    .alert-box {
        padding: 1rem 1.5rem;
        border-radius: 14px;
        margin-bottom: 1.8rem;
        font-weight: 600;
        font-size: 0.95rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* ===== STATS GRID ===== */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1.2rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem 1rem;
        text-align: center;
        box-shadow: 0 6px 20px rgba(0,0,0,0.06);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
    }

    .stat-card .stat-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .stat-card .stat-number {
        font-size: 2.2rem;
        font-weight: 900;
        color: #111;
        margin-bottom: 0.2rem;
    }

    .stat-card .stat-label {
        font-size: 0.8rem;
        color: #666;
        font-weight: 600;
    }

    .stat-card.primary .stat-number { color: #2563eb; }
    .stat-card.siswa .stat-number { color: #2196F3; }
    .stat-card.guru .stat-number { color: #4CAF50; }
    .stat-card.sarana .stat-number { color: #FF9800; }
    .stat-card.barang .stat-number { color: #009688; }

    /* ===== GRID DUA KOLOM ===== */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .card-section {
        background: white;
        border-radius: 20px;
        padding: 1.8rem;
        box-shadow: 0 6px 20px rgba(0,0,0,0.06);
    }

    .card-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 0.8rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .card-header-flex h2 {
        font-size: 1.15rem;
        font-weight: 800;
        color: #111;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* ===== TABLE ===== */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: #f8fafc;
    }

    th {
        padding: 0.8rem 1rem;
        text-align: left;
        font-weight: 700;
        color: #555;
        font-size: 0.82rem;
        border-bottom: 2px solid #eef2f6;
    }

    td {
        padding: 0.9rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.85rem;
        vertical-align: middle;
    }

    tbody tr:hover {
        background: #fafafa;
    }

    .badge-role {
        padding: 0.25rem 0.65rem;
        border-radius: 12px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: capitalize;
    }

    .badge-siswa { background: #e3f2fd; color: #1976d2; }
    .badge-guru { background: #e8f5e9; color: #2e7d32; }
    .badge-admin_sarana { background: #fff3e0; color: #e65100; }
    .badge-admin_sistem { background: #dbeafe; color: #1e40af; }

    .badge-overdue {
        background: #ffebee;
        color: #c62828;
        padding: 0.25rem 0.65rem;
        border-radius: 12px;
        font-size: 0.72rem;
        font-weight: 700;
    }

    .btn-warning-action {
        background: #f44336;
        color: white;
        border: none;
        padding: 0.4rem 0.8rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-warning-action:hover {
        background: #d32f2f;
        transform: scale(1.03);
    }

    .empty-state {
        text-align: center;
        padding: 2.5rem 1rem;
        color: #777;
    }

    /* ===== QUICK ACTION BANNER ===== */
    .quick-banner {
        background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
        border-radius: 20px;
        padding: 1.8rem 2rem;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        box-shadow: 0 8px 25px rgba(37, 99, 235, 0.3);
    }

    .quick-banner h3 {
        font-size: 1.3rem;
        font-weight: 800;
        margin-bottom: 0.3rem;
    }

    .quick-banner p {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .btn-banner {
        background: white;
        color: #1e40af;
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        font-weight: 700;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.25s;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    .btn-banner:hover {
        background: #eff6ff;
        transform: translateY(-2px);
    }

    /* ===== CUSTOM LOGOUT MODAL ===== */
    .logout-modal-overlay {
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
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
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

    .warning-icon {
        flex-shrink: 0;
    }

    .warning-icon svg {
        width: 90px;
        height: 90px;
    }

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

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1200px) {
        .stats-container { grid-template-columns: repeat(3, 1fr); }
        .dashboard-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 768px) {
        .navbar {
            margin: 1rem 1rem 0;
            padding: 0.8rem 1rem;
            flex-direction: column;
            align-items: stretch;
            gap: 0.8rem;
        }
        .navbar-left {
            justify-content: flex-start;
        }
        .navbar-right {
            width: 100%;
            flex-wrap: wrap;
            gap: 0.6rem;
            justify-content: space-between;
        }
        .nav-link-btn {
            padding: 0.45rem 0.8rem;
            font-size: 0.8rem;
        }
        .user-profile-badge {
            padding: 0.25rem 0.6rem 0.25rem 0.25rem;
        }
        .user-name {
            max-width: 90px;
        }
        .nav-divider {
            display: none;
        }
        .btn-logout {
            padding: 0.45rem 0.8rem;
            font-size: 0.8rem;
        }
        .main-content {
            padding: 1rem;
        }
        .stats-container { grid-template-columns: repeat(2, 1fr); gap: 0.8rem; }
        .quick-banner { flex-direction: column; text-align: center; gap: 1.2rem; padding: 1.5rem 1.2rem; }
        .quick-banner h3 { font-size: 1.15rem; }
        .btn-banner { width: 100%; display: inline-block; box-sizing: border-box; }
    }

    @media (max-width: 480px) {
        .stats-container { grid-template-columns: 1fr; }
        .logo-text { font-size: 1.1rem; }
    }
</style>
@endsection

@section('content')

<!-- FLOATING NAVBAR -->
<nav class="navbar">
    <div class="navbar-left">
        <div class="logo-badge">SF</div>
        <div class="logo-details">
            <span class="logo-text">SINFAS Super Admin</span>
            <span class="logo-subtitle">Sistem Kontrol & Manajemen Pengguna</span>
        </div>
    </div>

    <div class="navbar-right">
        <!-- Shortcut Kelola Akun -->
        <a href="{{ route('system-admin.users') }}" class="nav-link-btn">
            👥 Kelola Pengguna
        </a>

        <!-- Profile Chip -->
        <div class="user-profile-badge" title="Profil {{ Auth::user()->nama_lengkap ?? 'Super Admin' }}">
            <div class="profile-circle">
                {{ strtoupper(substr(Auth::user()->nama_lengkap ?? 'A', 0, 1)) }}
            </div>
            <div class="user-info-text">
                <span class="user-name">{{ Auth::user()->nama_lengkap ?? 'Super Admin' }}</span>
                <span class="user-role">Admin Sistem</span>
            </div>
        </div>

        <div class="nav-divider"></div>

        <!-- Logout Button dengan Modal -->
        <button type="button" class="btn-logout" onclick="openLogoutModal()">
            <svg viewBox="0 0 24 24">
                <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
            </svg>
            Logout
        </button>
    </div>
</nav>

<!-- MAIN CONTENT -->
<div class="main-content">

    <!-- Flash Notification -->
    @if(session('success'))
        <div class="alert-box alert-success">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-box alert-error">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    <!-- QUICK ACTION BANNER -->
    <div class="quick-banner">
        <div>
            <h3>Selamat Datang, {{ Auth::user()->nama_lengkap ?? 'Admin Sistem' }}! </h3>
            <p>Kelola data seluruh akun siswa, guru, admin sarana, dan pantau sistem fasilitas secara terpusat.</p>
        </div>
        <div>
            <a href="{{ route('system-admin.users') }}" class="btn-banner">
                ➕ Kelola & Tambah Akun
            </a>
        </div>
    </div>

    <!-- STATS GRID -->
    <div class="stats-container">
        <div class="stat-card primary">
            <div class="stat-icon">👥</div>
            <div class="stat-number">{{ $stats['total_users'] ?? 0 }}</div>
            <div class="stat-label">Total Akun</div>
        </div>
        <div class="stat-card siswa">
            <div class="stat-icon"></div>
            <div class="stat-number">{{ $stats['siswa'] ?? 0 }}</div>
            <div class="stat-label">Akun Siswa</div>
        </div>
        <div class="stat-card guru">
            <div class="stat-icon">👨‍</div>
            <div class="stat-number">{{ $stats['guru'] ?? 0 }}</div>
            <div class="stat-label">Akun Guru</div>
        </div>
        <div class="stat-card sarana">
            <div class="stat-icon">🛠️</div>
            <div class="stat-number">{{ $stats['admin_sarana'] ?? 0 }}</div>
            <div class="stat-label">Admin Sarana</div>
        </div>
        <div class="stat-card barang">
            <div class="stat-icon">📦</div>
            <div class="stat-number">{{ $stats['total_barang'] ?? 0 }}</div>
            <div class="stat-label">Total Barang</div>
        </div>
    </div>

    <!-- DUA KOLOM SECTION -->
    <div class="dashboard-grid">

        <!-- MONITORING PEMINJAMAN TERLAMBAT -->
        <div class="card-section">
            <div class="card-header-flex">
                <h2>⚠️ Peminjaman Melewati Batas Waktu</h2>
                <span class="badge-overdue">{{ count($peminjamanTerlambat) }} Terlambat</span>
            </div>

            @if(count($peminjamanTerlambat) > 0)
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Peminjam</th>
                                <th>Barang</th>
                                <th>Batas Kembali</th>
                                <th>Status</th>
                                <th>Aksi Peringatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peminjamanTerlambat as $pinjam)
                                <tr>
                                    <td>
                                        <strong>{{ $pinjam->nama_peminjam }}</strong>
                                        <br>
                                        <small style="color: #777;">{{ $pinjam->no_telepon ?? '-' }} ({{ ucfirst($pinjam->role_peminjam) }})</small>
                                    </td>
                                    <td>{{ $pinjam->nama_barang }} ({{ $pinjam->kode_barang }})</td>
                                    <td>
                                        <span style="color: #d32f2f; font-weight: 700;">
                                            {{ $pinjam->tanggal_kembali_formatted }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge-overdue">Terlambat</span>
                                    </td>
                                    <td>
                                        <form action="{{ route('system-admin.send-warning', $pinjam->id) }}" method="POST" onsubmit="return confirm('Kirim peringatan dan sanksi sistem kepada {{ $pinjam->nama_peminjam }}?')">
                                            @csrf
                                            <button type="submit" class="btn-warning-action">
                                                 Beri Peringatan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <p style="font-size: 1.5rem; margin-bottom: 0.3rem;">🎉</p>
                    <p><strong>Tidak ada peminjaman yang terlambat saat ini.</strong></p>
                    <small>Semua pengembalian fasilitas berjalan tepat waktu.</small>
                </div>
            @endif
        </div>

        <!-- PENGGUNA TERBARU -->
        <div class="card-section">
            <div class="card-header-flex">
                <h2>🆕 Pengguna Baru</h2>
                <a href="{{ route('system-admin.users') }}" style="font-size: 0.8rem; color: #673AB7; font-weight: 700; text-decoration: none;">
                    Lihat Semua →
                </a>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.8rem;">
                @forelse($recentUsers as $user)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.7rem; background: #f8fafc; border-radius: 12px; border: 1px solid #eef2f6;">
                        <div style="display: flex; align-items: center; gap: 0.7rem;">
                            <div style="width: 34px; height: 34px; border-radius: 50%; background: #673AB7; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem;">
                                {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                            </div>
                            <div>
                                <strong style="font-size: 0.85rem; color: #222; display: block;">{{ $user->nama_lengkap }}</strong>
                                <small style="color: #777;">{{ $user->nis_nip }}</small>
                            </div>
                        </div>
                        <span class="badge-role badge-{{ $user->role }}">
                            {{ str_replace('_', ' ', $user->role) }}
                        </span>
                    </div>
                @empty
                    <div class="empty-state">
                        <small>Belum ada data pengguna.</small>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

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

<script>
function openLogoutModal() {
    document.getElementById('logoutModal').classList.add('active');
}

function closeLogoutModal() {
    document.getElementById('logoutModal').classList.remove('active');
}

function confirmLogout() {
    document.getElementById('logoutForm').submit();
}

// Tutup modal saat klik di luar
document.getElementById('logoutModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLogoutModal();
    }
});

// Tutup modal dengan tombol ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLogoutModal();
    }
});
</script>

@endsection
@extends('layouts.dashboard-user')

@section('title', 'Profil Saya')

@section('styles')
<style>
    .profil-header-wrapper {
        background: transparent !important;
        border-radius: 20px !important;
        padding: 2rem !important;
        box-shadow: none !important;
        margin-bottom: 2.5rem !important;
    }

    .profil-header-flex {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        gap: 1.5rem !important;
    }

    .profil-avatar-box {
        width: 100px !important;
        height: 100px !important;
        min-width: 100px !important;
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%) !important;
        border-radius: 20px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: white !important;
        font-size: 2.5rem !important;
        font-weight: 800 !important;
        flex-shrink: 0 !important;
    }

    .profil-info-box {
        display: flex !important;
        flex-direction: column !important;
        gap: 0.4rem !important;
        flex: 1 !important;
    }

    .profil-info-box h1 {
        font-size: 2rem !important;
        font-weight: 800 !important;
        color: white !important;
        margin: 0 !important;
        text-transform: capitalize !important;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2) !important;
    }

    .profil-role-badge {
        display: inline-block !important;
        background: rgba(255, 255, 255, 0.25) !important;
        color: white !important;
        padding: 0.4rem 1.2rem !important;
        border-radius: 20px !important;
        font-size: 0.9rem !important;
        font-weight: 600 !important;
        text-transform: capitalize !important;
        width: fit-content !important;
        backdrop-filter: blur(10px) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
    }

    .profil-join-date {
        font-size: 0.95rem !important;
        color: rgba(255, 255, 255, 0.95) !important;
        font-weight: 600 !important;
        margin-top: 0.5rem !important;
        letter-spacing: 0.3px !important;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2) !important;
    }

    .profil-card {
        background: white !important;
        border-radius: 20px !important;
        padding: 2rem !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1) !important;
        margin-bottom: 1.5rem !important;
    }

    .profil-card h3 {
        font-size: 1.2rem !important;
        font-weight: 700 !important;
        color: #1e293b !important;
        margin-bottom: 1.5rem !important;
        padding-bottom: 1rem !important;
        border-bottom: 2px solid #f1f5f9 !important;
    }

    .form-grid {
        display: grid !important;
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 1.5rem !important;
    }

    .form-group {
        display: flex !important;
        flex-direction: column !important;
    }

    .form-group.full-width {
        grid-column: 1 / -1 !important;
    }

    .form-group label {
        font-size: 0.85rem !important;
        font-weight: 600 !important;
        color: #64748b !important;
        margin-bottom: 0.5rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
    }

    .form-group input {
        padding: 0.9rem 1rem !important;
        border: 2px solid #e2e8f0 !important;
        border-radius: 12px !important;
        font-size: 0.95rem !important;
        color: #1e293b !important;
        outline: none !important;
        transition: all 0.2s !important;
    }

    .form-group input:focus {
        border-color: #1e40af !important;
        box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1) !important;
    }

    .form-group input:disabled {
        background: #f1f5f9 !important;
        color: #94a3b8 !important;
        cursor: not-allowed !important;
    }

    .btn-save {
        background: #1e40af !important;
        color: white !important;
        border: none !important;
        padding: 0.9rem 2rem !important;
        border-radius: 12px !important;
        font-size: 1rem !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        transition: all 0.2s !important;
        margin-top: 1rem !important;
    }

    .btn-save:hover {
        background: #1e3a8a !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 12px rgba(30, 64, 175, 0.35) !important;
    }

    .alert-box {
        padding: 1rem 1.3rem !important;
        border-radius: 12px !important;
        margin-bottom: 1.5rem !important;
        font-weight: 500 !important;
        background: white !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1) !important;
    }

    .alert-success {
        color: #166534 !important;
        border-left: 4px solid #16a34a !important;
    }

    .stats-info {
        display: grid !important;
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 1rem !important;
        margin-top: 1.5rem !important;
    }

    .stat-info-item {
        background: #f8fafc !important;
        padding: 1.2rem 1rem !important;
        border-radius: 14px !important;
        text-align: center !important;
        border: 1px solid #e2e8f0 !important;
        transition: all 0.25s ease !important;
    }

    .stat-info-item:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 6px 18px rgba(0,0,0,0.06) !important;
        border-color: #cbd5e1 !important;
    }

    .stat-info-item .number {
        font-size: 1.6rem !important;
        font-weight: 900 !important;
    }

    .stat-info-item .label {
        font-size: 0.82rem !important;
        font-weight: 600 !important;
        color: #64748b !important;
        margin-top: 0.35rem !important;
    }

    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr !important; }
        .profil-header-flex { flex-direction: column !important; align-items: center !important; text-align: center !important; }
        .stats-info { grid-template-columns: 1fr !important; }
    }
</style>
@endsection

@section('content')

<!-- PROFIL HEADER -->
<div class="profil-header-wrapper">
    <div class="profil-header-flex">
        <div class="profil-avatar-box">
            {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
        </div>
        <div class="profil-info-box">
            <h1>{{ $user->nama_lengkap }}</h1>
            <span class="profil-role-badge">{{ str_replace('_', ' ', $user->role) }}</span>
            <div class="profil-join-date">
                Bergabung sejak {{ \Carbon\Carbon::parse($user->created_at)->isoFormat('DD MMMM YYYY') }}
            </div>
        </div>
    </div>
</div>

<!-- ALERT -->
@if(session('success'))
    <div class="alert-box alert-success">✅ {{ session('success') }}</div>
@endif

<!-- FORM EDIT PROFIL -->
<div class="profil-card">
    <h3>✏️ Edit Profil</h3>
    <form action="{{ route('user.profil.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required>
            </div>

            <div class="form-group">
                <label>NIS / NIP</label>
                <input type="text" value="{{ $user->nis_nip }}" disabled>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-group">
                <label>No. Telepon</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" required>
            </div>

            <div class="form-group">
                <label>Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                <input type="password" name="password" placeholder="Masukkan password baru">
            </div>

            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" placeholder="Ulangi password baru">
            </div>
        </div>

        <button type="submit" class="btn-save">💾 Simpan Perubahan</button>
    </form>
</div>

<!-- STATISTIK PEMINJAMAN -->
<div class="profil-card">
    <h3>📊 Statistik Peminjaman Saya</h3>
    <div class="stats-info">
        <a href="{{ route('user.status') }}" class="stat-info-item" style="text-decoration: none; display: block; transition: all 0.2s;">
            <div class="number" style="color: #d97706;">{{ $stats['pending'] ?? 0 }}</div>
            <div class="label">Menunggu Persetujuan</div>
        </a>
        <a href="{{ route('user.pengembalian.index') }}" class="stat-info-item" style="text-decoration: none; display: block; transition: all 0.2s;">
            <div class="number" style="color: #2563eb;">{{ $stats['sedang_dipinjam'] ?? 0 }}</div>
            <div class="label">Sedang Dipinjam</div>
        </a>
        <a href="{{ route('user.status') }}" class="stat-info-item" style="text-decoration: none; display: block; transition: all 0.2s;">
            <div class="number" style="color: #16a34a;">{{ $stats['selesai'] ?? 0 }}</div>
            <div class="label">Selesai</div>
        </a>
    </div>
</div>

@endsection
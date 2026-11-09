@extends('layouts.admin-sarana')

@section('title', 'Profil Admin Sarana')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .page-title-section {
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .page-title-section h2 {
        color: white;
        font-size: 1.8rem;
        font-weight: 800;
        margin: 0;
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
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        background: #f8fafc;
        color: #2563eb;
    }

    /* ===== PROFIL HEADER CARD ===== */
    .profil-hero-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .profil-avatar {
        width: 100px;
        height: 100px;
        min-width: 100px;
        background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.8rem;
        font-weight: 800;
        box-shadow: 0 8px 24px rgba(37, 99, 235, 0.25);
    }

    .profil-details {
        flex: 1;
        min-width: 250px;
    }

    .profil-details h3 {
        font-size: 1.8rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.4rem;
    }

    .profil-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 0.8rem;
    }

    .role-badge {
        background: #dbeafe;
        color: #1e40af;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .nip-badge {
        background: #f1f5f9;
        color: #475569;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .profil-join {
        font-size: 0.88rem;
        color: #64748b;
    }

    /* ===== STATS MINI GRID ===== */
    .stats-summary {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-box {
        background: white;
        border-radius: 16px;
        padding: 1.2rem;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: transform 0.2s;
    }

    .stat-box:hover {
        transform: translateY(-3px);
    }

    .stat-box .number {
        font-size: 1.8rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 0.2rem;
    }

    .stat-box .label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
    }

    /* ===== FORM CARD ===== */
    .content-card {
        background: white;
        border-radius: 20px;
        padding: 2.2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.8rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f5f9;
    }

    .card-header h3 {
        font-size: 1.3rem;
        color: #1e293b;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        font-size: 0.85rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-group input {
        padding: 0.9rem 1.1rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.95rem;
        color: #1e293b;
        outline: none;
        transition: all 0.2s;
        background: #f8fafc;
    }

    .form-group input:focus {
        border-color: #2563eb;
        background: white;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .form-group input:disabled {
        background: #e2e8f0;
        color: #64748b;
        cursor: not-allowed;
    }

    .section-divider {
        grid-column: 1 / -1;
        margin: 1rem 0 0.5rem;
        padding-top: 1.2rem;
        border-top: 2px dashed #e2e8f0;
    }

    .section-divider h4 {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1e3a5f;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .section-divider p {
        font-size: 0.82rem;
        color: #64748b;
        margin-top: 0.2rem;
    }

    .btn-submit {
        background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
        color: white;
        border: none;
        padding: 0.95rem 2.2rem;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 1.5rem;
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
    }

    .alert-box {
        padding: 1rem 1.4rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .alert-success {
        background: white;
        color: #166534;
        border-left: 5px solid #16a34a;
    }

    .alert-danger {
        background: white;
        color: #991b1b;
        border-left: 5px solid #dc2626;
    }

    @media (max-width: 900px) {
        .stats-summary {
            grid-template-columns: repeat(2, 1fr);
        }
        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .profil-hero-card {
            flex-direction: column;
            text-align: center;
            gap: 1.2rem;
        }
        .profil-meta {
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title-section">
        <span style="font-size: 1.8rem;">⚙️</span>
        <h2>Kelola Profil Admin</h2>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn-back">← Dashboard</a>
</div>

{{-- NOTIFIKASI SUCCESS / ERROR --}}
@if(session('success'))
    <div class="alert-box alert-success">
        ✅ {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert-box alert-danger">
        <div style="margin-bottom: 0.4rem;">⚠️ Terjadi kesalahan:</div>
        <ul style="margin: 0; padding-left: 1.2rem; font-weight: 500;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- PROFIL HERO CARD --}}
<div class="profil-hero-card">
    <div class="profil-avatar">
        {{ strtoupper(substr($user->nama_lengkap ?? $user->username ?? 'A', 0, 1)) }}
    </div>
    <div class="profil-details">
        <h3>{{ $user->nama_lengkap ?? $user->nama ?? 'Admin Sarana' }}</h3>
        <div class="profil-meta">
            <span class="role-badge">🛡️ Admin Sarana &amp; Prasarana</span>
            <span class="nip-badge">NIP / Username: <strong>{{ $user->nis_nip ?? $user->username }}</strong></span>
        </div>
        <div class="profil-join">
            📅 Terdaftar sejak: {{ \Carbon\Carbon::parse($user->created_at)->locale('id')->isoFormat('DD MMMM YYYY') }}
        </div>
    </div>
</div>

{{-- STATISTIK MINI --}}
<div class="stats-summary">
    <div class="stat-box">
        <div class="number" style="color: #2563eb;">{{ $stats['total_barang'] ?? 0 }}</div>
        <div class="label">Total Sarana/Barang</div>
    </div>
    <div class="stat-box">
        <div class="number" style="color: #d97706;">{{ $stats['peminjaman_pending'] ?? 0 }}</div>
        <div class="label">Menunggu Persetujuan</div>
    </div>
    <div class="stat-box">
        <div class="number" style="color: #16a34a;">{{ $stats['sedang_dipinjam'] ?? 0 }}</div>
        <div class="label">Sedang Dipinjam</div>
    </div>
    <div class="stat-box">
        <div class="number" style="color: #7c3aed;">{{ $stats['pengembalian_pending'] ?? 0 }}</div>
        <div class="label">Verifikasi Pengembalian</div>
    </div>
</div>

{{-- FORM EDIT PROFIL & GANTI PASSWORD --}}
<div class="content-card">
    <div class="card-header">
        <h3>✏️ Edit Data Akun &amp; Ganti Password</h3>
    </div>

    <form action="{{ route('admin.profil.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-group">
                <label>Nama Lengkap *</label>
                <input type="text" name="nama" value="{{ old('nama', $user->nama_lengkap ?? $user->nama) }}" required placeholder="Masukkan nama lengkap">
            </div>

            <div class="form-group">
                <label>Username / NIP</label>
                <input type="text" value="{{ $user->nis_nip ?? $user->username }}" disabled title="Username/NIP tidak dapat diubah">
            </div>

            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required placeholder="admin@sarana.com">
            </div>

            <div class="form-group">
                <label>No. HP / WhatsApp *</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp ?? $user->nomor_kontak) }}" required placeholder="Contoh: 08123456789">
            </div>

            {{-- GANTI PASSWORD --}}
            <div class="section-divider">
                <h4>🔒 Keamanan &amp; Ganti Password</h4>
                <p>Kosongkan kolom password berikut jika Anda tidak ingin mengubah password akun saat ini.</p>
            </div>

            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="password" placeholder="Minimal 6 karakter" autocomplete="new-password">
            </div>

            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" placeholder="Ulangi password baru" autocomplete="new-password">
            </div>
        </div>

        <button type="submit" class="btn-submit">
            💾 Simpan Perubahan Profil
        </button>
    </form>
</div>
@endsection

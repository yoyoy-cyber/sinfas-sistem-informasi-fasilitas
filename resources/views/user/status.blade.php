@extends('layouts.dashboard-user')

@section('title', 'Status Pengajuan')

@section('styles')
<style>
    .page-header {
        margin-bottom: 1.5rem;
    }

    .page-header h2 {
        color: white;
        font-size: 1.8rem;
        font-weight: 800;
        margin: 0;
    }

    .status-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }

    .status-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f5f9;
    }

    .status-card-header h3 {
        font-size: 1.3rem;
        color: #1e293b;
        font-weight: 700;
    }

    .status-badge {
        padding: 0.5rem 1.2rem;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
    }

    .status-pending { background: #fef3c7; color: #92400e; }
    .status-disetujui { background: #dcfce7; color: #166534; }
    .status-ditolak { background: #fee2e2; color: #991b1b; }
    .status-dikembalikan { background: #e0e7ff; color: #3730a3; }

    .pengajuan-item {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 1.5rem;
        border: 2px solid #f1f5f9;
        border-radius: 15px;
        margin-bottom: 1rem;
        transition: all 0.2s;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
    }

    .pengajuan-item:hover {
        border-color: #1e40af;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .pengajuan-image {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        overflow: hidden;
        flex-shrink: 0;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pengajuan-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pengajuan-image-placeholder {
        font-size: 2rem;
    }

    .pengajuan-info {
        flex: 1;
    }

    .pengajuan-info h4 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.3rem;
    }

    .pengajuan-info .kategori {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 0.5rem;
    }

    .pengajuan-info .tanggal {
        font-size: 0.85rem;
        color: #64748b;
    }

    .pengajuan-info .tanggal strong {
        color: #1e293b;
    }

    .pengajuan-status {
        text-align: right;
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
    }

    .alert-box {
        padding: 1rem 1.3rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        font-weight: 500;
        background: white;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .alert-success {
        color: #166534;
        border-left: 4px solid #16a34a;
    }

    .refresh-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: #64748b;
        margin-left: 1rem;
    }

    .refresh-indicator .dot {
        width: 8px;
        height: 8px;
        background: #16a34a;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    /* ===== MODAL POPUP PERHATIAN (IDENTIK GAMBAR) ===== */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.45);
        z-index: 99999;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(4px);
        padding: 1rem;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-box-notice {
        background: #e5e7eb;
        border-radius: 20px;
        width: 520px;
        max-width: 92%;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
        animation: modalPop 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        border: 2px solid #374151;
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

    .modal-header-blue {
        background: #2563eb;
        padding: 0.9rem;
        text-align: center;
    }

    .modal-header-blue h3 {
        color: #0f172a;
        font-size: 1.4rem;
        font-weight: 900;
        letter-spacing: 1.5px;
        margin: 0;
        text-transform: uppercase;
    }

    .modal-body-notice {
        padding: 2.2rem 2rem 1.8rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        background: #e5e7eb;
    }

    .modal-notice-icon {
        flex-shrink: 0;
    }

    .modal-notice-icon svg {
        width: 75px;
        height: 75px;
        display: block;
    }

    .modal-notice-text {
        font-size: 1.25rem;
        font-weight: 800;
        color: #111827;
        line-height: 1.4;
        margin: 0;
    }

    .modal-footer-notice {
        padding: 0.5rem 2rem 2.2rem;
        display: flex;
        justify-content: center;
        background: #e5e7eb;
    }

    .btn-tutup-notice {
        background: #9ca3af;
        color: #111827;
        font-size: 1.15rem;
        font-weight: 900;
        padding: 0.75rem 3.5rem;
        border-radius: 30px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        letter-spacing: 1px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .btn-tutup-notice:hover {
        background: #6b7280;
        color: white;
        transform: scale(1.05);
        box-shadow: 0 6px 18px rgba(0,0,0,0.2);
    }

    @media (max-width: 768px) {
        .pengajuan-item {
            flex-direction: column;
            align-items: flex-start;
        }
        .pengajuan-status {
            text-align: left;
            margin-top: 0.5rem;
        }

        .modal-body-notice {
            flex-direction: column;
            text-align: center;
            padding: 1.8rem 1.2rem 1.2rem;
            gap: 1rem;
        }

        .modal-notice-icon svg {
            width: 65px;
            height: 65px;
        }

        .modal-notice-text {
            font-size: 1.1rem;
        }

        .modal-footer-notice {
            padding: 0.5rem 1.2rem 1.8rem;
        }

        .btn-tutup-notice {
            width: 100%;
            padding: 0.75rem 1.5rem;
        }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div class="page-title-section">
        <h2>📋 Status Pengajuan Peminjaman</h2>
    </div>
    <a href="{{ route('user.dashboard') }}" class="btn-back">← Kembali ke Dashboard</a>
</div>

@if(session('success'))
    <div class="alert-box alert-success">
        ✅ {{ session('success') }}
    </div>
@endif

<div class="status-card">
    <div class="status-card-header">
        <h3>Daftar Pengajuan Saya</h3>
    </div>

    @if($pengajuan->count() > 0)
        @foreach($pengajuan as $p)
            <a href="{{ route('user.status.detail', $p->id) }}" class="pengajuan-item">
                <div class="pengajuan-image">
                    @if($p->barang && $p->barang->gambar)
                        <img src="{{ asset('storage/' . $p->barang->gambar) }}" alt="{{ $p->barang->nama_barang }}">
                    @else
                        <div class="pengajuan-image-placeholder">📦</div>
                    @endif
                </div>
                <div class="pengajuan-info">
                    <h4>{{ $p->barang->nama_barang ?? 'Barang tidak ditemukan' }}</h4>
                    <div class="kategori">{{ $p->barang->kategori->nama_kategori ?? '-' }}</div>
                    <div class="tanggal">
                        <strong>Pinjam:</strong> {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->locale('id')->isoFormat('DD MMMM YYYY') }} | 
                        <strong>Kembali:</strong> {{ \Carbon\Carbon::parse($p->tanggal_kembali)->locale('id')->isoFormat('DD MMMM YYYY') }}
                    </div>
                </div>
                <div class="pengajuan-status">
                    <span class="status-badge status-{{ $p->status }}">
                        {{ $p->status === 'pending' ? 'Menunggu' : ($p->status === 'disetujui' ? 'Disetujui' : ($p->status === 'ditolak' ? 'Ditolak' : 'Dikembalikan')) }}
                    </span>
                </div>
            </a>
        @endforeach
    @else
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
            </svg>
            <h3>Belum Ada Pengajuan</h3>
            <p>Anda belum mengajukan peminjaman barang. Silakan pilih barang dari dashboard untuk mengajukan peminjaman.</p>
        </div>
    @endif
</div>

<!-- ===== MODAL POPUP PERHATIAN MENUNGGU PERSETUJUAN ===== -->
<div class="modal-overlay {{ (session('show_peminjaman_modal') || session('success')) ? 'active' : '' }}" id="noticePeminjamanModal">
    <div class="modal-box-notice">
        <div class="modal-header-blue">
            <h3>PERHATIAN!!</h3>
        </div>
        <div class="modal-body-notice">
            <div class="modal-notice-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="#1f2937" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 8v4l3 3m6-3a9 9 0 1 1-2.636-6.364M21 3v5h-5"/>
                </svg>
            </div>
            <p class="modal-notice-text">
                ketika anda meminjam barang, anda harus menunggu persetujuan admin!!!
            </p>
        </div>
        <div class="modal-footer-notice">
            <button type="button" onclick="closeNoticeModal()" class="btn-tutup-notice">TUTUP</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function closeNoticeModal() {
        const modal = document.getElementById('noticePeminjamanModal');
        if (modal) {
            modal.classList.remove('active');
        }
    }

    // Tutup modal saat klik area latar belakang
    const modalNotice = document.getElementById('noticePeminjamanModal');
    if (modalNotice) {
        modalNotice.addEventListener('click', function(e) {
            if (e.target === this) {
                closeNoticeModal();
            }
        });
    }

    // Tutup modal saat tekan tombol Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeNoticeModal();
        }
    });

    // Auto reload status
    setInterval(function() {
        const modal = document.getElementById('noticePeminjamanModal');
        // Jangan auto reload jika modal sedang aktif/terbuka
        if (!modal || !modal.classList.contains('active')) {
            window.location.reload();
        }
    }, 30000);
</script>
@endsection
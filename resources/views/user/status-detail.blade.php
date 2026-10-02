@extends('layouts.dashboard-user')

@section('title', 'Detail Pengajuan')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .page-header h2 {
        color: white;
        font-size: 1.8rem;
        font-weight: 800;
        margin: 0;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: white;
        color: #1e40af;
        padding: 0.8rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    .detail-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }

    .detail-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #f1f5f9;
    }

    .detail-image {
        width: 120px;
        height: 120px;
        border-radius: 15px;
        overflow: hidden;
        flex-shrink: 0;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .detail-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .detail-image-placeholder {
        font-size: 3rem;
    }

    .detail-title h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.3rem;
    }

    .detail-title .kategori {
        font-size: 0.9rem;
        color: #64748b;
    }

    .status-badge-large {
        padding: 0.6rem 1.5rem;
        border-radius: 25px;
        font-size: 1rem;
        font-weight: 700;
        display: inline-block;
        margin-top: 0.5rem;
    }

    .status-pending { background: #fef3c7; color: #92400e; }
    .status-disetujui { background: #dcfce7; color: #166534; }
    .status-ditolak { background: #fee2e2; color: #991b1b; }
    .status-dikembalikan { background: #e0e7ff; color: #3730a3; }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .detail-item {
        margin-bottom: 1rem;
    }

    .detail-item label {
        display: block;
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 0.4rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-item .value {
        font-size: 1rem;
        color: #1e293b;
        font-weight: 500;
        padding: 0.8rem;
        background: #f8fafc;
        border-radius: 8px;
    }

    .detail-item.full-width {
        grid-column: 1 / -1;
    }

    .alasan-box {
        background: #eff6ff;
        border-left: 4px solid #1e40af;
        padding: 1.2rem;
        border-radius: 8px;
        margin-top: 1rem;
    }

    .alasan-box h4 {
        font-size: 0.9rem;
        color: #1e40af;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .alasan-box p {
        color: #1e293b;
        font-size: 0.95rem;
        line-height: 1.6;
        margin: 0;
    }

    .timeline {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #f1f5f9;
    }

    .timeline h4 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1rem;
    }

    .timeline-item {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        padding-left: 1rem;
        border-left: 3px solid #e2e8f0;
    }

    .timeline-item.active {
        border-left-color: #1e40af;
    }

    .timeline-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #e2e8f0;
        flex-shrink: 0;
        margin-top: 0.3rem;
    }

    .timeline-item.active .timeline-dot {
        background: #1e40af;
    }

    .timeline-content {
        flex: 1;
    }

    .timeline-content .time {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 0.2rem;
    }

    .timeline-content .desc {
        font-size: 0.95rem;
        color: #1e293b;
        font-weight: 500;
    }

    .alert-box {
        padding: 1rem 1.3rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        font-weight: 500;
        background: white;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .alert-info { color: #1e40af; border-left: 4px solid #3b82f6; }
    .alert-warning { color: #92400e; border-left: 4px solid #f59e0b; }
    .alert-error { color: #991b1b; border-left: 4px solid #dc2626; }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        .detail-header { flex-direction: column; align-items: flex-start; }
        .detail-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <h2>📋 Detail Pengajuan</h2>
    <a href="{{ route('user.status') }}" class="btn-back">← Kembali ke Status</a>
</div>

@if($pengajuan->status === 'pending')
    <div class="alert-box alert-warning">
        ⏳ Pengajuan Anda sedang menunggu persetujuan admin. Silakan cek kembali secara berkala.
    </div>
@elseif($pengajuan->status === 'disetujui')
    <div class="alert-box alert-info" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            ✅ Pengajuan Anda telah disetujui! Silakan kembalikan barang tepat waktu jika sudah selesai digunakan.
        </div>
        <a href="{{ route('user.pengembalian.create', $pengajuan->id) }}" style="background: #10b981; color: white; padding: 0.6rem 1.2rem; border-radius: 10px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.9rem; box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);">
            📦 Kembalikan Barang Sekarang
        </a>
    </div>
@elseif($pengajuan->status === 'ditolak')
    <div class="alert-box alert-error">
        ❌ Pengajuan Anda ditolak. {{ $pengajuan->alasan_penolakan ? 'Alasan: ' . $pengajuan->alasan_penolakan : '' }}
    </div>
@elseif($pengajuan->status === 'selesai')
    <div class="alert-box alert-success">
        ✅ Peminjaman ini telah selesai dan barang telah berhasil dikembalikan!
    </div>
@endif

<div class="detail-card">
    <div class="detail-header">
        <div class="detail-image">
            @if($pengajuan->barang && $pengajuan->barang->gambar)
                <img src="{{ asset('storage/' . $pengajuan->barang->gambar) }}" alt="{{ $pengajuan->barang->nama_barang }}">
            @else
                <div class="detail-image-placeholder"></div>
            @endif
        </div>
        <div class="detail-title">
            <h3>{{ $pengajuan->barang->nama_barang ?? 'Barang tidak ditemukan' }}</h3>
            <div class="kategori">{{ $pengajuan->barang->kategori->nama_kategori ?? '-' }}</div>
            <span class="status-badge-large status-{{ $pengajuan->status }}">
                {{ $pengajuan->status === 'pending' ? 'Menunggu Persetujuan' : ($pengajuan->status === 'disetujui' ? 'Disetujui' : ($pengajuan->status === 'ditolak' ? 'Ditolak' : 'Dikembalikan')) }}
            </span>
        </div>
    </div>

    <div class="detail-grid">
        <div class="detail-item">
            <label>Kode Barang</label>
            <div class="value">{{ $pengajuan->barang->kode_barang ?? '-' }}</div>
        </div>
        <div class="detail-item">
            <label>Merk / Model</label>
            <div class="value">{{ $pengajuan->barang->merk_model ?? '-' }}</div>
        </div>
        <div class="detail-item">
            <label>Tanggal Pinjam</label>
            <div class="value">{{ \Carbon\Carbon::parse($pengajuan->tanggal_pinjam)->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</div>
        </div>
        <div class="detail-item">
            <label>Tanggal Kembali</label>
            <div class="value">{{ \Carbon\Carbon::parse($pengajuan->tanggal_kembali)->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</div>
        </div>
        <div class="detail-item">
            <label>No. Telepon</label>
            <div class="value">{{ $pengajuan->no_telepon }}</div>
        </div>
        <div class="detail-item">
            <label>Tanggal Pengajuan</label>
            <div class="value">{{ \Carbon\Carbon::parse($pengajuan->created_at)->locale('id')->isoFormat('dddd, DD MMMM YYYY HH:mm') }} WIB</div>
        </div>

        @if($pengajuan->alasan_keperluan)
        <div class="detail-item full-width">
            <label>Alasan Keperluan Peminjaman</label>
            <div class="alasan-box">
                <p>{{ $pengajuan->alasan_keperluan }}</p>
            </div>
        </div>
        @endif
    </div>

    <div class="timeline">
        <h4>📅 Riwayat Status</h4>
        <div class="timeline-item active">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
                <div class="time">{{ \Carbon\Carbon::parse($pengajuan->created_at)->locale('id')->isoFormat('DD MMMM YYYY, HH:mm') }} WIB</div>
                <div class="desc">Pengajuan dikirim</div>
            </div>
        </div>
        @if($pengajuan->status !== 'pending')
        <div class="timeline-item active">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
                <div class="time">{{ \Carbon\Carbon::parse($pengajuan->updated_at)->locale('id')->isoFormat('DD MMMM YYYY, HH:mm') }} WIB</div>
                <div class="desc">Status diubah menjadi <strong>{{ $pengajuan->status === 'disetujui' ? 'Disetujui' : ($pengajuan->status === 'ditolak' ? 'Ditolak' : 'Dikembalikan') }}</strong></div>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script>
    setInterval(function() {
        window.location.reload();
    }, 30000);
</script>
@endsection
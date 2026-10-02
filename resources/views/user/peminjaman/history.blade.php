@extends('layouts.dashboard-user')

@section('title', 'Riwayat Peminjaman')

@section('styles')
<style>
    .page-container {
        min-height: 100vh;
        padding: 2rem;
    }

    .content-wrapper {
        max-width: 1200px;
        margin: 0 auto;
    }

    .header-section {
        background: white;
        padding: 1.5rem 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-section h1 {
        font-size: 1.8rem;
        color: #111;
        margin: 0;
    }

    .btn-back {
        background: #6c757d;
        color: white;
        padding: 0.7rem 1.5rem;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-block;
    }

    .btn-back:hover {
        background: #5a6268;
        transform: scale(1.05);
    }

    .card-container {
        display: grid;
        gap: 1.5rem;
    }

    .peminjaman-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s;
    }

    .peminjaman-card:hover {
        transform: translateY(-3px);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .item-info-header {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .item-image-thumb {
        max-width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }

    .item-name {
        font-size: 1.3rem;
        font-weight: 700;
        color: #111;
        margin: 0;
    }

    .status-badge {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.85rem;
        color: white;
    }

    .status-pending { background: #FF9800; }
    .status-disetujui { background: #4CAF50; }
    .status-ditolak { background: #f44336; }
    .status-selesai { background: #2196F3; }

    .card-body {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 0.85rem;
        color: #888;
        margin-bottom: 0.3rem;
    }

    .info-value {
        font-size: 1rem;
        color: #333;
        font-weight: 600;
    }

    .catatan-box {
        margin-top: 1rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #6c757d;
    }

    .catatan-box h4 {
        margin: 0 0 0.5rem 0;
        color: #495057;
        font-size: 0.9rem;
    }

    .catatan-box p {
        margin: 0;
        color: #6c757d;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 16px;
    }

    .empty-state h3 {
        color: #666;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #999;
    }

    @media (max-width: 768px) {
        .card-body {
            grid-template-columns: 1fr;
        }
        .header-section {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.8rem;
        }
    }
</style>
@endsection

@section('content')

<div class="page-container">
    <div class="content-wrapper">
        
        <div class="header-section">
            <h1>📋 Riwayat Peminjaman Saya</h1>
            <a href="{{ route('user.dashboard') }}" class="btn-back">← Kembali ke Dashboard</a>
        </div>

        @if($peminjaman->count() > 0)
            <div class="card-container">
                @foreach($peminjaman as $p)
                <div class="peminjaman-card">
                    <div class="card-header">
                        <div class="item-info-header">
                            @if($p->barang && $p->barang->gambar)
                                <img src="{{ asset('storage/' . $p->barang->gambar) }}" 
                                     alt="{{ $p->nama_barang }}" 
                                     class="item-image-thumb"
                                     style="max-width: 100px; border-radius: 8px;">
                            @endif
                            <h3 class="item-name">{{ $p->nama_barang }}</h3>
                        </div>
                        <span class="status-badge status-{{ $p->status }}">
                            @if($p->status === 'pending')
                                ⏳ Menunggu Persetujuan
                            @elseif($p->status === 'disetujui')
                                ✅ Disetujui
                            @elseif($p->status === 'ditolak')
                                ❌ Ditolak
                            @else
                                ✓ Selesai
                            @endif
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="info-item">
                            <span class="info-label">📅 Tanggal Peminjaman</span>
                            <span class="info-value">{{ $p->tanggal_pinjam->format('d-m-Y') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">📅 Tanggal Pengembalian</span>
                            <span class="info-value">{{ $p->tanggal_kembali->format('d-m-Y') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">📞 No. Telepon</span>
                            <span class="info-value">{{ $p->no_telepon }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">👤 Peminjam</span>
                            <span class="info-value">{{ $p->nama_peminjam }}</span>
                        </div>
                    </div>

                    @if($p->catatan_admin)
                    <div class="catatan-box">
                        <h4>Catatan dari Admin:</h4>
                        <p>{!! nl2br(e($p->catatan_admin)) !!}</p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <h3>Belum Ada Peminjaman</h3>
                <p>Anda belum pernah mengajukan peminjaman barang.</p>
                <a href="{{ route('user.dashboard') }}" class="btn-back" style="display: inline-block; margin-top: 1rem;">
                    Ajukan Peminjaman →
                </a>
            </div>
        @endif

    </div>
</div>

@endsection
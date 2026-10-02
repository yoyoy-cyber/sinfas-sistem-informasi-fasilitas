@extends('layouts.dashboard-user')

@section('title', $kategori->nama_kategori)

@section('styles')
<style>
    .page-container {
        min-height: 100vh;
        padding: 2rem;
    }

    .content-wrapper {
        max-width: 1400px;
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
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .header-section h1::before {
        content: '';
        background: linear-gradient(135deg, #30AFFF, #1976D2);
        width: 6px;
        height: 32px;
        border-radius: 4px;
        display: inline-block;
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

    .items-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }

    .item-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0,0,0,0.06);
        transition: transform 0.3s, box-shadow 0.3s;
        display: flex;
        flex-direction: column;
    }

    .item-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 14px 35px rgba(0,0,0,0.14);
    }

    .item-header {
        padding: 1.2rem 1.2rem 0.6rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .item-name {
        font-size: 1.05rem;
        font-weight: 700;
        color: #111;
        display: block;
    }

    .item-kategori {
        font-size: 0.75rem;
        color: #888;
        margin-top: 0.2rem;
    }

    .status-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        color: white;
    }

    .status-badge.tersedia { background: #4CAF50; }
    .status-badge.dipinjam { background: #FF9800; }
    .status-badge.rusak { background: #f44336; }

    .item-image {
        width: 100%;
        height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        background: #f9fbfd;
    }

    .item-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 10px;
    }

    .item-image-placeholder {
        width: 75px;
        height: 75px;
        background: #eef2f6;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
    }

    .item-info {
        padding: 0.5rem 1.2rem 1rem;
        font-size: 0.85rem;
        color: #666;
    }

    .item-info p {
        margin: 0.25rem 0;
    }

    .item-footer {
        padding: 0 1.2rem 1.2rem;
        margin-top: auto;
    }

    .btn-pinjam {
        width: 100%;
        padding: 0.75rem;
        background: #30AFFF;
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 0.88rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.3s, transform 0.2s;
        text-decoration: none;
        text-align: center;
        display: block;
        box-shadow: 0 4px 12px rgba(48, 175, 255, 0.3);
    }

    .btn-pinjam:hover:not(:disabled) {
        background: #1a8fe0;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(48, 175, 255, 0.4);
    }

    .btn-pinjam:disabled {
        background: #cbd5e1;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.06);
    }

    .empty-state h3 {
        color: #666;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #999;
    }

    @media (max-width: 1024px) {
        .items-grid { grid-template-columns: repeat(3, 1fr); }
    }

    @media (max-width: 768px) {
        .items-grid { grid-template-columns: repeat(2, 1fr); }
        .header-section { flex-direction: column; gap: 1rem; text-align: center; }
    }

    @media (max-width: 480px) {
        .items-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

<div class="page-container">
    <div class="content-wrapper">
        
        <div class="header-section">
            <h1>{{ $kategori->nama_kategori }} <span style="font-size: 0.9rem; color: #888;">({{ $barangs->count() }} barang)</span></h1>
            <a href="{{ route('user.dashboard') }}" class="btn-back">← Kembali ke Dashboard</a>
        </div>

        @if($barangs->count() > 0)
            <div class="items-grid">
                @foreach($barangs as $barang)
                    <div class="item-card">
                        <div class="item-header">
                            <div>
                                <span class="item-name">{{ $barang->nama_barang }}</span>
                                <div class="item-kategori">{{ $kategori->nama_kategori }}</div>
                            </div>
                            <span class="status-badge {{ $barang->status }}">
                                {{ ucfirst($barang->status) }}
                            </span>
                        </div>

                        <div class="item-image">
                            @if(!empty($barang->gambar))
                                <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}">
                            @else
                                <div class="item-image-placeholder">📦</div>
                            @endif
                        </div>

                        <div class="item-info">
                            <p><strong>Kode:</strong> {{ $barang->kode_barang }}</p>
                            <p><strong>Merk:</strong> {{ $barang->merk_model ?? '-' }}</p>
                            <p><strong>Tersedia:</strong> {{ $barang->jumlah_baik }} unit</p>
                        </div>

                        <div class="item-footer">
                            @if($barang->status === 'tersedia')
                                <a href="{{ route('user.peminjaman.create', $barang->kode_barang) }}" class="btn-pinjam">
                                    Ajukan Peminjaman
                                </a>
                            @else
                                <button type="button" class="btn-pinjam" disabled>Tidak Tersedia</button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <h3>Tidak ada barang di kategori ini</h3>
                <p>Belum ada fasilitas {{ $kategori->nama_kategori }} yang tersedia saat ini.</p>
                <a href="{{ route('user.dashboard') }}" class="btn-back" style="display: inline-block; margin-top: 1rem;">
                    Kembali ke Dashboard →
                </a>
            </div>
        @endif

    </div>
</div>

@endsection
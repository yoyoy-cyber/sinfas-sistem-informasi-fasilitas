@extends('layouts.admin-sarana')

@section('title', 'Detail Barang')

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
        background: white;
        color: #1e40af;
        border: none;
        padding: 0.8rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    .detail-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .detail-section {
        margin-bottom: 2rem;
    }

    .detail-section h3 {
        font-size: 1.2rem;
        color: #1e293b;
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 0.8rem;
        border-bottom: 2px solid #f1f5f9;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .detail-item {
        margin-bottom: 1.2rem;
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

    .gambar-preview {
        max-width: 400px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        margin-top: 0.5rem;
    }

    .status-badge {
        display: inline-block;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .status-tersedia { background: #16a34a; color: white; }
    .status-dipinjam { background: #d97706; color: white; }
    .status-rusak { background: #dc2626; color: white; }

    .action-buttons {
        margin-top: 2rem;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 0.9rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-edit {
        background: #f59e0b;
        color: white;
    }

    .btn-edit:hover {
        background: #d97706;
        transform: translateY(-2px);
    }

    .btn-delete {
        background: #ef4444;
        color: white;
    }

    .btn-delete:hover {
        background: #dc2626;
        transform: translateY(-2px);
    }

    .btn-back-list {
        background: #6b7280;
        color: white;
    }

    .btn-back-list:hover {
        background: #4b5563;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <h2> Detail Barang</h2>
    <a href="{{ route('admin.barang.index') }}" class="btn-back">← Kembali</a>
</div>

<div class="detail-card">
    <div class="detail-section">
        <h3>Informasi Dasar</h3>
        <div class="detail-grid">
            <div class="detail-item">
                <label>Kode Barang</label>
                <div class="value">{{ $barang->kode_barang }}</div>
            </div>
            <div class="detail-item">
                <label>Nama Barang</label>
                <div class="value">{{ $barang->nama_barang }}</div>
            </div>
            <div class="detail-item">
                <label>Kategori</label>
                <div class="value">{{ $barang->kategori->nama_kategori ?? '-' }}</div>
            </div>
            <div class="detail-item">
                <label>Merk / Model</label>
                <div class="value">{{ $barang->merk_model ?? '-' }}</div>
            </div>
            <div class="detail-item">
                <label>Status</label>
                <div class="value">
                    <span class="status-badge status-{{ $barang->status }}">
                        {{ ucfirst($barang->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="detail-section">
        <h3>Gambar Barang</h3>
        @if($barang->gambar)
            <img src="{{ asset('storage/' . $barang->gambar) }}" 
                 alt="{{ $barang->nama_barang }}" 
                 class="gambar-preview"
                 style="max-width: 100%; height: auto;">
        @else
            <div style="padding: 2rem; background: #f8fafc; border-radius: 12px; text-align: center; color: #94a3b8;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📦</div>
                <p>Tidak ada gambar</p>
            </div>
        @endif
    </div>

    <div class="detail-section">
        <h3>Informasi Jumlah</h3>
        <div class="detail-grid">
            <div class="detail-item">
                <label>Jumlah Baik</label>
                <div class="value" style="color: #16a34a; font-weight: 700;">{{ $barang->jumlah_baik }} unit</div>
            </div>
            <div class="detail-item">
                <label>Sedang Dipinjam</label>
                <div class="value" style="color: #d97706; font-weight: 700;">{{ $barang->jumlah_kurang_baik }} unit</div>
            </div>
            <div class="detail-item">
                <label>Rusak Berat</label>
                <div class="value" style="color: #dc2626; font-weight: 700;">{{ $barang->jumlah_rusak_berat }} unit</div>
            </div>
        </div>
    </div>

    <div class="detail-section">
        <h3>Informasi Teknis</h3>
        <div class="detail-grid">
            <div class="detail-item">
                <label>No. Seri Pabrik</label>
                <div class="value">{{ $barang->no_seri_pabrik ?? '-' }}</div>
            </div>
            <div class="detail-item">
                <label>Ukuran / Dimensi</label>
                <div class="value">{{ $barang->ukuran_dimensi ?? '-' }}</div>
            </div>
            <div class="detail-item">
                <label>Bahan</label>
                <div class="value">{{ $barang->bahan ?? '-' }}</div>
            </div>
            <div class="detail-item">
                <label>Tahun Pembelian</label>
                <div class="value">{{ $barang->tahun_pembelian ?? '-' }}</div>
            </div>
            @if($barang->keterangan)
            <div class="detail-item full-width">
                <label>Keterangan</label>
                <div class="value" style="white-space: pre-wrap;">{{ $barang->keterangan }}</div>
            </div>
            @endif
        </div>
    </div>

    <div class="action-buttons">
        <a href="{{ route('admin.barang.edit', $barang->kode_barang) }}" class="btn-action btn-edit">
            ️ Edit Barang
        </a>
        <button type="button" class="btn-action btn-delete" onclick="openDeleteModal('{{ route('admin.barang.destroy', $barang->kode_barang) }}', '{{ addslashes($barang->nama_barang) }}')">
            🗑️ Hapus Barang
        </button>
        <a href="{{ route('admin.barang.index') }}" class="btn-action btn-back-list">
             Kembali ke Daftar
        </a>
    </div>
</div>

@endsection
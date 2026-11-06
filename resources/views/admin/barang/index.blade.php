@extends('layouts.admin-sarana')

@section('title', 'Data Barang')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
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
    }

    .page-title-section .icon { font-size: 1.8rem; }

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

    .content-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f5f9;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .card-header h3 {
        font-size: 1.3rem;
        color: #1e293b;
        font-weight: 700;
    }

    .btn-tambah {
        background: #1e40af;
        color: white;
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
        white-space: nowrap;
    }

    .btn-tambah:hover {
        background: #1e3a8a;
        transform: translateY(-2px);
    }

    /* ===== DESKTOP TABLE ===== */
    .table-container { overflow-x: auto; }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead { background: #f8fafc; }

    th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #64748b;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    td {
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
        color: #334155;
        vertical-align: middle;
    }

    tbody tr:hover { background: #f8fafc; }

    .status-badge {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        color: white;
        display: inline-block;
    }

    .status-tersedia { background: #16a34a; }
    .status-dipinjam { background: #d97706; }
    .status-rusak { background: #dc2626; }

    .action-buttons { display: flex; gap: 0.5rem; }

    .btn-action {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-action:hover { transform: translateY(-2px); }
    .btn-action svg { width: 18px; height: 18px; fill: currentColor; }

    .btn-detail { background: #3b82f6; color: white; }
    .btn-edit { background: #f59e0b; color: white; }
    .btn-hapus { background: #ef4444; color: white; }

    .row-highlight {
        background-color: #eff6ff !important;
        animation: highlightPulse 2.5s ease-in-out infinite alternate;
    }

    @keyframes highlightPulse {
        from { background-color: #eff6ff; }
        to { background-color: #dbeafe; }
    }

    .badge-highlight {
        background: #2563eb;
        color: white;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.2rem 0.55rem;
        border-radius: 12px;
        margin-left: 0.4rem;
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.3);
    }

    /* ===== MOBILE CARD VIEW ===== */
    .mobile-cards { display: none; }

    .barang-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.2rem;
        margin-bottom: 1rem;
        background: #fafafa;
        transition: all 0.2s;
    }

    .barang-card:last-child { margin-bottom: 0; }

    .barang-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        background: white;
    }

    .barang-card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.8rem;
    }

    .barang-card-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        flex: 1;
        margin-right: 0.5rem;
        line-height: 1.3;
    }

    .barang-card-kode {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 0.2rem;
    }

    .barang-card-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin-bottom: 0.9rem;
    }

    .meta-chip {
        background: #f1f5f9;
        color: #475569;
        font-size: 0.78rem;
        font-weight: 500;
        padding: 0.3rem 0.7rem;
        border-radius: 20px;
    }

    .barang-card-stok {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.5rem;
        margin-bottom: 1rem;
        text-align: center;
    }

    .stok-item {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.6rem 0.4rem;
    }

    .stok-number {
        font-size: 1.2rem;
        font-weight: 800;
    }

    .stok-label {
        font-size: 0.68rem;
        color: #94a3b8;
        margin-top: 0.15rem;
    }

    .barang-card-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action-mobile {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        padding: 0.6rem 0.5rem;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-action-mobile svg {
        width: 14px;
        height: 14px;
        fill: currentColor;
        flex-shrink: 0;
    }

    .btn-action-mobile:hover { transform: translateY(-1px); opacity: 0.9; }

    /* ===== ALERT ===== */
    .alert-box {
        padding: 1rem 1.3rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        font-weight: 500;
        background: white;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .alert-success { color: #166534; border-left: 4px solid #16a34a; }
    .alert-error { color: #991b1b; border-left: 4px solid #dc2626; }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #94a3b8;
    }

    /* ===== SEARCH BAR ===== */
    .search-section {
        background: #f8fafc;
        border-radius: 16px;
        padding: 0.85rem 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        margin-bottom: 1.2rem;
        border: 1.5px solid #e2e8f0;
        transition: border-color 0.2s;
    }

    .search-section:focus-within {
        border-color: #2563eb;
        background: white;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .search-section svg {
        width: 20px;
        height: 20px;
        fill: #94a3b8;
        flex-shrink: 0;
    }

    .search-section input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 0.92rem;
        color: #334155;
        background: transparent;
    }

    .search-section input::placeholder {
        color: #94a3b8;
    }

    /* ===== KATEGORI FILTER & SORT BAR ===== */
    .filter-bar-container {
        background: #f8fafc;
        border-radius: 16px;
        padding: 1.2rem 1.4rem;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
        border: 1.5px solid #e2e8f0;
    }

    .filter-group {
        flex: 1;
        min-width: 260px;
    }

    .sort-group {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .kategori-filter-label {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 700;
        margin-bottom: 0.6rem;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .kategori-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .kategori-tab {
        padding: 0.55rem 1.1rem;
        border-radius: 25px;
        border: 1.5px solid #cbd5e1;
        background: white;
        color: #475569;
        font-weight: 600;
        font-size: 0.88rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .kategori-tab:hover {
        border-color: #1e40af;
        color: #1e40af;
        background: #eff6ff;
    }

    .kategori-tab.active {
        background: #1e40af;
        color: white;
        border-color: #1e40af;
        box-shadow: 0 2px 8px rgba(30, 64, 175, 0.3);
    }

    .kategori-tab .count {
        margin-left: 0.3rem;
        font-size: 0.8rem;
        opacity: 0.85;
    }

    .sort-select {
        padding: 0.65rem 1.2rem;
        border-radius: 25px;
        border: 2px solid #2563eb;
        background: white;
        color: #1e293b;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        outline: none;
        transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.15);
    }

    .sort-select:hover, .sort-select:focus {
        border-color: #1d4ed8;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .table-container { display: none; }
        .mobile-cards { display: block; }

        .content-card { padding: 1.2rem; }

        .page-title-section h2 { font-size: 1.4rem; }

        .filter-bar-container {
            padding: 1rem;
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .filter-group {
            min-width: 100%;
            width: 100%;
        }

        .kategori-tabs {
            flex-wrap: nowrap;
            overflow-x: auto;
            padding-bottom: 0.5rem;
            -webkit-overflow-scrolling: touch;
        }

        .kategori-tab {
            flex-shrink: 0;
            white-space: nowrap;
        }

        .sort-group {
            width: 100%;
        }

        .sort-select {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div class="page-title-section">
        <span class="icon">📦</span>
        <h2>Data Barang</h2>
    </div>
    <div style="display: flex; gap: 0.6rem; flex-wrap: wrap;">
        <a href="{{ route('admin.kategori.index') }}" class="btn-back" style="color: #0284c7;">📁 Kategori Barang</a>
        <a href="{{ route('admin.dashboard') }}" class="btn-back">← Dashboard</a>
    </div>
</div>

@if(session('success'))
    <div class="alert-box alert-success">✅ {{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert-box alert-error">⚠️ {{ session('error') }}</div>
@endif

<div class="content-card">
    <div class="card-header">
        <div>
            <h3>Daftar Semua Barang</h3>
            <p style="color: #64748b; font-size: 0.85rem; margin-top: 0.2rem;">Inventaris fasilitas dan sarana prasarana sekolah</p>
        </div>
        <div style="display: flex; gap: 0.6rem; flex-wrap: wrap;">
            <a href="{{ route('admin.kategori.index') }}" class="btn-tambah" style="background: #0284c7;">📁 Kelola Kategori</a>
            <a href="{{ route('admin.barang.create') }}" class="btn-tambah">+ Tambah Barang</a>
        </div>
    </div>

    @if($barangs->count() > 0)

    <!-- SEARCH BAR -->
    <div class="search-section">
        <svg viewBox="0 0 24 24">
            <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
        </svg>
        <input type="text" placeholder="Cari fasilitas berdasarkan nama, kode, kategori, atau merk..." id="searchInput">
    </div>

    <!-- KATEGORI FILTER & SORT BAR -->
    <div class="filter-bar-container">
        <div class="filter-group">
            <span class="kategori-filter-label">Filter Kategori</span>
            <div class="kategori-tabs">
                <button class="kategori-tab active" data-kategori="semua">
                    Semua <span class="count">({{ $barangs->count() }})</span>
                </button>
                @if(isset($kategoris))
                    @foreach($kategoris as $kategori)
                        @php
                            $count = $barangs->where('id_kategori', $kategori->id_kategori)->count();
                        @endphp
                        @if($count > 0)
                            <button class="kategori-tab" data-kategori="{{ $kategori->id_kategori }}">
                                {{ $kategori->nama_kategori }} <span class="count">({{ $count }})</span>
                            </button>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
        
        <div class="sort-group">
            <span class="kategori-filter-label">Urutkan Berdasarkan</span>
            <select id="sortSelect" class="sort-select">
                <option value="terbaru">✨ Terbaru Ditambahkan / Diubah</option>
                <option value="populer">🔥 Paling Sering Dipinjam</option>
                <option value="nama">🔤 Nama Barang (A-Z)</option>
            </select>
        </div>
    </div>

    {{-- ===== DESKTOP TABLE ===== --}}
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Merk</th>
                    <th>Baik</th>
                    <th>Dipinjam</th>
                    <th>Rusak</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="barangTableBody">
                @foreach($barangs as $barang)
                @php
                    $isHighlighted = (session('highlight_id') && session('highlight_id') == $barang->kode_barang) || ($loop->first && session('success'));
                @endphp
                <tr class="barang-row {{ $isHighlighted ? 'row-highlight' : '' }}"
                    data-name="{{ strtolower($barang->nama_barang) }}"
                    data-kode="{{ strtolower($barang->kode_barang) }}"
                    data-kategori-id="{{ $barang->id_kategori }}"
                    data-kategori-name="{{ strtolower($barang->kategori->nama_kategori ?? '') }}"
                    data-merk="{{ strtolower($barang->merk_model ?? '') }}"
                    data-updated="{{ $barang->updated_at ? $barang->updated_at->timestamp : 0 }}"
                    data-borrowed="{{ $barang->peminjaman_requests_count ?? 0 }}">
                    <td>
                        <strong>{{ $barang->kode_barang }}</strong>
                        @if($isHighlighted)
                            <span class="badge-highlight">✨ Terbaru</span>
                        @endif
                    </td>
                    <td>
                        {{ $barang->nama_barang }}
                    </td>
                    <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $barang->merk_model ?? '-' }}</td>
                    <td>{{ $barang->jumlah_baik }}</td>
                    <td>{{ $barang->jumlah_kurang_baik }}</td>
                    <td>{{ $barang->jumlah_rusak_berat }}</td>
                    <td>
                        <span class="status-badge status-{{ $barang->status }}">
                            {{ ucfirst($barang->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.barang.show', $barang->kode_barang) }}" class="btn-action btn-detail" title="Detail">
                                <svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                            </a>
                            <a href="{{ route('admin.barang.edit', $barang->kode_barang) }}" class="btn-action btn-edit" title="Edit">
                                <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                            </a>
                            <button type="button" class="btn-action btn-hapus" title="Hapus"
                                onclick="openDeleteModal('{{ route('admin.barang.destroy', $barang->kode_barang) }}', '{{ addslashes($barang->nama_barang) }}')">
                                <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ===== MOBILE CARDS ===== --}}
    <div class="mobile-cards" id="barangMobileList">
        @foreach($barangs as $barang)
        @php
            $isHighlighted = (session('highlight_id') && session('highlight_id') == $barang->kode_barang) || ($loop->first && session('success'));
        @endphp
        <div class="barang-card {{ $isHighlighted ? 'row-highlight' : '' }}"
             data-name="{{ strtolower($barang->nama_barang) }}"
             data-kode="{{ strtolower($barang->kode_barang) }}"
             data-kategori-id="{{ $barang->id_kategori }}"
             data-kategori-name="{{ strtolower($barang->kategori->nama_kategori ?? '') }}"
             data-merk="{{ strtolower($barang->merk_model ?? '') }}"
             data-updated="{{ $barang->updated_at ? $barang->updated_at->timestamp : 0 }}"
             data-borrowed="{{ $barang->peminjaman_requests_count ?? 0 }}">
            {{-- Top: nama + status --}}
            <div class="barang-card-top">
                <div>
                    <div class="barang-card-title">
                        {{ $barang->nama_barang }}
                        @if($isHighlighted)
                            <span class="badge-highlight">✨ Terbaru</span>
                        @endif
                    </div>
                    <div class="barang-card-kode">{{ $barang->kode_barang }}</div>
                </div>
                <span class="status-badge status-{{ $barang->status }}">
                    {{ ucfirst($barang->status) }}
                </span>
            </div>

            {{-- Meta chips --}}
            <div class="barang-card-meta">
                @if($barang->kategori)
                    <span class="meta-chip">📁 {{ $barang->kategori->nama_kategori }}</span>
                @endif
                @if($barang->merk_model)
                    <span class="meta-chip">🏷️ {{ $barang->merk_model }}</span>
                @endif
            </div>

            {{-- Stok grid --}}
            <div class="barang-card-stok">
                <div class="stok-item">
                    <div class="stok-number" style="color:#16a34a;">{{ $barang->jumlah_baik }}</div>
                    <div class="stok-label">Baik</div>
                </div>
                <div class="stok-item">
                    <div class="stok-number" style="color:#d97706;">{{ $barang->jumlah_kurang_baik }}</div>
                    <div class="stok-label">Dipinjam</div>
                </div>
                <div class="stok-item">
                    <div class="stok-number" style="color:#dc2626;">{{ $barang->jumlah_rusak_berat }}</div>
                    <div class="stok-label">Rusak</div>
                </div>
            </div>

            {{-- Action buttons --}}
            <div class="barang-card-actions">
                <a href="{{ route('admin.barang.show', $barang->kode_barang) }}"
                   class="btn-action-mobile" style="background:#3b82f6; color:white;">
                    <svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                    Detail
                </a>
                <a href="{{ route('admin.barang.edit', $barang->kode_barang) }}"
                   class="btn-action-mobile" style="background:#f59e0b; color:white;">
                    <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                    Edit
                </a>
                <button type="button"
                        class="btn-action-mobile"
                        style="background:#ef4444; color:white;"
                        onclick="openDeleteModal('{{ route('admin.barang.destroy', $barang->kode_barang) }}', '{{ addslashes($barang->nama_barang) }}')">
                    <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                    Hapus
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <div id="emptySearchResult" style="display: none; text-align: center; padding: 3rem 1rem; color: #94a3b8;">
        <h3 style="color: #1e293b; margin-bottom: 0.5rem; font-size: 1.15rem;">Tidak Ada Barang Ditemukan</h3>
        <p style="font-size: 0.9rem;">Coba gunakan kata kunci pencarian atau filter kategori yang berbeda.</p>
    </div>

    @else
    <div class="empty-state">
        <h3>Belum Ada Data Barang</h3>
        <p>Klik tombol "Tambah Barang" untuk menambahkan data barang baru.</p>
    </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
    const kategoriTabs = document.querySelectorAll('.kategori-tab');
    const tableRows = Array.from(document.querySelectorAll('.barang-row'));
    const mobileCards = Array.from(document.querySelectorAll('.barang-card'));
    const searchInput = document.getElementById('searchInput');
    const sortSelect = document.getElementById('sortSelect');
    const tableBody = document.getElementById('barangTableBody');
    const mobileList = document.getElementById('barangMobileList');
    const emptySearchResult = document.getElementById('emptySearchResult');
    const tableContainer = document.querySelector('.table-container');

    let activeKategori = 'semua';
    let searchQuery = '';

    function filterAndSort() {
        const sortValue = sortSelect ? sortSelect.value : 'terbaru';

        // Sort function
        const sortFn = (a, b) => {
            if (sortValue === 'terbaru') {
                return parseInt(b.getAttribute('data-updated') || 0) - parseInt(a.getAttribute('data-updated') || 0);
            } else if (sortValue === 'populer') {
                return parseInt(b.getAttribute('data-borrowed') || 0) - parseInt(a.getAttribute('data-borrowed') || 0);
            } else if (sortValue === 'nama') {
                return (a.getAttribute('data-name') || '').localeCompare(b.getAttribute('data-name') || '');
            }
            return 0;
        };

        // Sort arrays
        tableRows.sort(sortFn);
        mobileCards.sort(sortFn);

        // Re-append in sorted order & filter
        let visibleCount = 0;

        tableRows.forEach(row => {
            const name = row.getAttribute('data-name') || '';
            const kode = row.getAttribute('data-kode') || '';
            const kategoriId = row.getAttribute('data-kategori-id') || '';
            const kategoriName = row.getAttribute('data-kategori-name') || '';
            const merk = row.getAttribute('data-merk') || '';

            const matchKategori = (activeKategori === 'semua') || (kategoriId === activeKategori);
            const matchSearch = (searchQuery === '') ||
                                name.includes(searchQuery) ||
                                kode.includes(searchQuery) ||
                                kategoriName.includes(searchQuery) ||
                                merk.includes(searchQuery);

            if (matchKategori && matchSearch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }

            if (tableBody) tableBody.appendChild(row);
        });

        mobileCards.forEach(card => {
            const name = card.getAttribute('data-name') || '';
            const kode = card.getAttribute('data-kode') || '';
            const kategoriId = card.getAttribute('data-kategori-id') || '';
            const kategoriName = card.getAttribute('data-kategori-name') || '';
            const merk = card.getAttribute('data-merk') || '';

            const matchKategori = (activeKategori === 'semua') || (kategoriId === activeKategori);
            const matchSearch = (searchQuery === '') ||
                                name.includes(searchQuery) ||
                                kode.includes(searchQuery) ||
                                kategoriName.includes(searchQuery) ||
                                merk.includes(searchQuery);

            if (matchKategori && matchSearch) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }

            if (mobileList) mobileList.appendChild(card);
        });

        if (emptySearchResult) {
            emptySearchResult.style.display = (visibleCount === 0 && tableRows.length > 0) ? 'block' : 'none';
        }
        if (tableContainer) {
            tableContainer.style.display = (visibleCount === 0 && tableRows.length > 0) ? 'none' : '';
        }
    }

    kategoriTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            kategoriTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            activeKategori = this.getAttribute('data-kategori');
            filterAndSort();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            searchQuery = e.target.value.toLowerCase().trim();
            filterAndSort();
        });
    }

    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            filterAndSort();
        });
    }
</script>
@endsection
@extends('layouts.dashboard-user')

@section('title', 'Dashboard')

@section('styles')
<style>
    /* ===== STATS CARDS ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
        font-size: 2rem;
        margin-bottom: 0.8rem;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 0.3rem;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #64748b;
        font-weight: 600;
    }

    /* ===== SEARCH BAR ===== */
    .search-section {
        background: white;
        border-radius: 20px;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .search-section svg {
        width: 22px;
        height: 22px;
        fill: #94a3b8;
        flex-shrink: 0;
    }

    .search-section input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 1rem;
        color: #334155;
        background: transparent;
    }

    .search-section input::placeholder {
        color: #94a3b8;
    }

    /* ===== KATEGORI FILTER & SORT BAR ===== */
    .filter-bar-container {
        background: white;
        border-radius: 20px;
        padding: 1.2rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
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
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 0.8rem;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .kategori-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 0.6rem;
    }

    .kategori-tab {
        padding: 0.6rem 1.2rem;
        border-radius: 25px;
        border: 2px solid #e2e8f0;
        background: white;
        color: #475569;
        font-weight: 600;
        font-size: 0.9rem;
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
        padding: 0.6rem 1.2rem;
        border-radius: 25px;
        border: 2px solid #e2e8f0;
        background: #f8fafc;
        color: #1e293b;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        outline: none;
        transition: all 0.2s;
    }

    .sort-select:hover, .sort-select:focus {
        border-color: #1e40af;
        background: white;
    }

    .item-tags {
        display: flex;
        gap: 0.4rem;
        margin-top: 0.4rem;
        flex-wrap: wrap;
    }

    .badge-tag {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.2rem 0.6rem;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
    }

    .badge-baru {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
    }

    .badge-populer {
        background: #fff7ed;
        color: #ea580c;
        border: 1px solid #fed7aa;
    }

    /* ===== ITEMS GRID ===== */
    .items-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    .item-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
    }

    .item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }

    .item-header {
        padding: 1.2rem 1.2rem 0.6rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .item-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        display: block;
    }

    .item-kategori {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 0.2rem;
    }

    .status-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
    }

    .status-badge.tersedia { background: #16a34a; }
    .status-badge.dipinjam { background: #d97706; }
    .status-badge.rusak { background: #dc2626; }

    .item-image {
        width: 100%;
        height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        background: #f8fafc;
    }

    .item-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 8px;
    }

    .item-image-placeholder {
        width: 75px;
        height: 75px;
        background: #e2e8f0;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
    }

    .item-info {
        padding: 0.8rem 1.2rem;
        font-size: 0.85rem;
        color: #64748b;
    }

    .item-info p {
        margin: 0.25rem 0;
    }

    .item-info strong {
        color: #475569;
    }

    .item-footer {
        padding: 0 1.2rem 1.2rem;
        margin-top: auto;
    }

    .btn-pinjam {
        width: 100%;
        padding: 0.8rem;
        background: #1e40af;
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        text-align: center;
        display: block;
    }

    .btn-pinjam:hover:not(:disabled) {
        background: #1e3a8a;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 64, 175, 0.35);
    }

    .btn-pinjam:disabled {
        background: #cbd5e1;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* ===== ALERT ===== */
    .alert-box {
        padding: 1rem 1.3rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        font-weight: 500;
        font-size: 0.95rem;
        background: white;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .alert-success {
        color: #166534;
        border-left: 4px solid #16a34a;
    }

    .alert-error {
        color: #991b1b;
        border-left: 4px solid #dc2626;
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        background: white;
        border-radius: 20px;
        grid-column: 1 / -1;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .empty-state h3 {
        color: #1e293b;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }

    .empty-state p {
        color: #64748b;
        font-size: 0.95rem;
    }

    /* ===== PAGINATION STYLING ===== */
    .pagination-wrapper {
        margin-top: 2rem;
        margin-bottom: 2rem;
        background: white;
        border-radius: 18px;
        padding: 1.2rem 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        border: 1px solid #e2e8f0;
    }

    .pagination-info {
        font-size: 0.9rem;
        color: #64748b;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        flex-wrap: wrap;
    }

    .pagination-info strong {
        color: #0f172a;
        font-weight: 700;
    }

    .per-page-selector {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.85rem;
        color: #64748b;
    }

    .per-page-select {
        padding: 0.4rem 0.75rem;
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        background: #f8fafc;
        color: #1e293b;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        outline: none;
        transition: all 0.2s;
    }

    .per-page-select:focus, .per-page-select:hover {
        border-color: #2563eb;
        background: white;
    }

    .pagination-controls {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        flex-wrap: wrap;
    }

    .page-btn {
        min-width: 40px;
        height: 40px;
        padding: 0 0.75rem;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        background: white;
        color: #475569;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        user-select: none;
        text-decoration: none;
    }

    .page-btn:hover:not(:disabled):not(.active) {
        border-color: #3b82f6;
        color: #1e40af;
        background: #eff6ff;
        transform: translateY(-2px);
        box-shadow: 0 3px 10px rgba(59, 130, 246, 0.15);
    }

    .page-btn.active {
        background: linear-gradient(135deg, #1e40af, #2563eb);
        color: white;
        border-color: #1e40af;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
        font-weight: 700;
    }

    .page-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #94a3b8;
        transform: none !important;
        box-shadow: none !important;
    }

    .page-btn.btn-nav {
        padding: 0 0.9rem;
        font-weight: 600;
    }

    .page-ellipsis {
        min-width: 32px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-weight: 700;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1200px) {
        .items-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 768px) {
        .stats-grid { 
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
            margin-bottom: 1.2rem;
        }

        .stat-card {
            padding: 1rem 0.5rem;
            border-radius: 16px;
        }

        .stat-icon {
            font-size: 1.5rem;
            margin-bottom: 0.4rem;
        }

        .stat-number {
            font-size: 1.5rem;
        }

        .stat-label {
            font-size: 0.75rem;
        }

        .search-section {
            padding: 0.8rem 1.2rem;
            border-radius: 16px;
        }

        .filter-bar-container {
            padding: 1rem;
            border-radius: 16px;
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
            scrollbar-width: thin;
        }

        .kategori-tabs::-webkit-scrollbar {
            height: 4px;
        }

        .kategori-tabs::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .kategori-tab {
            flex-shrink: 0;
            white-space: nowrap;
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
        }

        .sort-group {
            width: 100%;
        }

        .sort-select {
            width: 100%;
        }

        .items-grid { 
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .pagination-wrapper {
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 1.2rem 1rem;
            gap: 1rem;
            border-radius: 16px;
        }

        .pagination-info {
            flex-direction: column;
            gap: 0.6rem;
            width: 100%;
        }

        .pagination-controls {
            justify-content: center;
            width: 100%;
            gap: 0.35rem;
        }

        .page-btn {
            min-width: 38px;
            height: 38px;
            padding: 0 0.6rem;
            font-size: 0.85rem;
        }

        .page-btn.btn-nav span.nav-text {
            display: none; /* Hide 'Sebelumnya' / 'Berikutnya' text on mobile, keep arrows */
        }
    }

    @media (max-width: 480px) {
        .stats-grid { 
            grid-template-columns: 1fr;
            gap: 0.6rem;
        }

        .stat-card {
            padding: 0.9rem;
            display: flex;
            align-items: center;
            text-align: left;
            gap: 1rem;
        }

        .stat-icon {
            margin-bottom: 0;
            font-size: 1.8rem;
        }

        .stat-number {
            font-size: 1.3rem;
            line-height: 1.1;
        }

        .stat-label {
            font-size: 0.8rem;
        }

        .pagination-controls {
            gap: 0.25rem;
        }

        .page-btn {
            min-width: 34px;
            height: 34px;
            padding: 0 0.4rem;
            font-size: 0.8rem;
            border-radius: 8px;
        }

        .page-ellipsis {
            min-width: 20px;
        }
    }
</style>
@endsection

@section('content')

<!-- ALERT -->
@if(session('success'))
    <div class="alert-box alert-success">✅ {{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert-box alert-error">⚠️ {{ session('error') }}</div>
@endif

<!-- STATS CARDS -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-number">{{ $stats['tersedia'] ?? 0 }}</div>
        <div class="stat-label">Item Tersedia</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🔄</div>
        <div class="stat-number">{{ $stats['dipinjam'] ?? 0 }}</div>
        <div class="stat-label">Sedang Dipinjam</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">⚠️</div>
        <div class="stat-number">{{ $stats['rusak'] ?? 0 }}</div>
        <div class="stat-label">Item Rusak</div>
    </div>
</div>

<!-- SEARCH -->
<div class="search-section">
    <svg viewBox="0 0 24 24">
        <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
    </svg>
    <input type="text" placeholder="Cari fasilitas berdasarkan nama atau kategori..." id="searchInput">
</div>

<!-- KATEGORI FILTER & SORT BAR -->
<div class="filter-bar-container">
    <div class="filter-group">
        <span class="kategori-filter-label">Filter Kategori</span>
        <div class="kategori-tabs">
            <button class="kategori-tab {{ !isset($id_kategori) ? 'active' : '' }}" data-kategori="semua">
                Semua <span class="count">({{ $barangs->count() }})</span>
            </button>
            @foreach($kategoris as $kategori)
                @php
                    $count = $barangs->where('id_kategori', $kategori->id_kategori)->count();
                @endphp
                @if($count > 0)
                    <button class="kategori-tab {{ (isset($id_kategori) && $id_kategori == $kategori->id_kategori) ? 'active' : '' }}" data-kategori="{{ $kategori->id_kategori }}">
                        {{ $kategori->nama_kategori }} <span class="count">({{ $count }})</span>
                    </button>
                @endif
            @endforeach
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

<!-- ITEMS GRID -->
<div class="items-grid" id="itemsGrid">
    @forelse($barangs as $barang)
        @php
            $isNew = $loop->first || ($barang->updated_at && $barang->updated_at->diffInDays(now()) <= 7);
            $borrowCount = isset($barang->peminjaman_requests_count) ? (int)$barang->peminjaman_requests_count : (int)$barang->total_dipinjam;
            $isPopular = $borrowCount > 0;
        @endphp
        <div class="item-card" 
             data-name="{{ strtolower($barang->nama_barang) }}" 
             data-kategori-id="{{ $barang->id_kategori }}"
             data-kategori-name="{{ strtolower($barang->kategori->nama_kategori ?? '') }}"
             data-updated="{{ $barang->updated_at ? $barang->updated_at->timestamp : 0 }}"
             data-borrowed="{{ $borrowCount }}">
            
            <div class="item-header">
                <div>
                    <span class="item-name">{{ $barang->nama_barang }}</span>
                    <div class="item-kategori">{{ $barang->kategori->nama_kategori ?? 'Tanpa Kategori' }}</div>
                    <div class="item-tags">
                        @if($isNew)
                            <span class="badge-tag badge-baru">✨ Terbaru</span>
                        @endif
                        @if($isPopular)
                            <span class="badge-tag badge-populer">🔥 {{ $borrowCount }}x Dipinjam</span>
                        @endif
                    </div>
                </div>
                <span class="status-badge {{ $barang->status }}">
                    {{ ucfirst($barang->status) }}
                </span>
            </div>

            <div class="item-image">
                @if($barang->gambar)
                    <img src="{{ asset('storage/' . $barang->gambar) }}" 
                         alt="{{ $barang->nama_barang }}"
                         style="max-width: 100%; max-height: 100%; object-fit: contain;">
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
    @empty
        <div class="empty-state">
            <h3>Tidak ada barang ditemukan</h3>
            <p>Belum ada data fasilitas yang tersedia saat ini.</p>
        </div>
    @endforelse

    <div class="empty-state" id="emptySearchResult" style="display: none;">
        <h3>Tidak ada barang ditemukan</h3>
        <p>Coba kata kunci pencarian yang berbeda</p>
    </div>
</div>

<!-- PAGINATION WRAPPER -->
<div class="pagination-wrapper" id="paginationWrapper" style="display: none;">
    <div class="pagination-info">
        <span>Menampilkan <strong id="pageStart">0</strong>-<strong id="pageEnd">0</strong> dari <strong id="pageTotal">0</strong> fasilitas</span>
        <div class="per-page-selector">
            <label for="perPageSelect">Tampilkan:</label>
            <select id="perPageSelect" class="per-page-select">
                <option value="6" selected>6 per halaman</option>
                <option value="9">9 per halaman</option>
                <option value="12">12 per halaman</option>
                <option value="18">18 per halaman</option>
            </select>
        </div>
    </div>
    
    <div class="pagination-controls" id="paginationControls">
        <!-- Injected via JavaScript -->
    </div>
</div>

@endsection

@section('scripts')
<script>
    const kategoriTabs = document.querySelectorAll('.kategori-tab');
    const itemCards = Array.from(document.querySelectorAll('.item-card'));
    const emptySearchResult = document.getElementById('emptySearchResult');
    const searchInput = document.getElementById('searchInput');
    const sortSelect = document.getElementById('sortSelect');
    const paginationWrapper = document.getElementById('paginationWrapper');
    const paginationControls = document.getElementById('paginationControls');
    const perPageSelect = document.getElementById('perPageSelect');
    const pageStartEl = document.getElementById('pageStart');
    const pageEndEl = document.getElementById('pageEnd');
    const pageTotalEl = document.getElementById('pageTotal');
    const itemsGrid = document.getElementById('itemsGrid');
    
    let activeKategori = '{{ $id_kategori ?? "semua" }}';
    let searchQuery = '';
    let currentPage = 1;
    let itemsPerPage = parseInt(perPageSelect ? perPageSelect.value : 6) || 6;

    function getFilteredAndSortedCards() {
        // 1. Filter
        const filtered = itemCards.filter(item => {
            const kategoriId = item.getAttribute('data-kategori-id');
            const name = (item.getAttribute('data-name') || '').toLowerCase();
            const kategoriName = (item.getAttribute('data-kategori-name') || '').toLowerCase();
            
            const matchKategori = (activeKategori === 'semua') || (kategoriId === activeKategori);
            const matchSearch = (searchQuery === '') || 
                                (name.includes(searchQuery)) || 
                                (kategoriName.includes(searchQuery));
            
            return matchKategori && matchSearch;
        });

        // 2. Sort
        const sortValue = sortSelect ? sortSelect.value : 'terbaru';
        filtered.sort((a, b) => {
            if (sortValue === 'terbaru') {
                return parseInt(b.getAttribute('data-updated') || 0) - parseInt(a.getAttribute('data-updated') || 0);
            } else if (sortValue === 'populer') {
                return parseInt(b.getAttribute('data-borrowed') || 0) - parseInt(a.getAttribute('data-borrowed') || 0);
            } else if (sortValue === 'nama') {
                return (a.getAttribute('data-name') || '').localeCompare(b.getAttribute('data-name') || '');
            }
            return 0;
        });

        return filtered;
    }

    function generatePageNumbers(current, total) {
        if (total <= 7) {
            return Array.from({ length: total }, (_, i) => i + 1);
        }
        const pages = [];
        pages.push(1);
        
        let start = Math.max(2, current - 1);
        let end = Math.min(total - 1, current + 1);
        
        if (current <= 3) {
            end = 4;
        } else if (current >= total - 2) {
            start = total - 3;
        }
        
        if (start > 2) {
            pages.push('...');
        }
        
        for (let i = start; i <= end; i++) {
            pages.push(i);
        }
        
        if (end < total - 1) {
            pages.push('...');
        }
        
        pages.push(total);
        return pages;
    }

    function renderView(shouldScroll = false) {
        if (!itemsGrid) return;

        const matchingCards = getFilteredAndSortedCards();
        const totalItems = matchingCards.length;

        // Hide all cards first
        itemCards.forEach(card => {
            card.style.display = 'none';
        });

        if (totalItems === 0) {
            if (emptySearchResult) emptySearchResult.style.display = 'block';
            if (paginationWrapper) paginationWrapper.style.display = 'none';
            return;
        }

        if (emptySearchResult) emptySearchResult.style.display = 'none';
        if (paginationWrapper) paginationWrapper.style.display = 'flex';

        const totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));
        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = Math.min(startIndex + itemsPerPage, totalItems);

        // Display cards on current page & append in correct sorted order
        for (let i = startIndex; i < endIndex; i++) {
            const card = matchingCards[i];
            card.style.display = 'flex';
            itemsGrid.appendChild(card);
        }
        if (emptySearchResult) itemsGrid.appendChild(emptySearchResult);

        // Update info text
        if (pageStartEl) pageStartEl.textContent = startIndex + 1;
        if (pageEndEl) pageEndEl.textContent = endIndex;
        if (pageTotalEl) pageTotalEl.textContent = totalItems;

        // Render pagination controls
        renderPaginationButtons(totalPages);

        if (shouldScroll) {
            const target = document.querySelector('.search-section') || itemsGrid;
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function renderPaginationButtons(totalPages) {
        if (!paginationControls) return;
        paginationControls.innerHTML = '';

        // Previous Button
        const prevBtn = document.createElement('button');
        prevBtn.type = 'button';
        prevBtn.className = 'page-btn btn-nav';
        prevBtn.innerHTML = '&larr; <span class="nav-text">Sebelumnya</span>';
        prevBtn.title = 'Halaman Sebelumnya';
        prevBtn.disabled = (currentPage === 1);
        prevBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                renderView(true);
            }
        });
        paginationControls.appendChild(prevBtn);

        // Numeric Buttons
        const pageNumbers = generatePageNumbers(currentPage, totalPages);
        pageNumbers.forEach(page => {
            if (page === '...') {
                const ellipsis = document.createElement('span');
                ellipsis.className = 'page-ellipsis';
                ellipsis.textContent = '...';
                paginationControls.appendChild(ellipsis);
            } else {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'page-btn' + (page === currentPage ? ' active' : '');
                btn.textContent = page;
                btn.title = 'Halaman ' + page;
                btn.addEventListener('click', () => {
                    if (currentPage !== page) {
                        currentPage = page;
                        renderView(true);
                    }
                });
                paginationControls.appendChild(btn);
            }
        });

        // Next Button
        const nextBtn = document.createElement('button');
        nextBtn.type = 'button';
        nextBtn.className = 'page-btn btn-nav';
        nextBtn.innerHTML = '<span class="nav-text">Berikutnya</span> &rarr;';
        nextBtn.title = 'Halaman Berikutnya';
        nextBtn.disabled = (currentPage === totalPages);
        nextBtn.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                renderView(true);
            }
        });
        paginationControls.appendChild(nextBtn);
    }

    // Event Listeners
    kategoriTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            kategoriTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            activeKategori = this.getAttribute('data-kategori');
            currentPage = 1;
            renderView(false);
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            searchQuery = e.target.value.toLowerCase().trim();
            currentPage = 1;
            renderView(false);
        });
    }

    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            currentPage = 1;
            renderView(false);
        });
    }

    if (perPageSelect) {
        perPageSelect.addEventListener('change', function(e) {
            itemsPerPage = parseInt(e.target.value) || 6;
            currentPage = 1;
            renderView(false);
        });
    }

    // Initial render
    renderView(false);
</script>
@endsection
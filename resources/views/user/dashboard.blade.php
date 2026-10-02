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

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1200px) {
        .items-grid { grid-template-columns: repeat(2, 1fr); }
        .welcome-banner { flex-direction: column; text-align: center; }
        .welcome-illustration { width: 150px; margin-top: 1rem; }
    }

    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: 1fr; }
        .items-grid { grid-template-columns: 1fr; }
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
            <button class="kategori-tab active" data-kategori="semua">
                Semua <span class="count">({{ $barangs->count() }})</span>
            </button>
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
            $isPopular = ($barang->peminjaman_requests_count ?? 0) > 0;
        @endphp
        <div class="item-card" 
             data-name="{{ strtolower($barang->nama_barang) }}" 
             data-kategori-id="{{ $barang->id_kategori }}"
             data-kategori-name="{{ strtolower($barang->kategori->nama_kategori ?? '') }}"
             data-updated="{{ $barang->updated_at ? $barang->updated_at->timestamp : 0 }}"
             data-borrowed="{{ $barang->peminjaman_requests_count ?? 0 }}">
            
            <div class="item-header">
                <div>
                    <span class="item-name">{{ $barang->nama_barang }}</span>
                    <div class="item-kategori">{{ $barang->kategori->nama_kategori ?? 'Tanpa Kategori' }}</div>
                    <div class="item-tags">
                        @if($isNew)
                            <span class="badge-tag badge-baru">✨ Terbaru</span>
                        @endif
                        @if($isPopular)
                            <span class="badge-tag badge-populer">🔥 {{ $barang->peminjaman_requests_count }}x Dipinjam</span>
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

@endsection

@section('scripts')
<script>
    const kategoriTabs = document.querySelectorAll('.kategori-tab');
    const itemCards = document.querySelectorAll('.item-card');
    const emptySearchResult = document.getElementById('emptySearchResult');
    const searchInput = document.getElementById('searchInput');
    const sortSelect = document.getElementById('sortSelect');
    
    let activeKategori = 'semua';
    let searchQuery = '';

    function sortItems() {
        const grid = document.getElementById('itemsGrid');
        if (!grid || itemCards.length === 0) return;

        const cardsArray = Array.from(itemCards);
        const sortValue = sortSelect ? sortSelect.value : 'terbaru';

        cardsArray.sort((a, b) => {
            if (sortValue === 'terbaru') {
                return parseInt(b.getAttribute('data-updated')) - parseInt(a.getAttribute('data-updated'));
            } else if (sortValue === 'populer') {
                return parseInt(b.getAttribute('data-borrowed')) - parseInt(a.getAttribute('data-borrowed'));
            } else if (sortValue === 'nama') {
                return (a.getAttribute('data-name') || '').localeCompare(b.getAttribute('data-name') || '');
            }
            return 0;
        });

        cardsArray.forEach(card => grid.appendChild(card));
        if (emptySearchResult) grid.appendChild(emptySearchResult);
    }

    function filterItems() {
        let visibleCount = 0;
        
        itemCards.forEach(item => {
            const kategoriId = item.getAttribute('data-kategori-id');
            const name = item.getAttribute('data-name') || '';
            const kategoriName = item.getAttribute('data-kategori-name') || '';
            
            const matchKategori = activeKategori === 'semua' || kategoriId === activeKategori;
            const matchSearch = searchQuery === '' || 
                                name.includes(searchQuery) || 
                                kategoriName.includes(searchQuery);
            
            if (matchKategori && matchSearch) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        if (emptySearchResult) {
            emptySearchResult.style.display = (visibleCount === 0 && itemCards.length > 0) ? 'block' : 'none';
        }
    }

    kategoriTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            kategoriTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            activeKategori = this.getAttribute('data-kategori');
            filterItems();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            searchQuery = e.target.value.toLowerCase().trim();
            filterItems();
        });
    }

    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            sortItems();
            filterItems();
        });
    }
</script>
@endsection
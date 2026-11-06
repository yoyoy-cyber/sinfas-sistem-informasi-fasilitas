@extends('layouts.admin-sarana')

@section('title', 'Kategori Barang')

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

    /* ===== STATS GRID ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.2rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 18px;
        padding: 1.2rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .stat-blue { background: #eff6ff; color: #2563eb; }
    .stat-green { background: #f0fdf4; color: #16a34a; }
    .stat-amber { background: #fffbeb; color: #d97706; }

    .stat-info h4 {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.2rem;
    }

    .stat-number {
        font-size: 1.6rem;
        font-weight: 800;
        color: #0f172a;
    }

    /* ===== CONTENT CARD ===== */
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
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(30, 64, 175, 0.25);
    }

    .btn-tambah:hover {
        background: #1e3a8a;
        transform: translateY(-2px);
    }

    /* ===== SEARCH BAR ===== */
    .search-box {
        position: relative;
        margin-bottom: 1.2rem;
    }

    .search-box input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.8rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.92rem;
        outline: none;
        transition: all 0.2s;
        background: #f8fafc;
    }

    .search-box input:focus {
        border-color: #2563eb;
        background: white;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .search-box svg {
        position: absolute;
        left: 0.9rem;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        fill: #94a3b8;
    }

    /* ===== TABLE ===== */
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

    .badge-count {
        background: #eff6ff;
        color: #1e40af;
        border: 1px solid #bfdbfe;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .badge-empty {
        background: #f1f5f9;
        color: #94a3b8;
        border: 1px solid #e2e8f0;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

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
    }

    .btn-action:hover { transform: translateY(-2px); }
    .btn-action svg { width: 18px; height: 18px; fill: currentColor; }

    .btn-edit { background: #f59e0b; color: white; }
    .btn-hapus { background: #ef4444; color: white; }
    .btn-hapus:disabled { background: #e2e8f0; color: #94a3b8; cursor: not-allowed; transform: none; }

    /* ===== MOBILE CARDS ===== */
    .mobile-cards { display: none; }

    .kategori-card {
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.2rem;
        margin-bottom: 1rem;
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .kategori-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.8rem;
    }

    .kategori-card-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1e293b;
    }

    .kategori-card-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 0.8rem;
        padding-top: 0.8rem;
        border-top: 1px solid #f1f5f9;
    }

    /* ===== MODAL ===== */
    .modal-overlay-custom {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.6);
        z-index: 2000;
        backdrop-filter: blur(4px);
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .modal-overlay-custom.active {
        display: flex;
    }

    .modal-card {
        background: white;
        border-radius: 20px;
        width: 100%;
        max-width: 480px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        animation: modalSlide 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes modalSlide {
        from { transform: translateY(20px) scale(0.96); opacity: 0; }
        to { transform: translateY(0) scale(1); opacity: 1; }
    }

    .modal-card-header {
        padding: 1.2rem 1.5rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-card-header h3 {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .btn-close-modal {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #94a3b8;
        cursor: pointer;
        padding: 0.2rem;
        line-height: 1;
        transition: color 0.2s;
    }

    .btn-close-modal:hover { color: #1e293b; }

    .modal-card-body {
        padding: 1.5rem;
    }

    .modal-card-body label {
        display: block;
        font-size: 0.9rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.5rem;
    }

    .modal-card-body input {
        width: 100%;
        padding: 0.85rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.95rem;
        outline: none;
        transition: border-color 0.2s;
    }

    .modal-card-body input:focus {
        border-color: #2563eb;
    }

    .modal-card-footer {
        padding: 1rem 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 0.8rem;
    }

    .btn-modal-cancel {
        background: #f1f5f9;
        color: #475569;
        border: none;
        padding: 0.75rem 1.4rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-modal-cancel:hover { background: #e2e8f0; }

    .btn-modal-save {
        background: #1e40af;
        color: white;
        border: none;
        padding: 0.75rem 1.6rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-modal-save:hover { background: #1e3a8a; }

    .btn-modal-delete {
        background: #dc2626;
        color: white;
        border: none;
        padding: 0.75rem 1.6rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-modal-delete:hover { background: #b91c1c; }

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

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: 1fr; gap: 0.8rem; }
        .table-container { display: none; }
        .mobile-cards { display: block; }
        .content-card { padding: 1.2rem; }
        .page-title-section h2 { font-size: 1.4rem; }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div class="page-title-section">
        <span class="icon">📁</span>
        <h2>Kelola Kategori Barang</h2>
    </div>
    <div style="display: flex; gap: 0.6rem; flex-wrap: wrap;">
        <a href="{{ route('admin.barang.index') }}" class="btn-back">📦 Data Barang</a>
        <a href="{{ route('admin.dashboard') }}" class="btn-back">← Dashboard</a>
    </div>
</div>

@if(session('success'))
    <div class="alert-box alert-success">✅ {{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert-box alert-error">⚠️ {{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="alert-box alert-error">
        <ul style="margin: 0; padding-left: 1.2rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- STATS CARDS -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon stat-blue">📁</div>
        <div class="stat-info">
            <h4>Total Kategori</h4>
            <div class="stat-number">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-green">📦</div>
        <div class="stat-info">
            <h4>Kategori Terisi</h4>
            <div class="stat-number">{{ $stats['terisi'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-amber">🏷️</div>
        <div class="stat-info">
            <h4>Kategori Kosong</h4>
            <div class="stat-number">{{ $stats['kosong'] }}</div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <div>
            <h3>Daftar Kategori Fasilitas</h3>
            <p style="color: #64748b; font-size: 0.85rem; margin-top: 0.2rem;">Kelola seluruh kelompok kategori sarana dan prasarana sekolah</p>
        </div>
        <button type="button" class="btn-tambah" onclick="openTambahModal()">
            <svg viewBox="0 0 24 24" style="width: 18px; height: 18px; fill: currentColor;"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            Tambah Kategori
        </button>
    </div>

    <!-- SEARCH BAR -->
    <div class="search-box">
        <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
        <input type="text" id="searchKategoriInput" placeholder="Cari kategori...">
    </div>

    @if($kategoris->count() > 0)

    {{-- ===== DESKTOP TABLE ===== --}}
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 70px;">No</th>
                    <th>Nama Kategori</th>
                    <th>Jumlah Barang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="kategoriTableBody">
                @foreach($kategoris as $index => $kategori)
                <tr class="kategori-row" data-name="{{ strtolower($kategori->nama_kategori) }}">
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong style="color: #0f172a; font-size: 0.95rem;">{{ $kategori->nama_kategori }}</strong>
                    </td>
                    <td>
                        @if($kategori->barangs_count > 0)
                            <span class="badge-count">📦 {{ $kategori->barangs_count }} Barang</span>
                        @else
                            <span class="badge-empty">0 Barang</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button type="button" class="btn-action btn-edit" title="Edit Kategori"
                                onclick="openEditModal('{{ $kategori->id_kategori }}', '{{ addslashes($kategori->nama_kategori) }}')">
                                <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                            </button>
                            
                            @if($kategori->barangs_count == 0)
                                <button type="button" class="btn-action btn-hapus" title="Hapus Kategori"
                                    onclick="openDeleteKategoriModal('{{ $kategori->id_kategori }}', '{{ addslashes($kategori->nama_kategori) }}')">
                                    <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                </button>
                            @else
                                <button type="button" class="btn-action btn-hapus" disabled title="Tidak dapat dihapus karena masih berisi {{ $kategori->barangs_count }} barang">
                                    <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ===== MOBILE CARDS ===== --}}
    <div class="mobile-cards" id="kategoriMobileList">
        @foreach($kategoris as $kategori)
        <div class="kategori-card" data-name="{{ strtolower($kategori->nama_kategori) }}">
            <div class="kategori-card-header">
                <span class="kategori-card-title">{{ $kategori->nama_kategori }}</span>
                @if($kategori->barangs_count > 0)
                    <span class="badge-count">📦 {{ $kategori->barangs_count }} Barang</span>
                @else
                    <span class="badge-empty">0 Barang</span>
                @endif
            </div>
            <div class="kategori-card-meta">
                <span style="font-size: 0.8rem; color: #64748b;">ID: #{{ $kategori->id_kategori }}</span>
                <div class="action-buttons">
                    <button type="button" class="btn-action btn-edit" title="Edit Kategori"
                        onclick="openEditModal('{{ $kategori->id_kategori }}', '{{ addslashes($kategori->nama_kategori) }}')">
                        <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                    </button>
                    @if($kategori->barangs_count == 0)
                        <button type="button" class="btn-action btn-hapus" title="Hapus Kategori"
                            onclick="openDeleteKategoriModal('{{ $kategori->id_kategori }}', '{{ addslashes($kategori->nama_kategori) }}')">
                            <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                        </button>
                    @else
                        <button type="button" class="btn-action btn-hapus" disabled title="Masih berisi {{ $kategori->barangs_count }} barang">
                            <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div id="emptyKategoriSearch" style="display: none; text-align: center; padding: 2.5rem; color: #94a3b8;">
        <h3>Kategori Tidak Ditemukan</h3>
        <p>Tidak ada kategori yang cocok dengan kata kunci pencarian.</p>
    </div>

    @else
    <div style="text-align: center; padding: 3rem; color: #94a3b8;">
        <h3>Belum Ada Kategori</h3>
        <p>Klik tombol "+ Tambah Kategori" untuk menambahkan kategori fasilitas baru.</p>
    </div>
    @endif
</div>

<!-- ===== MODAL TAMBAH KATEGORI ===== -->
<div class="modal-overlay-custom" id="modalTambah">
    <div class="modal-card">
        <form action="{{ route('admin.kategori.store') }}" method="POST">
            @csrf
            <div class="modal-card-header">
                <h3>Tambah Kategori Baru</h3>
                <button type="button" class="btn-close-modal" onclick="closeTambahModal()">&times;</button>
            </div>
            <div class="modal-card-body">
                <label for="namaKategoriTambah">Nama Kategori <span style="color: #dc2626;">*</span></label>
                <input type="text" name="nama_kategori" id="namaKategoriTambah" required placeholder="Contoh: Alat Laboratorium IPA" maxlength="100" autocomplete="off">
                <p style="font-size: 0.8rem; color: #64748b; margin-top: 0.5rem;">Nama kategori akan muncul saat menambah barang dan pada katalog peminjaman.</p>
            </div>
            <div class="modal-card-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeTambahModal()">Batal</button>
                <button type="submit" class="btn-modal-save">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL EDIT KATEGORI ===== -->
<div class="modal-overlay-custom" id="modalEdit">
    <div class="modal-card">
        <form id="formEditKategori" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-card-header">
                <h3>Edit Nama Kategori</h3>
                <button type="button" class="btn-close-modal" onclick="closeEditModal()">&times;</button>
            </div>
            <div class="modal-card-body">
                <label for="namaKategoriEdit">Nama Kategori <span style="color: #dc2626;">*</span></label>
                <input type="text" name="nama_kategori" id="namaKategoriEdit" required maxlength="100" autocomplete="off">
            </div>
            <div class="modal-card-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="btn-modal-save">Perbarui Kategori</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL HAPUS KATEGORI ===== -->
<div class="modal-overlay-custom" id="modalHapus">
    <div class="modal-card">
        <form id="formHapusKategori" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-card-header" style="background: #fef2f2; border-bottom-color: #fecaca;">
                <h3 style="color: #991b1b;">Konfirmasi Hapus Kategori</h3>
                <button type="button" class="btn-close-modal" onclick="closeDeleteKategoriModal()">&times;</button>
            </div>
            <div class="modal-card-body">
                <p id="deleteKategoriText" style="font-size: 1rem; color: #1e293b; line-height: 1.5;">Yakin ingin menghapus kategori ini?</p>
                <p style="font-size: 0.82rem; color: #64748b; margin-top: 0.5rem;">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-card-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeDeleteKategoriModal()">Batal</button>
                <button type="submit" class="btn-modal-delete">Ya, Hapus</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Live Search Kategori
    const searchInput = document.getElementById('searchKategoriInput');
    const tableRows = document.querySelectorAll('.kategori-row');
    const mobileCards = document.querySelectorAll('.kategori-card');
    const emptyMsg = document.getElementById('emptyKategoriSearch');

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();
            let visibleCount = 0;

            tableRows.forEach(row => {
                const name = row.getAttribute('data-name') || '';
                if (name.includes(query)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            mobileCards.forEach(card => {
                const name = card.getAttribute('data-name') || '';
                if (name.includes(query)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });

            if (emptyMsg) {
                emptyMsg.style.display = (visibleCount === 0 && (tableRows.length > 0 || mobileCards.length > 0)) ? 'block' : 'none';
            }
        });
    }

    // Modal Tambah
    function openTambahModal() {
        const modal = document.getElementById('modalTambah');
        if (modal) {
            modal.classList.add('active');
            const input = document.getElementById('namaKategoriTambah');
            if (input) {
                input.value = '';
                setTimeout(() => input.focus(), 100);
            }
        }
    }

    function closeTambahModal() {
        const modal = document.getElementById('modalTambah');
        if (modal) modal.classList.remove('active');
    }

    // Modal Edit
    function openEditModal(id, nama) {
        const modal = document.getElementById('modalEdit');
        const form = document.getElementById('formEditKategori');
        const input = document.getElementById('namaKategoriEdit');

        if (modal && form && input) {
            form.action = `/admin/kategori/${id}`;
            input.value = nama;
            modal.classList.add('active');
            setTimeout(() => input.focus(), 100);
        }
    }

    function closeEditModal() {
        const modal = document.getElementById('modalEdit');
        if (modal) modal.classList.remove('active');
    }

    // Modal Hapus
    function openDeleteKategoriModal(id, nama) {
        const modal = document.getElementById('modalHapus');
        const form = document.getElementById('formHapusKategori');
        const text = document.getElementById('deleteKategoriText');

        if (modal && form && text) {
            form.action = `/admin/kategori/${id}`;
            text.innerHTML = `Yakin ingin menghapus kategori <strong>"${nama}"</strong>?`;
            modal.classList.add('active');
        }
    }

    function closeDeleteKategoriModal() {
        const modal = document.getElementById('modalHapus');
        if (modal) modal.classList.remove('active');
    }

    // Close modal on click backdrop
    document.querySelectorAll('.modal-overlay-custom').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });
    });
</script>
@endsection

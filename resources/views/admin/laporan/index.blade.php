@extends('layouts.admin-sarana')

@section('title', 'Laporan Sarana & Prasarana')

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

    .report-tabs {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .report-tab-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem 1rem;
        text-decoration: none;
        color: #334155;
        border: 2px solid transparent;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .report-tab-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        border-color: #93c5fd;
    }

    .report-tab-card.active {
        background: #1e3a5f;
        color: white;
        border-color: #3b82f6;
        box-shadow: 0 8px 25px rgba(30, 58, 95, 0.3);
    }

    .report-tab-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .report-tab-badge {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 0.2rem 0.5rem;
        border-radius: 20px;
        background: #e2e8f0;
        color: #475569;
    }

    .report-tab-card.active .report-tab-badge {
        background: rgba(255,255,255,0.2);
        color: white;
    }

    .report-tab-title {
        font-size: 0.95rem;
        font-weight: 700;
        line-height: 1.3;
    }

    .report-tab-card.active .report-tab-title {
        color: white;
    }

    .report-tab-desc {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 0.4rem;
        line-height: 1.3;
    }

    .report-tab-card.active .report-tab-desc {
        color: rgba(255,255,255,0.85);
    }

    /* Filter Panel */
    .filter-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
    }

    .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f1f5f9;
        gap: 0.5rem;
    }

    .filter-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
        align-items: end;
    }

    .date-form-group {
        grid-column: span 2;
    }

    .date-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.3rem;
        flex-wrap: wrap;
        gap: 0.4rem;
    }

    .quick-date-wrapper {
        display: flex;
        gap: 0.3rem;
    }

    .date-inputs-wrapper {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }

    .date-separator {
        margin-top: 1rem;
        color: #94a3b8;
        font-weight: bold;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .form-group label {
        font-size: 0.85rem;
        font-weight: 700;
        color: #475569;
    }

    .form-control {
        width: 100%;
        padding: 0.6rem 0.85rem;
        border: 2px solid #cbd5e1;
        border-radius: 10px;
        font-size: 0.88rem;
        font-family: inherit;
        outline: none;
        transition: all 0.2s;
        background: #f8fafc;
    }

    .form-control:focus {
        border-color: #2563eb;
        background: white;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .btn-quick-date {
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .btn-quick-date:hover {
        background: #e2e8f0;
        border-color: #94a3b8;
        color: #0f172a;
    }

    .btn-filter {
        background: #2563eb;
        color: white;
        border: none;
        padding: 0.65rem 1.4rem;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        width: 100%;
    }

    .btn-filter:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
    }

    .btn-reset {
        background: #e2e8f0;
        color: #475569;
        border: none;
        padding: 0.6rem 1.1rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        transition: all 0.2s;
    }

    .btn-reset:hover {
        background: #cbd5e1;
        color: #1e293b;
    }

    .btn-print {
        background: #16a34a;
        color: white;
        border: none;
        padding: 0.7rem 1.4rem;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.25);
    }

    .btn-print:hover {
        background: #15803d;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(22, 163, 74, 0.35);
    }

    /* Summary Stats */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .summary-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        border-left: 5px solid #2563eb;
    }

    .summary-card.warning { border-left-color: #f59e0b; }
    .summary-card.danger { border-left-color: #dc2626; }
    .summary-card.success { border-left-color: #16a34a; }

    .summary-val {
        font-size: 1.8rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 0.2rem;
    }

    .summary-lbl {
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
    }

    /* Table & Container */
    .table-container {
        background: white;
        border-radius: 20px;
        padding: 1.5rem 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow-x: auto;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f1f5f9;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .table-header h3 {
        font-size: 1.15rem;
        color: #1e293b;
        font-weight: 800;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: #f8fafc;
    }

    th {
        padding: 0.9rem;
        text-align: left;
        font-weight: 700;
        color: #475569;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
    }

    td {
        padding: 0.9rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.88rem;
        color: #334155;
        vertical-align: middle;
    }

    tbody tr:hover {
        background: #f8fafc;
    }

    .badge {
        padding: 0.3rem 0.7rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-block;
    }

    .badge-success { background: #dcfce7; color: #15803d; }
    .badge-warning { background: #fef3c7; color: #b45309; }
    .badge-danger { background: #fee2e2; color: #b91c1c; }
    .badge-info { background: #e0f2fe; color: #0369a1; }
    .badge-secondary { background: #f1f5f9; color: #475569; }

    /* Mobile Cards Container */
    .mobile-report-cards {
        display: none;
    }

    .report-item-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.1rem;
        margin-bottom: 1rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transition: all 0.2s;
    }

    .report-item-card:hover {
        box-shadow: 0 6px 18px rgba(0,0,0,0.09);
    }

    .report-item-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
        padding-bottom: 0.6rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .report-item-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
    }

    .report-item-sub {
        font-size: 0.78rem;
        color: #64748b;
        margin-top: 0.15rem;
    }

    .report-item-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
    }

    .info-row {
        background: #f8fafc;
        border-radius: 8px;
        padding: 0.5rem 0.7rem;
    }

    .info-label {
        font-size: 0.68rem;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        font-weight: 600;
    }

    .info-value {
        font-size: 0.82rem;
        color: #334155;
        font-weight: 600;
        margin-top: 0.15rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #94a3b8;
    }

    .empty-state svg {
        width: 70px;
        height: 70px;
        margin-bottom: 1rem;
        opacity: 0.4;
    }

    /* ===== MOBILE RESPONSIVE RULES ===== */
    @media (max-width: 1024px) {
        .report-tabs {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch;
            gap: 0.8rem;
        }

        .page-title-section h2 {
            font-size: 1.4rem;
        }

        .btn-print {
            width: 100%;
            justify-content: center;
        }

        .report-tabs {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .filter-card {
            padding: 1.25rem 1rem;
        }

        .filter-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .date-form-group {
            grid-column: span 1 !important;
        }

        .date-header-row {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 0.4rem;
        }

        .quick-date-wrapper {
            width: 100%;
            display: flex;
            gap: 0.3rem;
        }

        .btn-quick-date {
            flex: 1;
            text-align: center;
        }

        .date-inputs-wrapper {
            flex-direction: column !important;
            gap: 0.5rem !important;
            align-items: stretch !important;
        }

        .date-separator {
            display: none;
        }

        .summary-grid {
            grid-template-columns: 1fr;
            gap: 0.8rem;
        }

        .summary-card {
            padding: 1rem 1.2rem;
        }

        .summary-val {
            font-size: 1.5rem;
        }

        .table-container {
            display: none;
        }

        .mobile-report-cards {
            display: block;
        }
    }

    /* ===== ANALYTICS & CHART STYLING ===== */
    .analytics-section {
        margin-bottom: 2rem;
    }

    .analytics-card {
        background: white;
        border-radius: 20px;
        padding: 1.75rem 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .analytics-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f5f9;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .analytics-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .analytics-subtitle {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 0.35rem;
    }

    .analytics-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #eff6ff;
        color: #1d4ed8;
        padding: 0.35rem 0.85rem;
        border-radius: 9999px;
        font-size: 0.78rem;
        font-weight: 700;
        border: 1px solid #bfdbfe;
    }

    .badge-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #2563eb;
        display: inline-block;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
    }

    .analytics-grid {
        display: grid;
        grid-template-columns: 2fr 1.1fr;
        gap: 1.5rem;
    }

    .chart-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        display: flex;
        flex-direction: column;
    }

    .chart-box-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .chart-box-header h4 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .chart-subtext {
        font-size: 0.75rem;
        color: #475569;
        background: #e2e8f0;
        padding: 0.2rem 0.55rem;
        border-radius: 6px;
        font-weight: 600;
    }

    .chart-canvas-wrapper {
        position: relative;
        flex: 1;
        min-height: 280px;
        max-height: 320px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @media (max-width: 992px) {
        .analytics-grid {
            grid-template-columns: 1fr;
        }
        .analytics-card {
            padding: 1.25rem;
        }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div class="page-title-section">
        <h2>📊 Laporan Data Barang</h2>
    </div>
    <a href="{{ route('admin.laporan.cetak', request()->all()) }}" target="_blank" class="btn-print">
        🖨️ Cetak PDF
    </a>
</div>

<!-- 1. TABS NAVIGASI LAPORAN -->
<div class="report-tabs">
    <a href="{{ route('admin.laporan.index', array_merge(request()->except('jenis_laporan'), ['jenis_laporan' => 'frekuensi'])) }}"
       class="report-tab-card {{ $jenisLaporan === 'frekuensi' ? 'active' : '' }}">
        <div>
            <div class="report-tab-header">
                <span class="report-tab-badge">Laporan 1</span>
            </div>
            <div class="report-tab-title">📈 Barang Sering Dipinjam</div>
            <div class="report-tab-desc">Daftar barang yang paling banyak dan sering dipinjam.</div>
        </div>
    </a>

    <a href="{{ route('admin.laporan.index', array_merge(request()->except('jenis_laporan'), ['jenis_laporan' => 'kerusakan'])) }}"
       class="report-tab-card {{ $jenisLaporan === 'kerusakan' ? 'active' : '' }}">
        <div>
            <div class="report-tab-header">
                <span class="report-tab-badge">Laporan 2</span>
            </div>
            <div class="report-tab-title">⚠️ Riwayat Barang Rusak</div>
            <div class="report-tab-desc">Daftar kerusakan barang & catatan dari peminjam.</div>
        </div>
    </a>

    <a href="{{ route('admin.laporan.index', array_merge(request()->except('jenis_laporan'), ['jenis_laporan' => 'keterlambatan'])) }}"
       class="report-tab-card {{ $jenisLaporan === 'keterlambatan' ? 'active' : '' }}">
        <div>
            <div class="report-tab-header">
                <span class="report-tab-badge">Laporan 3</span>
            </div>
            <div class="report-tab-title">⏱️ Keterlambatan Pengembalian</div>
            <div class="report-tab-desc">Daftar peminjam yang terlambat mengembalikan barang.</div>
        </div>
    </a>

    <a href="{{ route('admin.laporan.index', array_merge(request()->except('jenis_laporan'), ['jenis_laporan' => 'stok'])) }}"
       class="report-tab-card {{ $jenisLaporan === 'stok' ? 'active' : '' }}">
        <div>
            <div class="report-tab-header">
                <span class="report-tab-badge">Laporan 4</span>
            </div>
            <div class="report-tab-title">📦 Stok & Kondisi Barang</div>
            <div class="report-tab-desc">Ringkasan stok barang baik, dipinjam, atau rusak.</div>
        </div>
    </a>
</div>

<!-- 2. FILTER SEARCH PANEL -->
<div class="filter-card">
    <div class="filter-header">
        <div class="filter-title">
            🔍 Filter & Search Data
        </div>
        <a href="{{ route('admin.laporan.index', ['jenis_laporan' => $jenisLaporan]) }}" class="btn-reset">
            🔄 Reset
        </a>
    </div>

    <form method="GET" action="{{ route('admin.laporan.index') }}" id="filterForm">
        <input type="hidden" name="jenis_laporan" value="{{ $jenisLaporan }}">

        <div class="filter-grid">
            @if(in_array($jenisLaporan, ['frekuensi', 'kerusakan', 'keterlambatan']))
            <div class="form-group date-form-group">
                <div class="date-header-row">
                    <label style="font-weight: 700; color: #475569; font-size: 0.85rem;">Pilihan Tanggal</label>
                    <div class="quick-date-wrapper">
                        <button type="button" onclick="setDateRange('prev_month')" class="btn-quick-date" title="Bulan Sebelumnya">◀ Bln Lalu</button>
                        <button type="button" onclick="setDateRange('this_month')" class="btn-quick-date">Bln Ini</button>
                        <button type="button" onclick="setDateRange('next_month')" class="btn-quick-date" title="Bulan Berikutnya">Bln Depan ▶</button>
                    </div>
                </div>
                <div class="date-inputs-wrapper">
                    <div style="flex: 1; width: 100%;">
                        <span style="font-size: 0.75rem; color: #64748b; font-weight: 600;">Dari Tgl:</span>
                        <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $startDate }}">
                    </div>
                    <span class="date-separator">s/d</span>
                    <div style="flex: 1; width: 100%;">
                        <span style="font-size: 0.75rem; color: #64748b; font-weight: 600;">Sampai Tgl:</span>
                        <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                </div>
            </div>
            @endif

            @if(in_array($jenisLaporan, ['frekuensi', 'kerusakan', 'stok']))
            <div class="form-group">
                <label for="id_kategori">Kategori Barang</label>
                <select id="id_kategori" name="id_kategori" class="form-control">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id_kategori }}" {{ $idKategori == $kat->id_kategori ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            @if($jenisLaporan === 'kerusakan')
            <div class="form-group">
                <label for="kondisi_barang">Kondisi Kerusakan</label>
                <select id="kondisi_barang" name="kondisi_barang" class="form-control">
                    <option value="">Semua Kerusakan</option>
                    <option value="rusak_ringan" {{ $kondisiBarang == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak_berat" {{ $kondisiBarang == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
            </div>
            @endif

            @if($jenisLaporan === 'keterlambatan')
            <div class="form-group">
                <label for="role_peminjam">Peran Peminjam</label>
                <select id="role_peminjam" name="role_peminjam" class="form-control">
                    <option value="">Semua (Siswa & Guru)</option>
                    <option value="siswa" {{ $rolePeminjam == 'siswa' ? 'selected' : '' }}>Siswa</option>
                    <option value="guru" {{ $rolePeminjam == 'guru' ? 'selected' : '' }}>Guru / Staff</option>
                </select>
            </div>
            <div class="form-group">
                <label for="status_pengembalian">Status Pengembalian</label>
                <select id="status_pengembalian" name="status_pengembalian" class="form-control">
                    <option value="">Semua Terlambat</option>
                    <option value="dikembalikan_terlambat" {{ $statusPengembalian == 'dikembalikan_terlambat' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                    <option value="belum_dikembalikan" {{ $statusPengembalian == 'belum_dikembalikan' ? 'selected' : '' }}>Belum Dikembalikan</option>
                </select>
            </div>
            @endif

            <div class="form-group">
                <button type="submit" class="btn-filter">
                    🔍 Search
                </button>
            </div>
        </div>
    </form>
</div>

<!-- 3. RINGKASAN DATA -->
<div class="summary-grid">
    @if($jenisLaporan === 'frekuensi')
        <div class="summary-card">
            <div class="summary-val">{{ $totalFrekuensiGlobal }}</div>
            <div class="summary-lbl">Total Peminjaman</div>
        </div>
        <div class="summary-card success">
            <div class="summary-val">{{ $totalBarangDipinjamUnique }}</div>
            <div class="summary-lbl">Jenis Barang Dipinjam</div>
        </div>
    @elseif($jenisLaporan === 'kerusakan')
        <div class="summary-card danger">
            <div class="summary-val">{{ $totalKasusKerusakan }}</div>
            <div class="summary-lbl">Total Barang Rusak</div>
        </div>
        <div class="summary-card warning">
            <div class="summary-val">{{ $totalRusakRingan }}</div>
            <div class="summary-lbl">Rusak Ringan</div>
        </div>
        <div class="summary-card danger">
            <div class="summary-val">{{ $totalRusakBerat }}</div>
            <div class="summary-lbl">Rusak Berat</div>
        </div>
    @elseif($jenisLaporan === 'keterlambatan')
        <div class="summary-card danger">
            <div class="summary-val">{{ $totalTerlambat }}</div>
            <div class="summary-lbl">Total Terlambat</div>
        </div>
        <div class="summary-card warning">
            <div class="summary-val">{{ $totalSiswaTerlambat }}</div>
            <div class="summary-lbl">Siswa Terlambat</div>
        </div>
        <div class="summary-card info">
            <div class="summary-val">{{ $totalGuruTerlambat }}</div>
            <div class="summary-lbl">Guru Terlambat</div>
        </div>
    @elseif($jenisLaporan === 'stok')
        <div class="summary-card">
            <div class="summary-val">{{ $totalStokKeseluruhan }}</div>
            <div class="summary-lbl">Total Unit Barang</div>
        </div>
        <div class="summary-card success">
            <div class="summary-val">{{ $totalBaik }}</div>
            <div class="summary-lbl">Stok Baik (Tersedia)</div>
        </div>
        <div class="summary-card warning">
            <div class="summary-val">{{ $totalKurangBaik }}</div>
            <div class="summary-lbl">Sedang Dipinjam</div>
        </div>
        <div class="summary-card danger">
            <div class="summary-val">{{ $totalRusakBerat }}</div>
            <div class="summary-lbl">Rusak Berat</div>
        </div>
    @endif
</div>

<!-- 3.5. GRAFIK & VISUALISASI DATA (PER BULAN / FILTER AKTIF) -->
<div class="analytics-section">
    <div class="analytics-card">
        <div class="analytics-header">
            <div>
                <div class="analytics-title">
                    <span>📊</span>
                    <span>
                        @if($jenisLaporan === 'frekuensi')
                            Grafik Tren Peminjaman {{ ($chartData['timeType'] ?? '') === 'daily' ? 'Harian' : 'Per Bulan' }}
                        @elseif($jenisLaporan === 'kerusakan')
                            Grafik Riwayat Kasus Kerusakan {{ ($chartData['timeType'] ?? '') === 'daily' ? 'Harian' : 'Per Bulan' }}
                        @elseif($jenisLaporan === 'keterlambatan')
                            Grafik Tren Keterlambatan {{ ($chartData['timeType'] ?? '') === 'daily' ? 'Harian' : 'Per Bulan' }}
                        @else
                            Grafik Distribusi Stok per Kategori
                        @endif
                    </span>
                </div>
                <div class="analytics-subtitle">
                    Visualisasi data terhitung otomatis sesuai filter &bull; Periode: <strong style="color: #2563eb;">{{ $chartData['periodLabel'] ?? 'Tahun Berjalan' }}</strong>
                </div>
            </div>
            <div class="analytics-badge">
                <span class="badge-dot"></span> Grafik Interaktif
            </div>
        </div>

        <div class="analytics-grid">
            <!-- Main Chart (Line / Bar) -->
            <div class="chart-box main-chart-box">
                <div class="chart-box-header">
                    <h4>
                        @if($jenisLaporan === 'frekuensi')
                            📈 Tren Peminjaman Barang
                        @elseif($jenisLaporan === 'kerusakan')
                            📊 Tren Kasus Kerusakan Barang
                        @elseif($jenisLaporan === 'keterlambatan')
                            ⏱️ Tren Keterlambatan Pengembalian
                        @else
                            📦 Kapasitas Stok per Kategori
                        @endif
                    </h4>
                    <span class="chart-subtext">Sesuai Filter Admin</span>
                </div>
                <div class="chart-canvas-wrapper">
                    <canvas id="mainReportChart"></canvas>
                </div>
            </div>

            <!-- Secondary Chart (Doughnut / Top 5 / Pie) -->
            <div class="chart-box side-chart-box">
                <div class="chart-box-header">
                    <h4>
                        @if($jenisLaporan === 'frekuensi')
                            🏆 Top 5 Barang Sering Dipinjam
                        @elseif($jenisLaporan === 'kerusakan')
                            ⚠️ Proporsi Kondisi Kerusakan
                        @elseif($jenisLaporan === 'keterlambatan')
                            📋 Status Keterlambatan
                        @else
                            🥧 Proporsi Kondisi Keseluruhan
                        @endif
                    </h4>
                    <span class="chart-subtext">Distribusi Persentase</span>
                </div>
                <div class="chart-canvas-wrapper">
                    <canvas id="secondaryReportChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 4. HASIL LAPORAN (DESKTOP TABLE & MOBILE CARDS) -->
<div style="background: white; border-radius: 20px; padding: 1.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
    <div class="table-header">
        <h3>
            @if($jenisLaporan === 'frekuensi')
                📋 Daftar Barang Paling Sering Dipinjam
            @elseif($jenisLaporan === 'kerusakan')
                📋 Daftar Riwayat Barang Rusak
            @elseif($jenisLaporan === 'keterlambatan')
                📋 Daftar Keterlambatan Pengembalian
            @else
                📋 Daftar Stok & Kondisi Barang
            @endif
        </h3>
        <a href="{{ route('admin.laporan.cetak', request()->all()) }}" target="_blank" class="btn-print" style="padding: 0.4rem 0.9rem; font-size: 0.8rem;">
            🖨️ Cetak Laporan Ini
        </a>
    </div>

    @if(count($items) > 0)
        <!-- ===== DESKTOP TABLE ===== -->
        <div class="table-container">
            <table>
                @if($jenisLaporan === 'frekuensi')
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Status Saat Ini</th>
                            <th style="text-align: center;">Frekuensi Dipinjam</th>
                            <th style="text-align: center;">Selesai (Kembali)</th>
                            <th style="text-align: center;">Sedang Dipinjam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $idx => $item)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td><strong>{{ $item->kode_barang }}</strong></td>
                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ $item->barang_detail->kategori->nama_kategori ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $item->barang_detail->status ?? 'Tersedia' }}
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge badge-success" style="font-size: 0.85rem; padding: 0.35rem 0.8rem;">
                                        {{ $item->total_dipinjam }}x
                                    </span>
                                </td>
                                <td style="text-align: center;">{{ $item->total_selesai }} kali</td>
                                <td style="text-align: center;">{{ $item->total_aktif }} unit</td>
                            </tr>
                        @endforeach
                    </tbody>

                @elseif($jenisLaporan === 'kerusakan')
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tgl Pengembalian</th>
                            <th>Barang (Kode)</th>
                            <th>Kategori</th>
                            <th>Peminjam</th>
                            <th>Kondisi</th>
                            <th>Catatan Kronologi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $idx => $item)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->locale('id')->isoFormat('DD MMM YYYY') }}</td>
                                <td>
                                    <strong>{{ $item->nama_barang }}</strong><br>
                                    <small style="color: #64748b;">{{ $item->kode_barang }}</small>
                                </td>
                                <td>{{ $item->barang->kategori->nama_kategori ?? '-' }}</td>
                                <td>
                                    <strong>{{ $item->user->nama_lengkap ?? $item->peminjaman->nama_peminjam ?? '-' }}</strong><br>
                                    <small style="color: #94a3b8;">{{ ucfirst($item->user->role ?? $item->peminjaman->role_peminjam ?? '') }}</small>
                                </td>
                                <td>
                                    @if($item->kondisi_pengembalian === 'rusak_ringan')
                                        <span class="badge badge-warning">⚠️ Rusak Ringan</span>
                                    @elseif($item->kondisi_pengembalian === 'rusak_berat')
                                        <span class="badge badge-danger">🚫 Rusak Berat</span>
                                    @else
                                        <span class="badge badge-success">Baik</span>
                                    @endif
                                </td>
                                <td>{{ $item->catatan_kondisi ?: ($item->keterangan ?: '-') }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                @elseif($jenisLaporan === 'keterlambatan')
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Peminjam</th>
                            <th>NIS/NIP & Role</th>
                            <th>Barang Dipinjam</th>
                            <th>Tenggat Kembali</th>
                            <th>Tgl Kembali Real</th>
                            <th>Keterlambatan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $idx => $item)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td><strong>{{ $item->nama_peminjam }}</strong></td>
                                <td>
                                    {{ $item->user->nis_nip ?? '-' }}<br>
                                    <small style="color: #64748b;">{{ ucfirst($item->role_peminjam) }}</small>
                                </td>
                                <td>
                                    <strong>{{ $item->nama_barang }}</strong><br>
                                    <small style="color: #94a3b8;">{{ $item->kode_barang }}</small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_kembali)->locale('id')->isoFormat('DD MMM YYYY') }}</td>
                                <td>
                                    @if($item->tgl_realisasi_pengembalian)
                                        {{ \Carbon\Carbon::parse($item->tgl_realisasi_pengembalian)->locale('id')->isoFormat('DD MMM YYYY') }}
                                    @else
                                        <span style="color: #dc2626; font-style: italic;">Belum Kembali</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-danger" style="font-size: 0.82rem;">
                                        ⏰ +{{ $item->durasi_terlambat }} Hari
                                    </span>
                                </td>
                                <td>
                                    @if(str_contains($item->status_keterlambatan, 'Belum'))
                                        <span class="badge badge-danger">{{ $item->status_keterlambatan }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ $item->status_keterlambatan }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                @elseif($jenisLaporan === 'stok')
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Tahun Pembelian</th>
                            <th style="text-align: center;">Tersedia (Baik)</th>
                            <th style="text-align: center;">Dipinjam</th>
                            <th style="text-align: center;">Rusak Berat</th>
                            <th style="text-align: center;">Total Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $idx => $item)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td><strong>{{ $item->kode_barang }}</strong></td>
                                <td>
                                    <strong>{{ $item->nama_barang }}</strong><br>
                                    <small style="color: #64748b;">{{ $item->merk_model ?: '-' }}</small>
                                </td>
                                <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $item->tahun_pembelian ?: '-' }}</td>
                                <td style="text-align: center;">
                                    <span class="badge badge-success">{{ $item->jumlah_baik }} unit</span>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge badge-warning">{{ $item->jumlah_kurang_baik }} unit</span>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge badge-danger">{{ $item->jumlah_rusak_berat }} unit</span>
                                </td>
                                <td style="text-align: center;">
                                    <strong>{{ $item->jumlah_baik + $item->jumlah_kurang_baik + $item->jumlah_rusak_berat }} unit</strong>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                @endif
            </table>
        </div>

        <!-- ===== MOBILE CARDS (RESPONSIF MOBILE SANGAT CLEAN) ===== -->
        <div class="mobile-report-cards">
            @if($jenisLaporan === 'frekuensi')
                @foreach($items as $item)
                    <div class="report-item-card">
                        <div class="report-item-header">
                            <div>
                                <div class="report-item-title">{{ $item->nama_barang }}</div>
                                <div class="report-item-sub">{{ $item->kode_barang }} • {{ $item->barang_detail->kategori->nama_kategori ?? '-' }}</div>
                            </div>
                            <span class="badge badge-success" style="font-size: 0.82rem;">{{ $item->total_dipinjam }}x Dipinjam</span>
                        </div>
                        <div class="report-item-info">
                            <div class="info-row">
                                <div class="info-label">Status Saat Ini</div>
                                <div class="info-value"><span class="badge badge-info">{{ $item->barang_detail->status ?? 'Tersedia' }}</span></div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Selesai Kembali</div>
                                <div class="info-value">{{ $item->total_selesai }} kali</div>
                            </div>
                            <div class="info-row" style="grid-column: span 2;">
                                <div class="info-label">Sedang Dipinjam</div>
                                <div class="info-value">{{ $item->total_aktif }} unit</div>
                            </div>
                        </div>
                    </div>
                @endforeach

            @elseif($jenisLaporan === 'kerusakan')
                @foreach($items as $item)
                    <div class="report-item-card">
                        <div class="report-item-header">
                            <div>
                                <div class="report-item-title">{{ $item->nama_barang }}</div>
                                <div class="report-item-sub">{{ $item->kode_barang }} • {{ $item->barang->kategori->nama_kategori ?? '-' }}</div>
                            </div>
                            <div>
                                @if($item->kondisi_pengembalian === 'rusak_ringan')
                                    <span class="badge badge-warning">⚠️ Rusak Ringan</span>
                                @elseif($item->kondisi_pengembalian === 'rusak_berat')
                                    <span class="badge badge-danger">🚫 Rusak Berat</span>
                                @else
                                    <span class="badge badge-success">Baik</span>
                                @endif
                            </div>
                        </div>
                        <div class="report-item-info">
                            <div class="info-row">
                                <div class="info-label">Pelapor / Peminjam</div>
                                <div class="info-value">{{ $item->user->nama_lengkap ?? $item->peminjaman->nama_peminjam ?? '-' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Tgl Lapor</div>
                                <div class="info-value">{{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->locale('id')->isoFormat('DD MMM YYYY') }}</div>
                            </div>
                        </div>
                        <div style="background: #f8fafc; border-radius: 8px; padding: 0.6rem 0.8rem; margin-top: 0.5rem; font-size: 0.82rem; color: #334155;">
                            <strong>Catatan Kronologi:</strong><br>
                            {{ $item->catatan_kondisi ?: ($item->keterangan ?: '-') }}
                        </div>
                    </div>
                @endforeach

            @elseif($jenisLaporan === 'keterlambatan')
                @foreach($items as $item)
                    <div class="report-item-card">
                        <div class="report-item-header">
                            <div>
                                <div class="report-item-title">{{ $item->nama_peminjam }}</div>
                                <div class="report-item-sub">{{ $item->user->nis_nip ?? '-' }} ({{ ucfirst($item->role_peminjam) }})</div>
                            </div>
                            <span class="badge badge-danger" style="font-size: 0.82rem;">⏰ +{{ $item->durasi_terlambat }} Hari</span>
                        </div>
                        <div class="report-item-info">
                            <div class="info-row" style="grid-column: span 2;">
                                <div class="info-label">Barang Dipinjam</div>
                                <div class="info-value">{{ $item->nama_barang }} ({{ $item->kode_barang }})</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Tenggat Kembali</div>
                                <div class="info-value">{{ \Carbon\Carbon::parse($item->tanggal_kembali)->locale('id')->isoFormat('DD MMM YYYY') }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Realisasi Kembali</div>
                                <div class="info-value">
                                    @if($item->tgl_realisasi_pengembalian)
                                        {{ \Carbon\Carbon::parse($item->tgl_realisasi_pengembalian)->locale('id')->isoFormat('DD MMM YYYY') }}
                                    @else
                                        <span style="color: #dc2626; font-style: italic;">Belum Kembali</span>
                                    @endif
                                </div>
                            </div>
                            <div class="info-row" style="grid-column: span 2;">
                                <div class="info-label">Status</div>
                                <div class="info-value">
                                    @if(str_contains($item->status_keterlambatan, 'Belum'))
                                        <span class="badge badge-danger">{{ $item->status_keterlambatan }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ $item->status_keterlambatan }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            @elseif($jenisLaporan === 'stok')
                @foreach($items as $item)
                    <div class="report-item-card">
                        <div class="report-item-header">
                            <div>
                                <div class="report-item-title">{{ $item->nama_barang }}</div>
                                <div class="report-item-sub">{{ $item->kode_barang }} • {{ $item->kategori->nama_kategori ?? '-' }}</div>
                            </div>
                            <span class="badge badge-info" style="font-size: 0.85rem;">Total: {{ $item->jumlah_baik + $item->jumlah_kurang_baik + $item->jumlah_rusak_berat }} Unit</span>
                        </div>
                        <div class="report-item-info">
                            <div class="info-row">
                                <div class="info-label">Baik (Tersedia)</div>
                                <div class="info-value"><span class="badge badge-success">{{ $item->jumlah_baik }} unit</span></div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Dipinjam</div>
                                <div class="info-value"><span class="badge badge-warning">{{ $item->jumlah_kurang_baik }} unit</span></div>
                            </div>
                            <div class="info-row" style="grid-column: span 2;">
                                <div class="info-label">Rusak Berat</div>
                                <div class="info-value"><span class="badge badge-danger">{{ $item->jumlah_rusak_berat }} unit</span></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

    @else
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
            </svg>
            <h3>Data Tidak Ditemukan</h3>
            <p>Tidak ada data yang sesuai dengan kriteria pencarian anda.</p>
        </div>
    @endif
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function setDateRange(type) {
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');
        const now = new Date();

        function formatDate(d) {
            const year = d.getFullYear();
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        if (type === 'this_month') {
            const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
            const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
            startInput.value = formatDate(firstDay);
            endInput.value = formatDate(lastDay);
        } else if (type === 'prev_month') {
            let curStart = startInput.value ? new Date(startInput.value) : new Date();
            let prevMonthFirst = new Date(curStart.getFullYear(), curStart.getMonth() - 1, 1);
            let prevMonthLast = new Date(curStart.getFullYear(), curStart.getMonth(), 0);
            startInput.value = formatDate(prevMonthFirst);
            endInput.value = formatDate(prevMonthLast);
        } else if (type === 'next_month') {
            let curStart = startInput.value ? new Date(startInput.value) : new Date();
            let nextMonthFirst = new Date(curStart.getFullYear(), curStart.getMonth() + 1, 1);
            let nextMonthLast = new Date(curStart.getFullYear(), curStart.getMonth() + 2, 0);
            startInput.value = formatDate(nextMonthFirst);
            endInput.value = formatDate(nextMonthLast);
        }
    }

    // Inisialisasi Grafik Chart.js
    document.addEventListener('DOMContentLoaded', function() {
        const chartData = @json($chartData ?? []);
        if (!chartData || !chartData.labels) return;

        const mainCtx = document.getElementById('mainReportChart');
        const secondaryCtx = document.getElementById('secondaryReportChart');

        // Common Plugins Configuration
        const commonPlugins = {
            legend: {
                position: 'bottom',
                labels: {
                    boxWidth: 12,
                    boxHeight: 12,
                    usePointStyle: true,
                    pointStyle: 'circle',
                    font: { family: "'Segoe UI', Tahoma, sans-serif", size: 12, weight: '600' },
                    padding: 14
                }
            },
            tooltip: {
                backgroundColor: 'rgba(15, 23, 42, 0.92)',
                titleFont: { size: 13, weight: '700' },
                bodyFont: { size: 12 },
                padding: 10,
                cornerRadius: 8,
                boxPadding: 4
            }
        };

        // 1. Render Grafik Utama (Tren Per Bulan / Periode)
        if (mainCtx) {
            let mainChartType = 'bar';
            if (chartData.type === 'frekuensi') {
                mainChartType = 'line';
            }

            new Chart(mainCtx, {
                type: mainChartType,
                data: {
                    labels: chartData.labels,
                    datasets: chartData.datasets || []
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: commonPlugins,
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11, weight: '600' }, color: '#64748b' }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(226, 232, 240, 0.8)' },
                            ticks: {
                                precision: 0,
                                font: { size: 11 },
                                color: '#64748b',
                                callback: function(val) {
                                    return val + (chartData.type === 'frekuensi' ? 'x' : ' unit');
                                }
                            }
                        }
                    }
                }
            });
        }

        // 2. Render Grafik Sekunder (Doughnut Proporsi / Top 5)
        if (secondaryCtx) {
            if (chartData.type === 'frekuensi') {
                const topLabels = (chartData.topBarangLabels && chartData.topBarangLabels.length) 
                    ? chartData.topBarangLabels 
                    : ['Belum Ada Data'];
                const topData = (chartData.topBarangData && chartData.topBarangData.length) 
                    ? chartData.topBarangData 
                    : [0];
                const topColors = ['#2563eb', '#3b82f6', '#60a5fa', '#93c5fd', '#a5b4fc'];

                new Chart(secondaryCtx, {
                    type: 'doughnut',
                    data: {
                        labels: topLabels,
                        datasets: [{
                            data: topData,
                            backgroundColor: topColors.slice(0, topLabels.length),
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '62%',
                        plugins: commonPlugins
                    }
                });
            } else if (chartData.pieLabels && chartData.pieData) {
                const pieColors = chartData.pieColors || ['#10b981', '#f59e0b', '#ef4444'];
                const totalPie = chartData.pieData.reduce((a, b) => a + b, 0);

                new Chart(secondaryCtx, {
                    type: 'doughnut',
                    data: {
                        labels: totalPie > 0 ? chartData.pieLabels : ['Belum Ada Data'],
                        datasets: [{
                            data: totalPie > 0 ? chartData.pieData : [1],
                            backgroundColor: totalPie > 0 ? pieColors : ['#cbd5e1'],
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '62%',
                        plugins: commonPlugins
                    }
                });
            }
        }
    });
</script>
@endsection

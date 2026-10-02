@extends('layouts.dashboard-user')

@section('title', 'Pengembalian Barang')

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
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .page-header p {
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.95rem;
        margin-top: 0.3rem;
    }

    .main-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 0.8rem;
        border-bottom: 2px solid #f1f5f9;
    }

    .section-header h3 {
        font-size: 1.25rem;
        color: #1e293b;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .badge-counter {
        background: #3b82f6;
        color: white;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
    }

    /* Active borrow cards */
    .borrow-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    .borrow-card {
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background: #f8fafc;
        transition: all 0.25s ease;
    }

    .borrow-card:hover {
        border-color: #3b82f6;
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.12);
        transform: translateY(-3px);
    }

    .borrow-top {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .borrow-img {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
        border: 1px solid #e2e8f0;
    }

    .borrow-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .borrow-info h4 {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.3rem;
    }

    .borrow-info .meta {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 0.2rem;
    }

    .borrow-dates {
        background: white;
        padding: 0.8rem 1rem;
        border-radius: 10px;
        font-size: 0.85rem;
        color: #475569;
        margin-bottom: 1.2rem;
        border: 1px solid #edf2f7;
    }

    .borrow-dates div {
        margin-bottom: 0.3rem;
        display: flex;
        justify-content: space-between;
    }

    .borrow-dates div:last-child {
        margin-bottom: 0;
    }

    .borrow-dates strong {
        color: #1e293b;
    }

    .btn-return {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 0.8rem 1.2rem;
        border-radius: 12px;
        text-align: center;
        font-weight: 700;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
    }

    .btn-return:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(16, 185, 129, 0.35);
    }

    .btn-return-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
    }

    .btn-return-warning:hover {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    }

    /* History table */
    .table-responsive {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: #f8fafc;
    }

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
        padding: 1.1rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
        color: #334155;
        vertical-align: middle;
    }

    tbody tr:hover {
        background: #f8fafc;
    }

    /* Status Badges */
    .badge {
        display: inline-block;
        padding: 0.4rem 0.9rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-disetujui { background: #dcfce7; color: #166534; }
    .badge-ditolak { background: #fee2e2; color: #991b1b; }

    .badge-kondisi-baik { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .badge-kondisi-rusak_ringan { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .badge-kondisi-rusak_berat { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

    .btn-detail {
        background: #f1f5f9;
        color: #1e40af;
        border: 1px solid #cbd5e1;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: all 0.2s;
    }

    .btn-detail:hover {
        background: #1e40af;
        color: white;
        border-color: #1e40af;
    }

    .empty-state {
        text-align: center;
        padding: 2.5rem 1rem;
        color: #94a3b8;
    }

    .empty-state svg {
        width: 60px;
        height: 60px;
        margin-bottom: 0.8rem;
        opacity: 0.35;
    }

    .empty-state h4 {
        color: #1e293b;
        font-size: 1.1rem;
        margin-bottom: 0.3rem;
    }

    .empty-state p {
        color: #64748b;
        font-size: 0.9rem;
    }

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
    .alert-info { color: #1e40af; border-left: 4px solid #3b82f6; }
</style>
@endsection

@section('content')

<div class="page-header">
    <div class="page-title-section" style="flex-direction: column; align-items: flex-start;">
        <h2>📦 Pengembalian Fasilitas</h2>
        <p style="color: rgba(255, 255, 255, 0.85); font-size: 0.95rem; margin-top: 0.3rem;">Kelola dan ajukan pengembalian fasilitas/barang yang telah selesai Anda pinjam</p>
    </div>
    <a href="{{ route('user.dashboard') }}" class="btn-back">← Kembali ke Dashboard</a>
</div>

@if(session('success'))
    <div class="alert-box alert-success">
        ✅ {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert-box alert-error">
        ⚠️ {{ session('error') }}
    </div>
@endif

@if(session('info'))
    <div class="alert-box alert-info">
        ℹ️ {{ session('info') }}
    </div>
@endif

<!-- SECTION 1: BARANG YANG SEDANG DIPINJAM -->
<div class="main-card">
    <div class="section-header">
        <h3>
            <span>Barang Yang Sedang Anda Pinjam</span>
            <span class="badge-counter">{{ $sedangDipinjam->count() }}</span>
        </h3>
    </div>

    @if($sedangDipinjam->count() > 0)
        <div class="borrow-grid">
            @foreach($sedangDipinjam as $item)
                <div class="borrow-card">
                    <div>
                        <div class="borrow-top">
                            <div class="borrow-img">
                                @if($item->barang && $item->barang->gambar)
                                    <img src="{{ asset('storage/' . $item->barang->gambar) }}" alt="{{ $item->nama_barang }}">
                                @else
                                    <span style="font-size: 2rem;">📦</span>
                                @endif
                            </div>
                            <div class="borrow-info">
                                <h4>{{ $item->nama_barang }}</h4>
                                <div class="meta">Kode: {{ $item->kode_barang }}</div>
                                <div class="meta">Kategori: {{ $item->barang->kategori->nama_kategori ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="borrow-dates">
                            <div>
                                <span>Tanggal Pinjam:</span>
                                <strong>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->locale('id')->isoFormat('DD MMM YYYY') }}</strong>
                            </div>
                            <div>
                                <span>Batas Kembali:</span>
                                <strong>{{ \Carbon\Carbon::parse($item->tanggal_kembali)->locale('id')->isoFormat('DD MMM YYYY') }}</strong>
                            </div>
                        </div>

                        @if($item->pengembalian && $item->pengembalian->status === 'ditolak')
                            <div style="background: #fee2e2; border-left: 3px solid #dc2626; padding: 0.6rem 0.8rem; border-radius: 6px; font-size: 0.8rem; color: #991b1b; margin-bottom: 1rem;">
                                <strong>Pengembalian Sebelumnya Ditolak:</strong> {{ $item->pengembalian->alasan_penolakan ?? 'Silakan ajukan kembali dengan foto & keterangan yang sesuai.' }}
                            </div>
                        @endif
                    </div>

                    <div>
                        <a href="{{ route('user.pengembalian.create', $item->id) }}" class="btn-return {{ $item->pengembalian && $item->pengembalian->status === 'ditolak' ? 'btn-return-warning' : '' }}">
                            <svg viewBox="0 0 24 24" style="width: 18px; height: 18px; fill: currentColor;">
                                <path d="M19 8l-4 4h3c0 3.31-2.69 6-6 6-1.01 0-1.97-.25-2.8-.7l-1.46 1.46C8.97 19.54 10.43 20 12 20c4.42 0 8-3.58 8-8h3l-4-4zM6 12c0-3.31 2.69-6 6-6 1.01 0 1.97.25 2.8.7l1.46-1.46C15.03 4.46 13.57 4 12 4 7.58 4 4 7.58 4 12H1l4 4 4-4H6z"/>
                            </svg>
                            {{ $item->pengembalian && $item->pengembalian->status === 'ditolak' ? 'Ajukan Ulang Pengembalian' : 'Kembalikan Barang Sekarang' }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm4.59-12.42L10 14.17l-2.59-2.58L6 13l4 4 8-8z"/>
            </svg>
            <h4>Tidak Ada Peminjaman Aktif</h4>
            <p>Saat ini Anda tidak memiliki barang yang perlu dikembalikan. Semua peminjaman telah diselesaikan.</p>
        </div>
    @endif
</div>

<!-- SECTION 2: RIWAYAT PENGAJUAN PENGEMBALIAN -->
<div class="main-card">
    <div class="section-header">
        <h3>
            <span>Riwayat Pengajuan Pengembalian</span>
            <span class="badge-counter">{{ $riwayatPengembalian->count() }}</span>
        </h3>
    </div>

    @if($riwayatPengembalian->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Tgl Pengembalian</th>
                        <th>Kondisi Barang</th>
                        <th>Bukti Foto</th>
                        <th>Status Verifikasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayatPengembalian as $r)
                        <tr>
                            <td>
                                <strong>{{ $r->nama_barang }}</strong><br>
                                <small style="color: #64748b;">Kode: {{ $r->kode_barang }}</small>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($r->tanggal_pengembalian)->locale('id')->isoFormat('DD MMMM YYYY') }}
                            </td>
                            <td>
                                <span class="badge badge-kondisi-{{ $r->kondisi_pengembalian }}">
                                    @if($r->kondisi_pengembalian === 'baik')
                                        ✓ Baik (Normal)
                                    @elseif($r->kondisi_pengembalian === 'rusak_ringan')
                                        ⚠️ Rusak Ringan
                                    @else
                                        ❌ Rusak Berat
                                    @endif
                                </span>
                            </td>
                            <td>
                                @if($r->bukti_foto)
                                    <a href="{{ asset('storage/' . $r->bukti_foto) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 0.3rem; color: #2563eb; font-weight: 600; text-decoration: none;">
                                        📷 Lihat Foto
                                    </a>
                                @else
                                    <span style="color: #94a3b8;">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $r->status }}">
                                    @if($r->status === 'pending')
                                        ⏳ Menunggu Verifikasi
                                    @elseif($r->status === 'disetujui')
                                        ✅ Disetujui (Selesai)
                                    @else
                                        ❌ Ditolak
                                    @endif
                                </span>
                                @if($r->status === 'ditolak' && $r->alasan_penolakan)
                                    <br><small style="color: #dc2626; font-style: italic;">"{{ $r->alasan_penolakan }}"</small>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('user.pengembalian.show', $r->id) }}" class="btn-detail">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
            </svg>
            <h4>Belum Ada Riwayat Pengembalian</h4>
            <p>Pengajuan pengembalian barang yang Anda kirimkan akan tampil di tabel ini.</p>
        </div>
    @endif
</div>

@endsection

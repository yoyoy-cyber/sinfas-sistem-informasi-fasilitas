@extends('layouts.admin-sarana')

@section('title', 'Dashboard Admin Sarana')

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
        font-size: 1.5rem;
        font-weight: 800;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 2rem 1.5rem;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }

    .stat-icon { font-size: 2.5rem; margin-bottom: 1rem; }
    .stat-number { font-size: 2.5rem; font-weight: 800; color: #1e293b; margin-bottom: 0.5rem; }
    .stat-label { font-size: 1rem; color: #64748b; font-weight: 600; }

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
    }

    .card-header h3 {
        font-size: 1.3rem;
        color: #1e293b;
        font-weight: 700;
    }

    .permintaan-count {
        background: #fef3c7;
        color: #92400e;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

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

    .btn-approve {
        background: #16a34a;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
        margin-right: 0.5rem;
    }

    .btn-approve:hover {
        background: #15803d;
        transform: translateY(-2px);
    }

    .btn-reject {
        background: #dc2626;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-reject:hover {
        background: #b91c1c;
        transform: translateY(-2px);
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #94a3b8;
    }

    .empty-state svg {
        width: 80px;
        height: 80px;
        margin-bottom: 1rem;
        opacity: 0.3;
    }

    .empty-state h3 {
        color: #1e293b;
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #64748b;
        font-size: 0.95rem;
    }

    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

<!-- STATS CARDS -->
<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
    <div class="stat-card">
        <div class="stat-icon">📦</div>
        <div class="stat-number">{{ $stats['total_barang'] }}</div>
        <div class="stat-label">Total Barang</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-number">{{ $stats['tersedia'] }}</div>
        <div class="stat-label">Tersedia</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🔄</div>
        <div class="stat-number">{{ $stats['dipinjam'] }}</div>
        <div class="stat-label">Sedang Dipinjam</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">⚠️</div>
        <div class="stat-number">{{ $stats['rusak'] }}</div>
        <div class="stat-label">Rusak</div>
    </div>
    <div class="stat-card" style="border: 2px solid {{ $stats['pending_pengembalian'] > 0 ? '#3b82f6' : 'transparent' }};">
        <div class="stat-icon">📥</div>
        <div class="stat-number" style="color: #2563eb;">{{ $stats['pending_pengembalian'] }}</div>
        <div class="stat-label">Pengembalian Pending</div>
    </div>
</div>

<!-- PENDING PENGEMBALIAN (IF ANY) -->
@if($pengembalianTertunda->count() > 0)
<div class="content-card" style="margin-bottom: 2rem; border-left: 5px solid #3b82f6;">
    <div class="card-header">
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <span style="font-size: 1.3rem;">📥</span>
            <h3>Verifikasi Pengembalian Baru</h3>
        </div>
        <a href="{{ route('admin.pengembalian.index') }}" style="background: #2563eb; color: white; padding: 0.4rem 1rem; border-radius: 20px; font-weight: 700; font-size: 0.8rem; text-decoration: none;">
            Lihat Semua ({{ $pengembalianTertunda->count() }}) →
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Peminjam</th>
                <th>Barang</th>
                <th>Tgl Pengembalian</th>
                <th>Kondisi Dilaporkan</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengembalianTertunda->take(5) as $pkt)
            <tr>
                <td>
                    <strong>{{ $pkt->user->nama_lengkap ?? $pkt->peminjaman->nama_peminjam ?? '-' }}</strong><br>
                    <small style="color: #94a3b8;">{{ $pkt->peminjaman->no_telepon ?? '-' }}</small>
                </td>
                <td>
                    <strong>{{ $pkt->nama_barang }}</strong><br>
                    <small style="color: #64748b;">{{ $pkt->kode_barang }}</small>
                </td>
                <td>{{ \Carbon\Carbon::parse($pkt->tanggal_pengembalian)->locale('id')->isoFormat('DD MMM YYYY') }}</td>
                <td>
                    <span style="padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.8rem; font-weight: 700; background: #eff6ff; color: #1e40af;">
                        {{ ucfirst(str_replace('_', ' ', $pkt->kondisi_pengembalian)) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.pengembalian.index', ['status' => 'pending']) }}" style="background: #2563eb; color: white; padding: 0.45rem 0.9rem; border-radius: 8px; font-size: 0.8rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.3rem;">
                        🔍 Periksa & Verifikasi
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<!-- PENDING REQUESTS -->
<div class="content-card">
    <div class="card-header">
        <h3>📋 Permintaan Peminjaman Tertunda</h3>
        @if($permintaanTertunda->count() > 0)
            <span class="permintaan-count">{{ $permintaanTertunda->count() }} Permintaan</span>
        @endif
    </div>

    @if($permintaanTertunda->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Peminjam</th>
                <th>Barang</th>
                <th>Tanggal</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permintaanTertunda as $p)
            <tr>
                <td>
                    <strong>{{ $p->nama_peminjam }}</strong><br>
                    <small style="color: #94a3b8;">{{ $p->no_telepon ?? '-' }}</small>
                </td>
                <td>{{ $p->nama_barang }}</td>
                <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->locale('id')->isoFormat('DD MMM YYYY') }}</td>
                <td>
                    <button type="button" class="btn-approve" onclick="openApproveModal('/admin/peminjaman/{{ $p->id }}/approve', '{{ addslashes($p->nama_peminjam) }}')">
                        ✓ Setuju
                    </button>
                    <button type="button" class="btn-reject" onclick="openRejectModal('/admin/peminjaman/{{ $p->id }}/reject', '{{ addslashes($p->nama_peminjam) }}')">
                        ✕ Tolak
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">
        <svg viewBox="0 0 24 24" fill="currentColor">
            <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
        </svg>
        <h3>Tidak Ada Permintaan</h3>
        <p>Saat ini tidak ada permintaan peminjaman yang menunggu persetujuan</p>
    </div>
    @endif
</div>

@endsection
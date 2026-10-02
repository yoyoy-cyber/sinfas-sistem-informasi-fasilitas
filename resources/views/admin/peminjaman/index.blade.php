@extends('layouts.admin-sarana')

@section('title', 'Data Peminjaman')

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
        white-space: nowrap;
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
        margin-bottom: 1.5rem;
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

    .tab-container {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .tab-btn {
        padding: 0.7rem 1.3rem;
        border: 2px solid #e2e8f0;
        background: white;
        color: #475569;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }

    .tab-btn:hover {
        border-color: #1e40af;
        color: #1e40af;
    }

    .tab-btn.active {
        background: #1e40af;
        color: white;
        border-color: #1e40af;
    }

    .badge-count {
        background: #e2e8f0;
        color: #334155;
        padding: 0.15rem 0.55rem;
        border-radius: 12px;
        font-size: 0.75rem;
        margin-left: 0.4rem;
        font-weight: 700;
    }

    .tab-btn.active .badge-count {
        background: rgba(255, 255, 255, 0.25);
        color: white;
    }

    .badge-danger {
        background: #ef4444 !important;
        color: white !important;
    }

    .row-overdue {
        background-color: #fef2f2 !important;
    }

    .status-terlambat {
        background: #dc2626 !important;
        color: white;
        box-shadow: 0 2px 6px rgba(220, 38, 38, 0.3);
        animation: pulseAlert 2s infinite alternate;
    }

    @keyframes pulseAlert {
        0% { opacity: 0.85; transform: scale(1); }
        100% { opacity: 1; transform: scale(1.03); }
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

    .status-pending { background: #d97706; }
    .status-disetujui { background: #16a34a; }
    .status-ditolak { background: #dc2626; }
    .status-dikembalikan { background: #6b7280; }

    .btn-alasan {
        background: #3b82f6;
        color: white;
        border: none;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-alasan:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }

    .action-buttons { display: flex; gap: 0.5rem; }

    .btn-action {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-action:hover { transform: translateY(-2px); }
    .btn-approve { background: #16a34a; color: white; }
    .btn-reject { background: #ef4444; color: white; }

    /* ===== MOBILE CARDS ===== */
    .mobile-cards { display: none; }

    .pinjam-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.2rem;
        margin-bottom: 1rem;
        background: #fafafa;
        transition: all 0.2s;
    }

    .pinjam-card:last-child { margin-bottom: 0; }
    .pinjam-card:hover { box-shadow: 0 4px 15px rgba(0,0,0,0.08); background: white; }

    .pinjam-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.9rem;
    }

    .pinjam-card-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
    }

    .pinjam-card-sub {
        font-size: 0.78rem;
        color: #94a3b8;
        margin-top: 0.15rem;
    }

    .pinjam-card-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
        margin-bottom: 0.9rem;
    }

    .info-row {
        background: #f8fafc;
        border-radius: 10px;
        padding: 0.6rem 0.8rem;
    }

    .info-label {
        font-size: 0.68rem;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        font-weight: 600;
    }

    .info-value {
        font-size: 0.85rem;
        color: #334155;
        font-weight: 600;
        margin-top: 0.1rem;
    }

    .pinjam-card-footer {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .card-btn {
        flex: 1;
        min-width: 80px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        padding: 0.6rem 0.5rem;
        border-radius: 10px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        text-decoration: none;
        transition: all 0.2s;
    }

    .card-btn:hover { transform: translateY(-1px); opacity: 0.9; }

    .card-btn svg {
        width: 14px;
        height: 14px;
        fill: currentColor;
        flex-shrink: 0;
    }

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

    .empty-state svg { width: 80px; height: 80px; margin-bottom: 1rem; opacity: 0.3; }
    .empty-state h3 { color: #1e293b; font-size: 1.2rem; margin-bottom: 0.5rem; }
    .empty-state p { color: #64748b; }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .table-container { display: none; }
        .mobile-cards { display: block; }
        .content-card { padding: 1.2rem; }
        .page-title-section h2 { font-size: 1.4rem; }
        .tab-btn { padding: 0.6rem 0.9rem; font-size: 0.8rem; }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div class="page-title-section">
        <span style="font-size:1.8rem;">📋</span>
        <h2>Data Peminjaman</h2>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn-back">← Dashboard</a>
</div>

@if(session('success'))
    <div class="alert-box alert-success">✅ {{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert-box alert-error">⚠️ {{ session('error') }}</div>
@endif

<div class="tab-container">
    <a href="{{ route('admin.peminjaman.index', ['status' => 'pending']) }}" class="tab-btn {{ $status === 'pending' ? 'active' : '' }}">
        Menunggu <span class="badge-count">{{ $counts['pending'] ?? 0 }}</span>
    </a>
    <a href="{{ route('admin.peminjaman.index', ['status' => 'sedang_dipinjam']) }}" class="tab-btn {{ $status === 'sedang_dipinjam' || $status === 'disetujui' ? 'active' : '' }}">
        Sedang Dipinjam <span class="badge-count">{{ $counts['sedang_dipinjam'] ?? 0 }}</span>
    </a>
    <a href="{{ route('admin.peminjaman.index', ['status' => 'terlambat']) }}" class="tab-btn {{ $status === 'terlambat' ? 'active' : '' }}" style="{{ ($counts['terlambat'] ?? 0) > 0 ? 'border-color: #ef4444; color: #dc2626;' : '' }}">
        🚨 Terlambat <span class="badge-count {{ ($counts['terlambat'] ?? 0) > 0 ? 'badge-danger' : '' }}">{{ $counts['terlambat'] ?? 0 }}</span>
    </a>
    <a href="{{ route('admin.peminjaman.index', ['status' => 'ditolak']) }}" class="tab-btn {{ $status === 'ditolak' ? 'active' : '' }}">
        Ditolak <span class="badge-count">{{ $counts['ditolak'] ?? 0 }}</span>
    </a>
</div>

<div class="content-card">
    <div class="card-header">
        <h3>
            Daftar Peminjaman 
            @if($status === 'pending')
                (Menunggu Verifikasi)
            @elseif($status === 'sedang_dipinjam' || $status === 'disetujui')
                (Sedang Dipinjam / Aktif)
            @elseif($status === 'terlambat')
                <span style="color:#dc2626;">(Terlambat Mengembalikan)</span>
            @elseif($status === 'ditolak')
                (Ditolak)
            @endif
        </h3>
    </div>

    @if($peminjaman->count() > 0)

    {{-- ===== DESKTOP TABLE ===== --}}
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Peminjam</th>
                    <th>Barang</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>No. HP</th>
                    <th>Alasan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjaman as $p)
                @php
                    $isOverdue = in_array($p->status, ['disetujui', 'dipinjam', 'sedang_dipinjam']) && \Carbon\Carbon::parse($p->tanggal_kembali)->endOfDay()->isPast();
                    $daysLate = $isOverdue ? \Carbon\Carbon::parse($p->tanggal_kembali)->startOfDay()->diffInDays(now()->startOfDay()) : 0;
                @endphp
                <tr class="{{ $isOverdue ? 'row-overdue' : '' }}">
                    <td>
                        <strong>{{ $p->nama_peminjam ?? $p->user->nama_lengkap ?? '-' }}</strong><br>
                        <small style="color: #94a3b8;">{{ ucfirst(str_replace('_', ' ', $p->role_peminjam ?? $p->user->role ?? '-')) }}</small>
                    </td>
                    <td>
                        <strong>{{ $p->nama_barang ?? $p->barang->nama_barang ?? '-' }}</strong><br>
                        <small style="color: #94a3b8;">{{ $p->kode_barang ?? $p->barang->kode_barang ?? '-' }}</small>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->locale('id')->isoFormat('DD MMM YYYY') }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($p->tanggal_kembali)->locale('id')->isoFormat('DD MMM YYYY') }}
                        @if($isOverdue)
                            <br><small style="color:#dc2626; font-weight:700;">(Lewat {{ $daysLate }} Hari)</small>
                        @endif
                    </td>
                    <td>{{ $p->no_telepon ?? '-' }}</td>
                    <td>
                        @if($p->alasan_keperluan)
                            <button class="btn-alasan" onclick="showAlasan({{ $p->id }}, '{{ addslashes($p->alasan_keperluan) }}')">Lihat Alasan</button>
                        @else
                            <span style="color: #94a3b8;">-</span>
                        @endif
                    </td>
                    <td>
                        @if($isOverdue)
                            <span class="status-badge status-terlambat">
                                🚨 Terlambat {{ $daysLate }} Hari
                            </span>
                        @else
                            <span class="status-badge status-{{ $p->status === 'disetujui' ? 'disetujui' : $p->status }}">
                                {{ $p->status === 'pending' ? 'Menunggu' : (in_array($p->status, ['disetujui', 'dipinjam', 'sedang_dipinjam']) ? 'Sedang Dipinjam' : ($p->status === 'ditolak' ? 'Ditolak' : 'Dikembalikan')) }}
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($p->status === 'pending')
                            <div class="action-buttons">
                                <button type="button" class="btn-action btn-approve"
                                    onclick="openApproveModal('/admin/peminjaman/{{ $p->id }}/approve', '{{ addslashes($p->nama_peminjam ?? $p->user->nama_lengkap ?? 'Peminjam') }}')">
                                    ✓ Setuju
                                </button>
                                <button type="button" class="btn-action btn-reject"
                                    onclick="openRejectModal('/admin/peminjaman/{{ $p->id }}/reject', '{{ addslashes($p->nama_peminjam ?? $p->user->nama_lengkap ?? 'Peminjam') }}')">
                                    ✕ Tolak
                                </button>
                            </div>
                        @else
                            <span style="color: #94a3b8; font-size: 0.85rem;">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ===== MOBILE CARDS ===== --}}
    <div class="mobile-cards">
        @foreach($peminjaman as $p)
        @php
            $isOverdue = in_array($p->status, ['disetujui', 'dipinjam', 'sedang_dipinjam']) && \Carbon\Carbon::parse($p->tanggal_kembali)->endOfDay()->isPast();
            $daysLate = $isOverdue ? \Carbon\Carbon::parse($p->tanggal_kembali)->startOfDay()->diffInDays(now()->startOfDay()) : 0;
        @endphp
        <div class="pinjam-card {{ $isOverdue ? 'row-overdue' : '' }}">
            {{-- Header: barang + status --}}
            <div class="pinjam-card-header">
                <div>
                    <div class="pinjam-card-title">{{ $p->nama_barang ?? $p->barang->nama_barang ?? '-' }}</div>
                    <div class="pinjam-card-sub">{{ $p->kode_barang ?? $p->barang->kode_barang ?? '-' }}</div>
                </div>
                @if($isOverdue)
                    <span class="status-badge status-terlambat" style="font-size:0.72rem; padding:0.3rem 0.7rem; white-space:nowrap;">
                        🚨 Terlambat {{ $daysLate }} Hari
                    </span>
                @else
                    <span class="status-badge status-{{ $p->status === 'disetujui' ? 'disetujui' : $p->status }}" style="font-size:0.72rem; padding:0.3rem 0.7rem; white-space:nowrap;">
                        {{ $p->status === 'pending' ? 'Menunggu' : (in_array($p->status, ['disetujui', 'dipinjam', 'sedang_dipinjam']) ? 'Sedang Dipinjam' : ($p->status === 'ditolak' ? 'Ditolak' : 'Dikembalikan')) }}
                    </span>
                @endif
            </div>

            {{-- Info grid --}}
            <div class="pinjam-card-info">
                <div class="info-row">
                    <div class="info-label">Peminjam</div>
                    <div class="info-value">{{ $p->nama_peminjam ?? $p->user->nama_lengkap ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">No. HP</div>
                    <div class="info-value">{{ $p->no_telepon ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tgl Pinjam</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->locale('id')->isoFormat('DD MMM YY') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tgl Kembali</div>
                    <div class="info-value">
                        {{ \Carbon\Carbon::parse($p->tanggal_kembali)->locale('id')->isoFormat('DD MMM YY') }}
                        @if($isOverdue)
                            <span style="color:#dc2626; font-size:0.7rem; display:block; font-weight:700;">(Lewat {{ $daysLate }} Hari)</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="pinjam-card-footer">
                @if($p->alasan_keperluan)
                    <button class="card-btn" style="background:#eff6ff; color:#1e40af;"
                        onclick="showAlasan({{ $p->id }}, '{{ addslashes($p->alasan_keperluan) }}')">
                        📝 Alasan
                    </button>
                @endif
                @if($p->status === 'pending')
                    <button type="button" class="card-btn" style="background:#16a34a; color:white;"
                        onclick="openApproveModal('/admin/peminjaman/{{ $p->id }}/approve', '{{ addslashes($p->nama_peminjam ?? $p->user->nama_lengkap ?? 'Peminjam') }}')">
                        ✓ Setuju
                    </button>
                    <button type="button" class="card-btn" style="background:#ef4444; color:white;"
                        onclick="openRejectModal('/admin/peminjaman/{{ $p->id }}/reject', '{{ addslashes($p->nama_peminjam ?? $p->user->nama_lengkap ?? 'Peminjam') }}')">
                        ✕ Tolak
                    </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    @else
    <div class="empty-state">
        <svg viewBox="0 0 24 24" fill="currentColor">
            <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
        </svg>
        <h3>Tidak Ada Data Peminjaman</h3>
        <p>Belum ada permintaan peminjaman pada status ini.</p>
    </div>
    @endif
</div>

<!-- MODAL LIHAT ALASAN -->
<div id="alasanModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header" style="background: #3b82f6;">
            <h3>📝 ALASAN KEPERLUAN PEMINJAMAN</h3>
        </div>
        <div class="modal-body column-layout">
            <div id="alasanText" style="background: #f8fafc; padding: 1.2rem; border-radius: 10px; color: #1e293b; line-height: 1.6; font-size: 0.95rem; width: 100%; border-left: 4px solid #3b82f6;"></div>
        </div>
        <div class="modal-footer">
            <button class="btn-modal btn-modal-tidak" onclick="closeAlasanModal()">TUTUP</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function showAlasan(id, alasan) {
        document.getElementById('alasanText').innerText = alasan;
        document.getElementById('alasanModal').classList.add('active');
    }

    function closeAlasanModal() {
        document.getElementById('alasanModal').classList.remove('active');
    }
</script>
@endsection
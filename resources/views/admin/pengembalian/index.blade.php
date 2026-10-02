@extends('layouts.admin-sarana')

@section('title', 'Verifikasi Pengembalian')

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

    .page-title-section {
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .page-title-section h2 {
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

    .tab-container {
        display: flex;
        gap: 0.8rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .tab-btn {
        padding: 0.8rem 1.4rem;
        border: 2px solid #e2e8f0;
        background: white;
        color: #475569;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tab-btn:hover { border-color: #1e40af; color: #1e40af; }

    .tab-btn.active {
        background: #1e40af;
        color: white;
        border-color: #1e40af;
        box-shadow: 0 4px 12px rgba(30, 64, 175, 0.25);
    }

    .tab-counter {
        background: rgba(0,0,0,0.08);
        font-size: 0.75rem;
        padding: 0.15rem 0.55rem;
        border-radius: 20px;
    }

    .tab-btn.active .tab-counter {
        background: rgba(255,255,255,0.25);
        color: white;
    }

    /* ===== DESKTOP TABLE ===== */
    .table-container { overflow-x: auto; }

    table { width: 100%; border-collapse: collapse; }
    thead { background: #f8fafc; }

    th {
        padding: 1rem;
        text-align: left;
        font-weight: 700;
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

    tbody tr:hover { background: #f8fafc; }

    .status-badge {
        padding: 0.4rem 0.9rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-block;
    }

    .status-pending { background: #fef3c7; color: #92400e; }
    .status-disetujui { background: #dcfce7; color: #166534; }
    .status-ditolak { background: #fee2e2; color: #991b1b; }

    .badge-kondisi-baik { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .badge-kondisi-rusak_ringan { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .badge-kondisi-rusak_berat { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

    .photo-thumb {
        width: 55px;
        height: 55px;
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        overflow: hidden;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        transition: transform 0.2s, border-color 0.2s;
    }

    .photo-thumb:hover { transform: scale(1.08); border-color: #2563eb; }
    .photo-thumb img { width: 100%; height: 100%; object-fit: cover; }

    .btn-alasan {
        background: #eff6ff;
        color: #1e40af;
        border: 1px solid #bfdbfe;
        padding: 0.35rem 0.7rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-block;
    }

    .btn-alasan:hover { background: #1e40af; color: white; }

    .action-buttons { display: flex; gap: 0.5rem; }

    .btn-action {
        padding: 0.55rem 1rem;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .btn-action:hover { transform: translateY(-2px); }
    .btn-approve { background: #16a34a; color: white; box-shadow: 0 2px 8px rgba(22,163,74,0.3); }
    .btn-approve:hover { background: #15803d; }
    .btn-reject { background: #dc2626; color: white; box-shadow: 0 2px 8px rgba(220,38,38,0.3); }
    .btn-reject:hover { background: #b91c1c; }

    /* ===== MOBILE CARDS ===== */
    .mobile-cards { display: none; }

    .kembali-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.2rem;
        margin-bottom: 1rem;
        background: #fafafa;
        transition: all 0.2s;
    }

    .kembali-card:last-child { margin-bottom: 0; }
    .kembali-card:hover { box-shadow: 0 4px 15px rgba(0,0,0,0.08); background: white; }

    .kembali-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.9rem;
        gap: 0.5rem;
    }

    .kembali-card-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        flex: 1;
    }

    .kembali-card-sub {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 0.15rem;
    }

    .kembali-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin-bottom: 0.9rem;
    }

    .kembali-card-info {
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

    .kembali-card-footer {
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
        padding: 0.65rem 0.5rem;
        border-radius: 10px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        text-decoration: none;
        transition: all 0.2s;
    }

    .card-btn:hover { transform: translateY(-1px); opacity: 0.9; }

    .kembali-photo-thumb {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
        overflow: hidden;
        cursor: pointer;
        flex-shrink: 0;
    }

    .kembali-photo-thumb img { width: 100%; height: 100%; object-fit: cover; }

    .kembali-verif-info {
        background: #f8fafc;
        border-radius: 10px;
        padding: 0.7rem;
        font-size: 0.8rem;
        color: #64748b;
        grid-column: 1 / -1;
    }

    /* ===== ALERTS ===== */
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
        padding: 3.5rem 1.5rem;
        color: #94a3b8;
    }

    .empty-state svg { width: 80px; height: 80px; margin-bottom: 1rem; opacity: 0.3; }
    .empty-state h3 { color: #1e293b; font-size: 1.2rem; margin-bottom: 0.4rem; }
    .empty-state p { color: #64748b; }

    .photo-modal-img {
        max-width: 100%;
        max-height: 70vh;
        border-radius: 12px;
        object-fit: contain;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .table-container { display: none; }
        .mobile-cards { display: block; }
        .content-card { padding: 1.2rem; }
        .page-title-section h2 { font-size: 1.3rem; }
        .tab-btn { padding: 0.6rem 0.8rem; font-size: 0.8rem; }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div class="page-title-section">
        <span style="font-size: 2rem;">📥</span>
        <h2>Verifikasi Pengembalian</h2>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn-back">← Dashboard</a>
</div>

@if(session('success'))
    <div class="alert-box alert-success">✅ {{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert-box alert-error">⚠️ {{ session('error') }}</div>
@endif

<!-- TAB STATUS FILTER -->
<div class="tab-container">
    <a href="{{ route('admin.pengembalian.index', ['status' => 'pending']) }}" class="tab-btn {{ $status === 'pending' ? 'active' : '' }}">
        <span>Menunggu</span>
        <span class="tab-counter">{{ $counts['pending'] }}</span>
    </a>
    <a href="{{ route('admin.pengembalian.index', ['status' => 'disetujui']) }}" class="tab-btn {{ $status === 'disetujui' ? 'active' : '' }}">
        <span>Selesai</span>
        <span class="tab-counter">{{ $counts['disetujui'] }}</span>
    </a>
    <a href="{{ route('admin.pengembalian.index', ['status' => 'ditolak']) }}" class="tab-btn {{ $status === 'ditolak' ? 'active' : '' }}">
        <span>Ditolak</span>
        <span class="tab-counter">{{ $counts['ditolak'] }}</span>
    </a>
</div>

<div class="content-card">
    @if($pengembalian->count() > 0)

    {{-- ===== DESKTOP TABLE ===== --}}
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Peminjam</th>
                    <th>Barang</th>
                    <th>Tgl Pengembalian</th>
                    <th>Kondisi Barang</th>
                    <th>Catatan</th>
                    <th>Bukti Foto</th>
                    <th>Status</th>
                    <th>Aksi / Info</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengembalian as $p)
                <tr>
                    <td>
                        <strong>{{ $p->user->nama_lengkap ?? $p->peminjaman->nama_peminjam ?? '-' }}</strong><br>
                        <small style="color: #64748b;">{{ ucfirst(str_replace('_', ' ', $p->user->role ?? $p->peminjaman->role_peminjam ?? '-')) }}</small><br>
                        <small style="color: #94a3b8;">📞 {{ $p->peminjaman->no_telepon ?? '-' }}</small>
                    </td>
                    <td>
                        <strong>{{ $p->nama_barang }}</strong><br>
                        <small style="color: #64748b;">Kode: {{ $p->kode_barang }}</small><br>
                        <small style="color: #94a3b8;">{{ $p->barang->kategori->nama_kategori ?? '-' }}</small>
                    </td>
                    <td>
                        <strong>{{ \Carbon\Carbon::parse($p->tanggal_pengembalian)->locale('id')->isoFormat('DD MMM YYYY') }}</strong><br>
                        @if($p->peminjaman)
                            <small style="color: #64748b;">Pinjam: {{ \Carbon\Carbon::parse($p->peminjaman->tanggal_pinjam)->locale('id')->isoFormat('DD MMM') }}</small>
                        @endif
                    </td>
                    <td>
                        <span class="status-badge badge-kondisi-{{ $p->kondisi_pengembalian }}">
                            @if($p->kondisi_pengembalian === 'baik') ✓ Baik
                            @elseif($p->kondisi_pengembalian === 'rusak_ringan') ⚠️ Rusak Ringan
                            @else ❌ Rusak Berat @endif
                        </span>
                    </td>
                    <td>
                        @if($p->catatan_kondisi)
                            <button type="button" class="btn-alasan"
                                onclick="showCatatan('{{ addslashes($p->catatan_kondisi) }}', '{{ addslashes($p->nama_barang) }}')">
                                📝 Lihat
                            </button>
                        @else
                            <span style="color: #94a3b8;">-</span>
                        @endif
                    </td>
                    <td>
                        @if($p->bukti_foto)
                            <div class="photo-thumb"
                                onclick="openPhotoModal('{{ asset('storage/' . $p->bukti_foto) }}', '{{ addslashes($p->nama_barang) }}', '{{ addslashes($p->user->nama_lengkap ?? 'Peminjam') }}')">
                                <img src="{{ asset('storage/' . $p->bukti_foto) }}" alt="Bukti Foto">
                            </div>
                        @else
                            <span style="color: #94a3b8; font-size: 0.85rem;">Tidak ada</span>
                        @endif
                    </td>
                    <td>
                        <span class="status-badge status-{{ $p->status }}">
                            {{ $p->status === 'pending' ? '⏳ Menunggu' : ($p->status === 'disetujui' ? '✅ Disetujui' : '❌ Ditolak') }}
                        </span>
                        @if($p->status === 'ditolak' && $p->alasan_penolakan)
                            <br><small style="color: #dc2626; font-style: italic;">"{{ $p->alasan_penolakan }}"</small>
                        @endif
                    </td>
                    <td>
                        @if($p->status === 'pending')
                            <div class="action-buttons">
                                <button type="button" class="btn-action btn-approve"
                                    onclick="openVerifApproveModal('/admin/pengembalian/{{ $p->id }}/approve', '{{ addslashes($p->user->nama_lengkap ?? $p->peminjaman->nama_peminjam ?? 'Peminjam') }}', '{{ addslashes($p->nama_barang) }}', '{{ $p->kondisi_pengembalian }}')">
                                    ✓ Setuju
                                </button>
                                <button type="button" class="btn-action btn-reject"
                                    onclick="openVerifRejectModal('/admin/pengembalian/{{ $p->id }}/reject', '{{ addslashes($p->user->nama_lengkap ?? $p->peminjaman->nama_peminjam ?? 'Peminjam') }}', '{{ addslashes($p->nama_barang) }}')">
                                    ✕ Tolak
                                </button>
                            </div>
                        @else
                            <div style="font-size: 0.8rem; color: #64748b;">
                                <div>Oleh: <strong>{{ $p->verifikasi_oleh ?? '-' }}</strong></div>
                                <div>{{ $p->tanggal_verifikasi ? \Carbon\Carbon::parse($p->tanggal_verifikasi)->locale('id')->isoFormat('DD MMM YYYY HH:mm') : '-' }}</div>
                            </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ===== MOBILE CARDS ===== --}}
    <div class="mobile-cards">
        @foreach($pengembalian as $p)
        <div class="kembali-card">
            {{-- Header: nama barang + status --}}
            <div class="kembali-card-header">
                <div style="flex:1;">
                    <div class="kembali-card-title">{{ $p->nama_barang }}</div>
                    <div class="kembali-card-sub">{{ $p->kode_barang }} · {{ $p->barang->kategori->nama_kategori ?? '-' }}</div>
                </div>
                <span class="status-badge status-{{ $p->status }}" style="font-size:0.72rem; padding:0.3rem 0.6rem; white-space:nowrap;">
                    {{ $p->status === 'pending' ? '⏳ Menunggu' : ($p->status === 'disetujui' ? '✅ OK' : '❌ Ditolak') }}
                </span>
            </div>

            {{-- Kondisi badge --}}
            <div class="kembali-badges">
                <span class="status-badge badge-kondisi-{{ $p->kondisi_pengembalian }}" style="font-size:0.75rem; padding:0.3rem 0.7rem;">
                    @if($p->kondisi_pengembalian === 'baik') ✓ Baik
                    @elseif($p->kondisi_pengembalian === 'rusak_ringan') ⚠️ Rusak Ringan
                    @else ❌ Rusak Berat @endif
                </span>
                @if($p->bukti_foto)
                    <div class="kembali-photo-thumb"
                        onclick="openPhotoModal('{{ asset('storage/' . $p->bukti_foto) }}', '{{ addslashes($p->nama_barang) }}', '{{ addslashes($p->user->nama_lengkap ?? 'Peminjam') }}')">
                        <img src="{{ asset('storage/' . $p->bukti_foto) }}" alt="Bukti">
                    </div>
                @endif
            </div>

            {{-- Info grid --}}
            <div class="kembali-card-info">
                <div class="info-row">
                    <div class="info-label">Peminjam</div>
                    <div class="info-value">{{ $p->user->nama_lengkap ?? $p->peminjaman->nama_peminjam ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">No. HP</div>
                    <div class="info-value">{{ $p->peminjaman->no_telepon ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tgl Kembali</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($p->tanggal_pengembalian)->locale('id')->isoFormat('DD MMM YY') }}</div>
                </div>
                @if($p->peminjaman)
                <div class="info-row">
                    <div class="info-label">Tgl Pinjam</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($p->peminjaman->tanggal_pinjam)->locale('id')->isoFormat('DD MMM YY') }}</div>
                </div>
                @endif
                @if($p->status !== 'pending' && $p->verifikasi_oleh)
                <div class="info-row" style="grid-column: 1 / -1;">
                    <div class="info-label">Diverifikasi Oleh</div>
                    <div class="info-value">{{ $p->verifikasi_oleh }} · {{ $p->tanggal_verifikasi ? \Carbon\Carbon::parse($p->tanggal_verifikasi)->locale('id')->isoFormat('DD MMM YY HH:mm') : '-' }}</div>
                </div>
                @endif
                @if($p->status === 'ditolak' && $p->alasan_penolakan)
                <div class="info-row" style="grid-column: 1 / -1; border-left: 3px solid #dc2626;">
                    <div class="info-label">Alasan Penolakan</div>
                    <div class="info-value" style="color:#dc2626; font-style:italic;">{{ $p->alasan_penolakan }}</div>
                </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="kembali-card-footer">
                @if($p->catatan_kondisi)
                    <button type="button" class="card-btn" style="background:#eff6ff; color:#1e40af;"
                        onclick="showCatatan('{{ addslashes($p->catatan_kondisi) }}', '{{ addslashes($p->nama_barang) }}')">
                        📝 Catatan
                    </button>
                @endif
                @if($p->status === 'pending')
                    <button type="button" class="card-btn" style="background:#16a34a; color:white;"
                        onclick="openVerifApproveModal('/admin/pengembalian/{{ $p->id }}/approve', '{{ addslashes($p->user->nama_lengkap ?? $p->peminjaman->nama_peminjam ?? 'Peminjam') }}', '{{ addslashes($p->nama_barang) }}', '{{ $p->kondisi_pengembalian }}')">
                        ✓ Setuju
                    </button>
                    <button type="button" class="card-btn" style="background:#dc2626; color:white;"
                        onclick="openVerifRejectModal('/admin/pengembalian/{{ $p->id }}/reject', '{{ addslashes($p->user->nama_lengkap ?? $p->peminjaman->nama_peminjam ?? 'Peminjam') }}', '{{ addslashes($p->nama_barang) }}')">
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
        <h3>Tidak Ada Data Pengembalian</h3>
        <p>Belum ada pengajuan pengembalian pada tab ini.</p>
    </div>
    @endif
</div>

<!-- ===== MODAL ZOOM FOTO ===== -->
<div id="photoModal" class="modal-overlay">
    <div class="modal-box" style="max-width: 600px;">
        <div class="modal-header" style="background: #1e40af;">
            <h3 id="photoModalTitle">📷 BUKTI FOTO PENGEMBALIAN</h3>
        </div>
        <div class="modal-body" style="display: flex; flex-direction: column; align-items: center; padding: 1.5rem;">
            <img id="photoModalImg" src="" alt="Bukti Pengembalian" class="photo-modal-img">
            <p id="photoModalSubtitle" style="font-size: 0.85rem; color: #64748b; margin-top: 0.8rem;"></p>
        </div>
        <div class="modal-footer">
            <a id="photoModalDownload" href="" target="_blank" class="btn-modal btn-modal-ya" style="text-decoration: none;">Buka Ukuran Asli</a>
            <button class="btn-modal btn-modal-tidak" onclick="closePhotoModal()">TUTUP</button>
        </div>
    </div>
</div>

<!-- ===== MODAL CATATAN ===== -->
<div id="catatanModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header" style="background: #3b82f6;">
            <h3>📝 CATATAN KONDISI DARI PEMINJAM</h3>
        </div>
        <div class="modal-body column-layout">
            <p id="catatanItemName" style="font-size: 0.9rem; color: #64748b; margin-bottom: 0.5rem;"></p>
            <div id="catatanText" style="background: #f8fafc; padding: 1.2rem; border-radius: 10px; color: #1e293b; line-height: 1.6; font-size: 0.95rem; width: 100%; border-left: 4px solid #3b82f6;"></div>
        </div>
        <div class="modal-footer">
            <button class="btn-modal btn-modal-tidak" onclick="closeCatatanModal()">TUTUP</button>
        </div>
    </div>
</div>

<!-- ===== MODAL SETUJUI PENGEMBALIAN ===== -->
<div class="modal-overlay" id="verifApproveModal">
    <div class="modal-box">
        <div class="modal-header header-success">
            <h3>KONFIRMASI PERSETUJUAN PENGEMBALIAN</h3>
        </div>
        <div class="modal-body">
            <div class="warning-icon">
                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="42" fill="#16a34a" stroke="#15803d" stroke-width="4"/>
                    <path d="M30 50 L45 65 L70 35" stroke="white" stroke-width="8" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <p id="verifApproveText" style="font-weight: 700; font-size: 1.05rem; color: #1e293b;">Setujui pengembalian barang ini?</p>
                <p id="verifApproveKondisi" style="font-size: 0.85rem; color: #475569; margin-top: 0.4rem;"></p>
                <p class="info-text" style="font-size: 0.8rem; color: #64748b; margin-top: 0.4rem;">Status peminjaman akan diselesaikan dan stok barang diperbarui otomatis.</p>
            </div>
        </div>
        <div class="modal-footer">
            <form id="verifApproveForm" method="POST">
                @csrf
                <button type="submit" class="btn-modal btn-modal-approve">YA, SETUJUI</button>
            </form>
            <button type="button" class="btn-modal btn-modal-tidak" onclick="closeVerifApproveModal()">BATAL</button>
        </div>
    </div>
</div>

<!-- ===== MODAL TOLAK PENGEMBALIAN ===== -->
<div class="modal-overlay" id="verifRejectModal">
    <div class="modal-box">
        <div class="modal-header header-danger">
            <h3>TOLAK PENGEMBALIAN</h3>
        </div>
        <form id="verifRejectForm" method="POST">
            @csrf
            <div class="modal-body column-layout">
                <div style="display: flex; align-items: center; gap: 1rem; width: 100%;">
                    <div class="warning-icon">
                        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <polygon points="50,8 95,88 5,88" fill="#facc15" stroke="#dc2626" stroke-width="5" stroke-linejoin="round"/>
                            <text x="50" y="72" font-size="50" font-weight="900" text-anchor="middle" fill="#1f2937">!</text>
                        </svg>
                    </div>
                    <p id="verifRejectText" style="font-size: 1.05rem; font-weight: 700; color: #1e293b;">Tolak pengembalian ini?</p>
                </div>
                <div style="width: 100%; margin-top: 1rem;">
                    <label style="font-size: 0.9rem; font-weight: 700; color: #1f2937; display: block; margin-bottom: 0.5rem;">
                        Alasan Penolakan <span style="color: #dc2626;">*</span>
                    </label>
                    <textarea name="alasan" id="verifRejectAlasan"
                        placeholder="Contoh: Bukti foto tidak jelas / barang belum dikembalikan secara fisik..."
                        style="width: 100%; height: 100px; padding: 0.8rem; border: 2px solid #e2e8f0; border-radius: 10px; font-family: inherit; font-size: 0.9rem;"
                        required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn-modal btn-modal-reject">KIRIM PENOLAKAN</button>
                <button type="button" class="btn-modal btn-modal-tidak" onclick="closeVerifRejectModal()">BATAL</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openPhotoModal(imageUrl, itemName, userName) {
        document.getElementById('photoModalImg').src = imageUrl;
        document.getElementById('photoModalDownload').href = imageUrl;
        document.getElementById('photoModalSubtitle').innerText = 'Barang: ' + itemName + ' | Peminjam: ' + userName;
        document.getElementById('photoModal').classList.add('active');
    }

    function closePhotoModal() {
        document.getElementById('photoModal').classList.remove('active');
    }

    function showCatatan(catatan, itemName) {
        document.getElementById('catatanItemName').innerText = 'Barang: ' + itemName;
        document.getElementById('catatanText').innerText = catatan;
        document.getElementById('catatanModal').classList.add('active');
    }

    function closeCatatanModal() {
        document.getElementById('catatanModal').classList.remove('active');
    }

    function openVerifApproveModal(url, peminjamName, itemName, kondisi) {
        document.getElementById('verifApproveForm').action = url;
        document.getElementById('verifApproveText').innerText = 'Setujui pengembalian "' + itemName + '" dari ' + peminjamName + '?';
        let labelKondisi = 'Baik';
        if (kondisi === 'rusak_ringan') labelKondisi = 'Rusak Ringan';
        if (kondisi === 'rusak_berat') labelKondisi = 'Rusak Berat';
        document.getElementById('verifApproveKondisi').innerText = 'Kondisi barang dilaporkan: ' + labelKondisi;
        document.getElementById('verifApproveModal').classList.add('active');
    }

    function closeVerifApproveModal() {
        document.getElementById('verifApproveModal').classList.remove('active');
    }

    function openVerifRejectModal(url, peminjamName, itemName) {
        document.getElementById('verifRejectForm').action = url;
        document.getElementById('verifRejectText').innerText = 'Tolak pengembalian "' + itemName + '" dari ' + peminjamName;
        document.getElementById('verifRejectAlasan').value = '';
        document.getElementById('verifRejectModal').classList.add('active');
        setTimeout(() => document.getElementById('verifRejectAlasan').focus(), 300);
    }

    function closeVerifRejectModal() {
        document.getElementById('verifRejectModal').classList.remove('active');
    }

    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('active');
        });
    });
</script>
@endsection

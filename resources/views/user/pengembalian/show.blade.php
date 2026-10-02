@extends('layouts.dashboard-user')

@section('title', 'Detail Pengembalian')

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
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: white;
        color: #1e40af;
        padding: 0.8rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    .detail-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }

    .status-banner {
        padding: 1.2rem 1.5rem;
        border-radius: 14px;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .status-banner.pending {
        background: #fef3c7;
        color: #92400e;
        border-left: 5px solid #f59e0b;
    }

    .status-banner.disetujui {
        background: #dcfce7;
        color: #166534;
        border-left: 5px solid #16a34a;
    }

    .status-banner.ditolak {
        background: #fee2e2;
        color: #991b1b;
        border-left: 5px solid #dc2626;
    }

    .banner-icon {
        font-size: 2rem;
        flex-shrink: 0;
    }

    .banner-text h4 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.2rem;
    }

    .banner-text p {
        font-size: 0.9rem;
        margin: 0;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1.2rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .info-item label {
        display: block;
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.3rem;
    }

    .info-item .val {
        font-size: 0.95rem;
        color: #1e293b;
        font-weight: 600;
        background: #f8fafc;
        padding: 0.7rem 1rem;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
    }

    .photo-preview-card {
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        padding: 1rem;
        text-align: center;
    }

    .photo-preview-card img {
        max-width: 100%;
        max-height: 320px;
        border-radius: 10px;
        object-fit: contain;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .photo-preview-card img:hover {
        transform: scale(1.02);
    }

    .badge {
        display: inline-block;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .badge-baik { background: #ecfdf5; color: #047857; }
    .badge-rusak_ringan { background: #fffbeb; color: #b45309; }
    .badge-rusak_berat { background: #fef2f2; color: #b91c1c; }

    .alasan-box {
        background: #fff1f2;
        border: 1px solid #fecdd3;
        border-left: 4px solid #e11d48;
        padding: 1rem 1.2rem;
        border-radius: 10px;
        margin-top: 1rem;
    }

    .alasan-box h5 {
        color: #be123c;
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 0.3rem;
    }

    .alasan-box p {
        color: #881337;
        font-size: 0.9rem;
        margin: 0;
    }

    .btn-reapply {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        color: white;
        padding: 0.8rem 1.5rem;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .btn-reapply:hover {
        opacity: 0.95;
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
    <h2>📄 Detail Pengembalian Fasilitas</h2>
    <a href="{{ route('user.pengembalian.index') }}" class="btn-back">← Kembali</a>
</div>

<div class="detail-card">
    <!-- STATUS BANNER -->
    @if($pengembalian->status === 'pending')
        <div class="status-banner pending">
            <div class="banner-icon">⏳</div>
            <div class="banner-text">
                <h4>Menunggu Verifikasi Admin Sarana</h4>
                <p>Pengajuan pengembalian barang Anda telah kami terima dan sedang dalam proses pemeriksaan oleh petugas Admin Sarana.</p>
            </div>
        </div>
    @elseif($pengembalian->status === 'disetujui')
        <div class="status-banner disetujui">
            <div class="banner-icon">✅</div>
            <div class="banner-text">
                <h4>Pengembalian Telah Diverifikasi & Disetujui</h4>
                <p>Barang telah berhasil dikembalikan dan diverifikasi oleh <strong>{{ $pengembalian->verifikasi_oleh ?? 'Admin Sarana' }}</strong> pada {{ \Carbon\Carbon::parse($pengembalian->tanggal_verifikasi)->locale('id')->isoFormat('DD MMMM YYYY HH:mm') }} WIB.</p>
            </div>
        </div>
    @elseif($pengembalian->status === 'ditolak')
        <div class="status-banner ditolak">
            <div class="banner-icon">❌</div>
            <div class="banner-text">
                <h4>Pengembalian Ditolak</h4>
                <p>Pengajuan pengembalian belum dapat disetujui. Silakan periksa alasan penolakan di bawah ini.</p>
            </div>
        </div>
    @endif

    <div class="detail-grid">
        <!-- KOLOM KIRI: INFO BARANG & PENGEMBALIAN -->
        <div>
            <div class="section-title">📦 Informasi Barang & Pengembalian</div>
            <div class="info-list">
                <div class="info-item">
                    <label>Nama Barang</label>
                    <div class="val">{{ $pengembalian->nama_barang }}</div>
                </div>

                <div class="info-item">
                    <label>Kode Barang</label>
                    <div class="val">{{ $pengembalian->kode_barang }}</div>
                </div>

                <div class="info-item">
                    <label>Kategori</label>
                    <div class="val">{{ $pengembalian->barang->kategori->nama_kategori ?? '-' }}</div>
                </div>

                <div class="info-item">
                    <label>Tanggal Pengembalian</label>
                    <div class="val">{{ \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian)->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</div>
                </div>

                <div class="info-item">
                    <label>Kondisi Barang Dilaporkan</label>
                    <div>
                        <span class="badge badge-{{ $pengembalian->kondisi_pengembalian }}">
                            @if($pengembalian->kondisi_pengembalian === 'baik')
                                ✓ Baik / Normal
                            @elseif($pengembalian->kondisi_pengembalian === 'rusak_ringan')
                                ⚠️ Rusak Ringan
                            @else
                                ❌ Rusak Berat
                            @endif
                        </span>
                    </div>
                </div>

                <div class="info-item">
                    <label>Catatan Kondisi dari Peminjam</label>
                    <div class="val" style="font-weight: 400; line-height: 1.5;">
                        {{ $pengembalian->catatan_kondisi ?: 'Tidak ada catatan tambahan.' }}
                    </div>
                </div>

                @if($pengembalian->status === 'ditolak' && $pengembalian->alasan_penolakan)
                    <div class="alasan-box">
                        <h5>⚠️ Alasan Penolakan dari Admin:</h5>
                        <p>{{ $pengembalian->alasan_penolakan }}</p>
                        @if($pengembalian->peminjaman)
                            <a href="{{ route('user.pengembalian.create', $pengembalian->peminjaman_id) }}" class="btn-reapply">
                                🔄 Ajukan Ulang Pengembalian
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- KOLOM KANAN: BUKTI FOTO & VERIFIKASI -->
        <div>
            <div class="section-title">📷 Bukti Foto Kondisi Barang</div>
            <div class="photo-preview-card">
                @if($pengembalian->bukti_foto)
                    <a href="{{ asset('storage/' . $pengembalian->bukti_foto) }}" target="_blank" title="Klik untuk memperbesar gambar">
                        <img src="{{ asset('storage/' . $pengembalian->bukti_foto) }}" alt="Bukti Pengembalian">
                    </a>
                    <div style="font-size: 0.8rem; color: #64748b; margin-top: 0.6rem;">
                        💡 Klik pada foto untuk melihat ukuran penuh
                    </div>
                @else
                    <div style="padding: 3rem; color: #94a3b8;">
                        Tidak ada bukti foto terlampir
                    </div>
                @endif
            </div>

            <div style="margin-top: 1.5rem;">
                <div class="section-title">🔍 Info Verifikasi</div>
                <div class="info-list">
                    <div class="info-item">
                        <label>Status Verifikasi</label>
                        <div class="val">
                            {{ $pengembalian->status === 'pending' ? '⏳ Menunggu Pemeriksaan' : ($pengembalian->status === 'disetujui' ? '✅ Disetujui' : '❌ Ditolak') }}
                        </div>
                    </div>

                    @if($pengembalian->verifikasi_oleh)
                        <div class="info-item">
                            <label>Diverifikasi Oleh</label>
                            <div class="val">{{ $pengembalian->verifikasi_oleh }}</div>
                        </div>
                        <div class="info-item">
                            <label>Waktu Verifikasi</label>
                            <div class="val">{{ \Carbon\Carbon::parse($pengembalian->tanggal_verifikasi)->locale('id')->isoFormat('DD MMMM YYYY HH:mm') }} WIB</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

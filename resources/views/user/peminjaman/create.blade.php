@extends('layouts.dashboard-user')

@section('title', 'Pengajuan Peminjaman')

@section('styles')
<style>
    .form-container {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .form-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .form-header h2 {
        font-size: 1.8rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #64748b;
        color: white;
        padding: 0.7rem 1.3rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        margin-bottom: 1.5rem;
    }

    .btn-back:hover {
        background: #475569;
        transform: translateY(-2px);
    }

    .form-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2.5rem;
        align-items: start;
    }

    .barang-info-card {
        background: #f8fafc;
        border-radius: 15px;
        padding: 1.5rem;
        border: 2px solid #e2e8f0;
    }

    .barang-image {
        width: 100%;
        height: 220px;
        background: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.2rem;
        overflow: hidden;
    }

    .barang-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .barang-detail {
        font-size: 0.95rem;
        color: #475569;
    }

    .barang-detail p {
        margin: 0.5rem 0;
    }

    .barang-detail strong {
        color: #1e293b;
    }

    .status-badge {
        display: inline-block;
        padding: 0.4rem 1.2rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        margin-top: 1rem;
    }

    .status-tersedia { background: #16a34a; color: white; }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.6rem;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 0.9rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.95rem;
        color: #1e293b;
        outline: none;
        transition: all 0.2s;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        border-color: #1e40af;
        box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .btn-submit {
        width: 100%;
        padding: 1rem;
        background: #1e40af;
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 1.05rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 0.5rem;
    }

    .btn-submit:hover {
        background: #1e3a8a;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3);
    }

    .error {
        color: #ef4444;
        font-size: 0.8rem;
        margin-top: 0.3rem;
    }

    /* ===== CONFIRMATION MODAL ===== */
    .confirm-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 99999;
        backdrop-filter: blur(4px);
    }

    .confirm-modal-overlay.active {
        display: flex;
    }

    .confirm-modal {
        background: #e5e7eb;
        border-radius: 20px;
        width: 500px;
        max-width: 90%;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.4);
        animation: modalPop 0.3s ease;
        border: 3px solid #1f2937;
    }

    @keyframes modalPop {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }

    .confirm-modal-header {
        background: #1e40af;
        padding: 1rem;
        text-align: center;
    }

    .confirm-modal-header h3 {
        color: white;
        font-size: 1.3rem;
        font-weight: 800;
        letter-spacing: 1px;
        margin: 0;
    }

    .confirm-modal-body {
        padding: 2.5rem 2rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .warning-icon { flex-shrink: 0; }
    .warning-icon svg { width: 85px; height: 85px; }

    .confirm-modal-body p {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1f2937;
        line-height: 1.4;
        margin: 0;
    }

    .confirm-modal-footer {
        padding: 1.5rem 2rem 2rem;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    .btn-modal-ya {
        background: #2563eb;
        color: white;
        border: none;
        padding: 0.8rem 2.5rem;
        border-radius: 30px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        letter-spacing: 1px;
    }

    .btn-modal-ya:hover {
        background: #1d4ed8;
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
    }

    .btn-modal-tidak {
        background: #dc2626;
        color: white;
        border: none;
        padding: 0.8rem 2.5rem;
        border-radius: 30px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        letter-spacing: 1px;
    }

    .btn-modal-tidak:hover {
        background: #b91c1c;
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4);
    }

    @media (max-width: 768px) {
        .form-layout { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div class="page-title-section">
        <h2>📋 Pengajuan Peminjaman</h2>
    </div>
    <a href="{{ route('user.dashboard') }}" class="btn-back">← Kembali ke Dashboard</a>
</div>

<div class="form-container">

    <form id="formPengajuan" action="{{ route('user.peminjaman.store', $barang->kode_barang) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-layout">
            <!-- Info Barang -->
            <div class="barang-info-card">
                <div class="barang-image">
                    @if($barang->gambar)
                        <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}">
                    @else
                        <div style="font-size: 4rem;">📦</div>
                    @endif
                </div>
                <div class="barang-detail">
                    <p><strong>Nama barang :</strong> {{ $barang->nama_barang }}</p>
                    <p><strong>Merk :</strong> {{ $barang->merk_model ?? '-' }}</p>
                    <p><strong>Kategori :</strong> {{ $barang->kategori->nama_kategori ?? '-' }}</p>
                    <p><strong>Kode :</strong> {{ $barang->kode_barang }}</p>
                </div>
                <div style="text-align: center;">
                    <span class="status-badge status-tersedia">Tersedia</span>
                </div>
            </div>

            <!-- Form -->
            <div>
                <div class="form-group">
                    <label>Tanggal Peminjaman</label>
                    <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required min="{{ date('Y-m-d') }}">
                    @error('tanggal_pinjam')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Tanggal Pengembalian</label>
                    <input type="date" name="tanggal_kembali" id="tanggal_kembali" value="{{ old('tanggal_kembali', date('Y-m-d', strtotime('+1 day'))) }}" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    @error('tanggal_kembali')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>No Telepon Aktif</label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon', Auth::user()->no_hp) }}" required>
                    @error('no_telepon')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Alasan Keperluan Peminjaman</label>
                    <textarea name="alasan_keperluan" rows="4" placeholder="Jelaskan tujuan dan keperluan Anda meminjam barang ini..." required>{{ old('alasan_keperluan') }}</textarea>
                    @error('alasan_keperluan')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="button" class="btn-submit" onclick="showConfirmModal()">Konfirmasi Pengajuan</button>
            </div>
        </div>
    </form>
</div>

<!-- POPUP MODAL KONFIRMASI PENGAJUAN -->
<div class="confirm-modal-overlay" id="confirmModal">
    <div class="confirm-modal">
        <div class="confirm-modal-header">
            <h3>PERINGATAN!!!</h3>
        </div>
        <div class="confirm-modal-body">
            <div class="warning-icon">
                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <polygon points="50,8 95,88 5,88" fill="#facc15" stroke="#dc2626" stroke-width="5" stroke-linejoin="round"/>
                    <text x="50" y="72" font-size="50" font-weight="900" text-anchor="middle" fill="#1f2937">!</text>
                </svg>
            </div>
            <p>Apakah Anda yakin ingin mengajukan peminjaman barang ini?</p>
        </div>
        <div class="confirm-modal-footer">
            <button type="button" class="btn-modal-ya" onclick="submitPengajuan()">YA</button>
            <button type="button" class="btn-modal-tidak" onclick="closeConfirmModal()">TIDAK</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Dynamic date handling
    const tglPinjamInput = document.getElementById('tanggal_pinjam');
    const tglKembaliInput = document.getElementById('tanggal_kembali');

    if (tglPinjamInput && tglKembaliInput) {
        tglPinjamInput.addEventListener('change', function() {
            if (this.value) {
                const parts = this.value.split('-');
                const pinjamDate = new Date(parts[0], parts[1] - 1, parts[2]);
                pinjamDate.setDate(pinjamDate.getDate() + 1);
                
                const yyyy = pinjamDate.getFullYear();
                const mm = String(pinjamDate.getMonth() + 1).padStart(2, '0');
                const dd = String(pinjamDate.getDate()).padStart(2, '0');
                const nextDayStr = `${yyyy}-${mm}-${dd}`;
                
                tglKembaliInput.min = nextDayStr;
                tglKembaliInput.value = nextDayStr;
            }
        });
    }

    function showConfirmModal() {
        const form = document.getElementById('formPengajuan');
        if (!form.reportValidity()) {
            return;
        }
        document.getElementById('confirmModal').classList.add('active');
    }

    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.remove('active');
    }

    function submitPengajuan() {
        document.getElementById('formPengajuan').submit();
    }

    document.getElementById('confirmModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeConfirmModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeConfirmModal();
        }
    });
</script>
@endsection
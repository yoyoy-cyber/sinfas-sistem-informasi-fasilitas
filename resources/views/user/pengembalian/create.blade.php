@extends('layouts.dashboard-user')

@section('title', 'Form Pengembalian Barang')

@section('styles')
<style>
    .form-container {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: white;
        color: #1e40af;
        padding: 0.7rem 1.3rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .btn-back:hover {
        background: #f8fafc;
        transform: translateY(-2px);
    }

    .form-header {
        margin-bottom: 2rem;
        padding-bottom: 1.2rem;
        border-bottom: 2px solid #f1f5f9;
    }

    .form-header h2 {
        font-size: 1.7rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 0.4rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .form-header p {
        color: #64748b;
        font-size: 0.95rem;
    }

    .form-layout {
        display: grid;
        grid-template-columns: 360px 1fr;
        gap: 2.5rem;
        align-items: start;
    }

    /* Info card */
    .barang-info-card {
        background: #f8fafc;
        border-radius: 16px;
        padding: 1.8rem;
        border: 2px solid #e2e8f0;
    }

    .barang-image {
        width: 100%;
        height: 200px;
        background: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.2rem;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .barang-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .barang-detail h3 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.4rem;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 0.6rem 0;
        border-bottom: 1px solid #edf2f7;
        font-size: 0.9rem;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: #64748b;
    }

    .detail-value {
        font-weight: 600;
        color: #1e293b;
    }

    /* Form Inputs */
    .form-group {
        margin-bottom: 1.8rem;
    }

    .form-label {
        display: block;
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.6rem;
    }

    .form-label .required {
        color: #dc2626;
    }

    .form-input {
        width: 100%;
        padding: 0.85rem 1.1rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.95rem;
        color: #1e293b;
        outline: none;
        transition: all 0.2s;
        font-family: inherit;
    }

    .form-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .form-textarea {
        min-height: 100px;
        resize: vertical;
    }

    /* Condition Selector Cards */
    .condition-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .condition-option {
        position: relative;
    }

    .condition-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .condition-card {
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.2rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: white;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .condition-card .icon {
        font-size: 1.8rem;
        margin-bottom: 0.4rem;
    }

    .condition-card .title {
        font-weight: 700;
        font-size: 0.95rem;
        color: #1e293b;
        margin-bottom: 0.2rem;
    }

    .condition-card .desc {
        font-size: 0.75rem;
        color: #64748b;
        line-height: 1.3;
    }

    .condition-option input[type="radio"]:checked + .condition-card {
        border-color: #2563eb;
        background: #eff6ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.15);
    }

    .condition-option input[type="radio"]:checked + .condition-card.card-baik {
        border-color: #10b981;
        background: #ecfdf5;
    }

    .condition-option input[type="radio"]:checked + .condition-card.card-rusak_ringan {
        border-color: #f59e0b;
        background: #fffbeb;
    }

    .condition-option input[type="radio"]:checked + .condition-card.card-rusak_berat {
        border-color: #ef4444;
        background: #fef2f2;
    }

    /* Photo Upload Box */
    .upload-box {
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        padding: 2rem 1.5rem;
        text-align: center;
        cursor: pointer;
        background: #f8fafc;
        transition: all 0.2s;
        position: relative;
    }

    .upload-box:hover {
        border-color: #2563eb;
        background: #f0f7ff;
    }

    .upload-icon {
        font-size: 2.5rem;
        color: #64748b;
        margin-bottom: 0.6rem;
    }

    .upload-text {
        font-size: 0.95rem;
        color: #334155;
        font-weight: 600;
        margin-bottom: 0.3rem;
    }

    .upload-subtext {
        font-size: 0.8rem;
        color: #94a3b8;
    }

    .preview-container {
        display: none;
        margin-top: 1rem;
        position: relative;
    }

    .preview-image {
        max-width: 100%;
        max-height: 260px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        object-fit: contain;
        background: white;
    }

    .btn-remove-preview {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(220, 38, 38, 0.9);
        color: white;
        border: none;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-remove-preview:hover {
        background: #dc2626;
        transform: scale(1.05);
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 2px solid #f1f5f9;
    }

    .btn-submit {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        color: white;
        border: none;
        padding: 0.9rem 2.2rem;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        transition: all 0.2s;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
    }

    .btn-cancel {
        background: #f1f5f9;
        color: #64748b;
        border: 2px solid #e2e8f0;
        padding: 0.9rem 1.8rem;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.2s;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
        color: #334155;
    }

    .error-feedback {
        color: #dc2626;
        font-size: 0.85rem;
        margin-top: 0.3rem;
    }

    @media (max-width: 900px) {
        .form-layout {
            grid-template-columns: 1fr;
        }
        .condition-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div class="page-title-section">
        <h2>📋 Formulir Pengembalian Barang</h2>
    </div>
    <a href="{{ route('user.pengembalian.index') }}" class="btn-back">
        ← Kembali ke Daftar Pengembalian
    </a>
</div>

<div class="form-container">
    <div class="form-header">
        <p style="margin: 0; color: #64748b;">Silakan isi informasi pengembalian barang dan unggah foto bukti kondisi barang setelah selesai digunakan.</p>
    </div>

    <div class="form-layout">
        <!-- DETAIL BARANG YANG DIPINJAM -->
        <div class="barang-info-card">
            <div class="barang-image">
                @if($peminjaman->barang && $peminjaman->barang->gambar)
                    <img src="{{ asset('storage/' . $peminjaman->barang->gambar) }}" alt="{{ $peminjaman->nama_barang }}">
                @else
                    <span style="font-size: 4rem;">📦</span>
                @endif
            </div>

            <div class="barang-detail">
                <h3>{{ $peminjaman->nama_barang }}</h3>
                <div class="detail-row">
                    <span class="detail-label">Kode Barang</span>
                    <span class="detail-value">{{ $peminjaman->kode_barang }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Kategori</span>
                    <span class="detail-value">{{ $peminjaman->barang->kategori->nama_kategori ?? '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Merk / Model</span>
                    <span class="detail-value">{{ $peminjaman->barang->merk_model ?? '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tanggal Pinjam</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->locale('id')->isoFormat('DD MMMM YYYY') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Batas Kembali</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->locale('id')->isoFormat('DD MMMM YYYY') }}</span>
                </div>
            </div>
        </div>

        <!-- FORM ISIAN PENGEMBALIAN -->
        <form action="{{ route('user.pengembalian.store', $peminjaman->id) }}" method="POST" enctype="multipart/form-data" id="formPengembalian">
            @csrf

            <!-- 1. TANGGAL PENGEMBALIAN -->
            <div class="form-group">
                <label class="form-label" for="tanggal_pengembalian">
                    Tanggal Pengembalian <span class="required">*</span>
                </label>
                <input 
                    type="date" 
                    name="tanggal_pengembalian" 
                    id="tanggal_pengembalian" 
                    class="form-input @error('tanggal_pengembalian') is-invalid @enderror"
                    value="{{ old('tanggal_pengembalian', date('Y-m-d')) }}" 
                    required
                >
                @error('tanggal_pengembalian')
                    <div class="error-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- 2. KONDISI BARANG SESUDAH DIPINJAM -->
            <div class="form-group">
                <label class="form-label">
                    Kondisi Barang Sesudah Dipinjam <span class="required">*</span>
                </label>
                <div class="condition-grid">
                    <label class="condition-option">
                        <input type="radio" name="kondisi_pengembalian" value="baik" {{ old('kondisi_pengembalian', 'baik') === 'baik' ? 'checked' : '' }} required>
                        <div class="condition-card card-baik">
                            <div class="icon">✨</div>
                            <div class="title">Baik / Normal</div>
                            <div class="desc">Barang utuh, bersih, dan berfungsi dengan baik</div>
                        </div>
                    </label>

                    <label class="condition-option">
                        <input type="radio" name="kondisi_pengembalian" value="rusak_ringan" {{ old('kondisi_pengembalian') === 'rusak_ringan' ? 'checked' : '' }}>
                        <div class="condition-card card-rusak_ringan">
                            <div class="icon">⚠️</div>
                            <div class="title">Rusak Ringan</div>
                            <div class="desc">Terdapat sedikit kendala fisik/fungsi minor</div>
                        </div>
                    </label>

                    <label class="condition-option">
                        <input type="radio" name="kondisi_pengembalian" value="rusak_berat" {{ old('kondisi_pengembalian') === 'rusak_berat' ? 'checked' : '' }}>
                        <div class="condition-card card-rusak_berat">
                            <div class="icon">❌</div>
                            <div class="title">Rusak Berat</div>
                            <div class="desc">Patah, pecah, mati total, atau komponen hilang</div>
                        </div>
                    </label>
                </div>
                @error('kondisi_pengembalian')
                    <div class="error-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- 3. BUKTI FOTO BARANG SESUDAH DIPINJAM -->
            <div class="form-group">
                <label class="form-label" for="bukti_foto">
                    Bukti Foto Barang Sesudah Dipinjam <span class="required">*</span>
                </label>
                <div class="upload-box" onclick="document.getElementById('bukti_foto').click()">
                    <div class="upload-icon">📷</div>
                    <div class="upload-text">Klik atau seret foto bukti pengembalian di sini</div>
                    <div class="upload-subtext">Format: JPG, JPEG, PNG, WEBP (Maksimal 5 MB)</div>
                    <input 
                        type="file" 
                        name="bukti_foto" 
                        id="bukti_foto" 
                        accept="image/jpeg,image/png,image/jpg,image/webp" 
                        style="display: none;" 
                        onchange="previewImage(this)"
                        required
                    >
                </div>
                
                <div class="preview-container" id="previewContainer">
                    <img id="previewImg" class="preview-image" src="" alt="Preview Bukti Foto">
                    <button type="button" class="btn-remove-preview" onclick="removeImage()">✕ Hapus Foto</button>
                </div>
                @error('bukti_foto')
                    <div class="error-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- 4. CATATAN KONDISI / KETERANGAN TAMBAHAN -->
            <div class="form-group">
                <label class="form-label" for="catatan_kondisi">
                    Catatan Kondisi / Keterangan Tambahan
                </label>
                <textarea 
                    name="catatan_kondisi" 
                    id="catatan_kondisi" 
                    class="form-input form-textarea @error('catatan_kondisi') is-invalid @enderror" 
                    placeholder="Tuliskan catatan kondisi barang atau kelengkapan fasilitas saat dikembalikan..."
                >{{ old('catatan_kondisi') }}</textarea>
                @error('catatan_kondisi')
                    <div class="error-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- TOMBOL AKSI -->
            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <svg viewBox="0 0 24 24" style="width: 20px; height: 20px; fill: currentColor;">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                    Kirim Pengembalian
                </button>
                <a href="{{ route('user.pengembalian.index') }}" class="btn-cancel">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function previewImage(input) {
        const file = input.files[0];
        if (file) {
            // Validasi ukuran (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 5 MB.');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('previewContainer').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    }

    function removeImage() {
        document.getElementById('bukti_foto').value = '';
        document.getElementById('previewImg').src = '';
        document.getElementById('previewContainer').style.display = 'none';
    }
</script>
@endsection

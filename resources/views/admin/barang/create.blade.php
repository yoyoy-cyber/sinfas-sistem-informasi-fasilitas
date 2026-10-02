@extends('layouts.admin-sarana')

@section('title', 'Tambah Barang Baru')

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
    }

    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    .form-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        font-size: 0.9rem;
        color: #1e293b;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 0.9rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.95rem;
        outline: none;
        transition: all 0.2s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #1e40af;
        box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .btn-submit {
        background: #1e40af;
        color: white;
        border: none;
        padding: 1rem 2.5rem;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
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

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <h2>➕ Tambah Barang Baru</h2>
    <a href="{{ route('admin.barang.index') }}" class="btn-back">← Kembali</a>
</div>

<div class="form-card">
    <form action="{{ route('admin.barang.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Kode Barang *</label>
            <input type="text" name="kode_barang" value="{{ old('kode_barang') }}" required placeholder="Contoh: BRG001">
            @error('kode_barang')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Nama Barang *</label>
            <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" required placeholder="Contoh: Proyektor Epson">
            @error('nama_barang')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Kategori *</label>
            <select name="id_kategori" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id_kategori }}" {{ old('id_kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                        {{ $kategori->nama_kategori }}
                    </option>
                @endforeach
            </select>
            @error('id_kategori')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Merk / Model</label>
            <input type="text" name="merk_model" value="{{ old('merk_model') }}" placeholder="Contoh: EB-X400">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Jumlah Baik *</label>
                <input type="number" name="jumlah_baik" value="{{ old('jumlah_baik', 0) }}" min="0" required>
            </div>
            <div class="form-group">
                <label>Jumlah Dipinjam *</label>
                <input type="number" name="jumlah_kurang_baik" value="{{ old('jumlah_kurang_baik', 0) }}" min="0" required>
                <small style="color: #888; font-size: 0.8rem;">Barang yang sedang dipinjam</small>
            </div>
            <div class="form-group">
                <label>Jumlah Rusak Berat *</label>
                <input type="number" name="jumlah_rusak_berat" value="{{ old('jumlah_rusak_berat', 0) }}" min="0" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>No. Seri Pabrik</label>
                <input type="text" name="no_seri_pabrik" value="{{ old('no_seri_pabrik') }}">
            </div>
            <div class="form-group">
                <label>Ukuran / Dimensi</label>
                <input type="text" name="ukuran_dimensi" value="{{ old('ukuran_dimensi') }}">
            </div>
            <div class="form-group">
                <label>Bahan</label>
                <input type="text" name="bahan" value="{{ old('bahan') }}">
            </div>
        </div>

        <div class="form-group">
            <label>Tahun Pembelian</label>
            <input type="number" name="tahun_pembelian" value="{{ old('tahun_pembelian') }}" min="1900" max="{{ date('Y') }}">
        </div>

        <div class="form-group">
            <label>Gambar Barang</label>
            <input type="file" name="gambar" accept="image/*">
            <small style="color: #888; font-size: 0.8rem;">Format: JPG, PNG, GIF, WEBP (Maks. 2MB)</small>
            @error('gambar')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
        </div>

        <button type="submit" class="btn-submit">💾 Simpan Barang</button>
    </form>
</div>

@endsection
@extends('layouts.admin-sarana')

@section('title', 'Fitur Database: SP, Trigger & View')

@section('styles')
<style>
    .features-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .feature-card {
        background: white;
        border-radius: 20px;
        padding: 1.8rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
    }

    .feature-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.9rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        width: fit-content;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-view { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
    .badge-sp { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .badge-func { background: #f3e8ff; color: #7e22ce; border: 1px solid #e9d5ff; }
    .badge-trigger { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }

    .feature-card h3 {
        font-size: 1.3rem;
        color: #1e293b;
        margin-bottom: 0.6rem;
        font-weight: 800;
    }

    .feature-card p {
        color: #64748b;
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1rem;
    }

    .sql-code-box {
        background: #0f172a;
        color: #38bdf8;
        border-radius: 12px;
        padding: 1rem;
        font-family: 'Consolas', 'Monaco', monospace;
        font-size: 0.85rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        overflow-x: auto;
    }

    .data-table-container {
        background: white;
        border-radius: 20px;
        padding: 1.8rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .data-table-container h3 {
        font-size: 1.25rem;
        color: #1e293b;
        font-weight: 800;
        margin-bottom: 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .custom-table th {
        background: #f8fafc;
        padding: 1rem;
        font-size: 0.85rem;
        font-weight: 700;
        color: #475569;
        text-align: left;
        border-bottom: 2px solid #e2e8f0;
    }

    .custom-table td {
        padding: 1rem;
        font-size: 0.88rem;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
    }

    .custom-table tr:hover td {
        background: #f8fafc;
    }

    .status-pill {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-block;
    }

    .pill-green { background: #dcfce7; color: #166534; }
    .pill-yellow { background: #fef3c7; color: #92400e; }
    .pill-red { background: #fee2e2; color: #991b1b; }
    .pill-gray { background: #f1f5f9; color: #475569; }
    .pill-blue { background: #eff6ff; color: #1e40af; }

    @media (max-width: 992px) {
        .features-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div class="page-title-section">
        <h2 style="color: white; font-size: 1.8rem; font-weight: 800; margin: 0;">
            ⚙️ Implementasi Fitur Database (SP, Trigger &amp; View)
        </h2>
        <p style="color: #94a3b8; margin-top: 0.4rem; font-size: 0.95rem;">
            Halaman ini membuktikan dan menampilkan hasil eksekusi langsung dari <strong>Database View</strong>, <strong>Stored Procedure</strong>, <strong>Stored Function</strong>, dan <strong>Trigger</strong> di MySQL.
        </p>
    </div>
</div>

<!-- GRID SUMMARY 4 FITUR DATABASE -->
<div class="features-grid">
    <!-- 1. DATABASE VIEW -->
    <div class="feature-card">
        <span class="feature-badge badge-view">👁️ Database View</span>
        <h3>view_peminjaman_lengkap</h3>
        <p>
            View SQL yang menggabungkan tabel <code>peminjaman_requests</code>, <code>barang</code>, <code>kategori</code>, dan <code>akun</code> menjadi satu tampilan lengkap disertai kolom kalkulasi status deskriptif.
        </p>
        <div class="sql-code-box">
CREATE OR REPLACE VIEW view_peminjaman_lengkap AS
SELECT pr.id, pr.kode_barang, b.nama_barang, 
       k.nama_kategori, pr.nama_peminjam, pr.status, ...
FROM peminjaman_requests pr
LEFT JOIN barang b ON pr.kode_barang = b.kode_barang
...
        </div>
        <div style="margin-top: auto; padding: 0.8rem; background: #f8fafc; border-radius: 12px; font-size: 0.85rem;">
            <strong>Status:</strong> ✅ Aktif — Data di bawah dibaca langsung dari Model <code>ViewPeminjamanLengkap</code>
        </div>
    </div>

    <!-- 2. STORED PROCEDURE -->
    <div class="feature-card">
        <span class="feature-badge badge-sp">⚡ Stored Procedure</span>
        <h3>sp_rekap_status_peminjaman</h3>
        <p>
            Prosedur tersimpan di MySQL yang menghitung rekapitulasi status secara server-side dan mengembalikan nilai melalui parameter <code>OUT</code>.
        </p>
        <div class="sql-code-box">
CALL sp_rekap_status_peminjaman(@tot, @pend, @setuju, @batal, @tolak);
-- Hasil Eksekusi Live:
Total Pengajuan : {{ $procSummary->total ?? 0 }}
Disetujui       : {{ $procSummary->disetujui ?? 0 }}
Dibatalkan      : {{ $procSummary->dibatalkan ?? 0 }}
Ditolak         : {{ $procSummary->ditolak ?? 0 }}
        </div>
        <div style="margin-top: auto; padding: 0.8rem; background: #f8fafc; border-radius: 12px; font-size: 0.85rem;">
            <strong>Status:</strong> ✅ Aktif — Menggunakan sintaks <code>CALL sp_rekap_status_peminjaman(...)</code>
        </div>
    </div>

    <!-- 3. STORED FUNCTION -->
    <div class="feature-card">
        <span class="feature-badge badge-func">📐 Stored Function</span>
        <h3>func_total_dipinjam_barang</h3>
        <p>
            Fungsi SQL kustom deterministik untuk menghitung frekuensi peminjaman sah dari suatu kode barang langsung di level database engine.
        </p>
        <div class="sql-code-box">
SELECT func_total_dipinjam_barang('{{ $barangSample->kode_barang ?? 'BRG008' }}') AS total,
       func_status_ketersediaan_barang('{{ $barangSample->kode_barang ?? 'BRG008' }}') AS status;
-- Hasil: Total Dipinjam = {{ $funcResult->total_dipinjam ?? 0 }}x | Status = {{ $funcResult->status_barang ?? 'Tersedia' }}
        </div>
        <div style="margin-top: auto; padding: 0.8rem; background: #f8fafc; border-radius: 12px; font-size: 0.85rem;">
            <strong>Status:</strong> ✅ Aktif — Menghitung riwayat peminjaman sah barang secara instan
        </div>
    </div>

    <!-- 4. DATABASE TRIGGER -->
    <div class="feature-card">
        <span class="feature-badge badge-trigger">🎯 Database Trigger</span>
        <h3>trg_after_update_peminjaman</h3>
        <p>
            Trigger MySQL yang secara otomatis mencatat riwayat perubahan status (misal saat disetujui, ditolak, atau dibatalkan peminjam) ke dalam tabel audit <code>log_aktivitas_peminjaman</code>.
        </p>
        <div class="sql-code-box">
CREATE TRIGGER trg_after_update_peminjaman
AFTER UPDATE ON peminjaman_requests FOR EACH ROW
BEGIN
  IF OLD.status &lt;&gt; NEW.status THEN
    INSERT INTO log_aktivitas_peminjaman(...) VALUES(...);
  END IF;
END;
        </div>
        <div style="margin-top: auto; padding: 0.8rem; background: #f8fafc; border-radius: 12px; font-size: 0.85rem;">
            <strong>Status:</strong> ✅ Aktif — Berjalan otomatis di MySQL tanpa bergantung logika controller
        </div>
    </div>
</div>

<!-- TABEL 1: DATA LANGSUNG DARI DATABASE VIEW -->
<div class="data-table-container">
    <h3>👁️ Data Hasil Query dari DATABASE VIEW (<code>view_peminjaman_lengkap</code>)</h3>
    <div style="overflow-x: auto;">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Barang</th>
                    <th>Kategori</th>
                    <th>Peminjam</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status DB</th>
                    <th>Status Deskripsi (Computed View)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($viewData as $v)
                    <tr>
                        <td><strong>#{{ $v->id_peminjaman }}</strong></td>
                        <td>{{ $v->nama_barang }} ({{ $v->kode_barang }})</td>
                        <td>{{ $v->nama_kategori ?? '-' }}</td>
                        <td>{{ $v->nama_peminjam }} ({{ $v->username }})</td>
                        <td>{{ $v->tanggal_pinjam }}</td>
                        <td>{{ $v->tanggal_kembali }}</td>
                        <td>
                            <span class="status-pill {{ $v->status === 'disetujui' ? 'pill-green' : ($v->status === 'pending' ? 'pill-yellow' : ($v->status === 'ditolak' ? 'pill-red' : 'pill-gray')) }}">
                                {{ ucfirst($v->status) }}
                            </span>
                        </td>
                        <td>
                            <span class="status-pill pill-blue">
                                {{ $v->status_deskripsi }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: #94a3b8; padding: 2rem;">Belum ada data di view.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- TABEL 2: DATA LOG AKTIVITAS DARI DATABASE TRIGGER -->
<div class="data-table-container">
    <h3>🎯 Audit Log yang Dibuat Otomatis oleh TRIGGER (<code>log_aktivitas_peminjaman</code>)</h3>
    <div style="overflow-x: auto;">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>ID Log</th>
                    <th>ID Pengajuan</th>
                    <th>Kode Barang</th>
                    <th>Status Lama</th>
                    <th>Status Baru</th>
                    <th>Aksi Trigger</th>
                    <th>Catatan Otomatis</th>
                    <th>Waktu Kejadian</th>
                </tr>
            </thead>
            <tbody>
                @forelse($triggerLogs as $log)
                    <tr>
                        <td><strong>#{{ $log->id }}</strong></td>
                        <td>#{{ $log->id_peminjaman }}</td>
                        <td>{{ $log->kode_barang ?? '-' }}</td>
                        <td>
                            <span class="status-pill pill-gray">{{ $log->status_lama ?? 'BARU' }}</span>
                        </td>
                        <td>
                            <span class="status-pill {{ $log->status_baru === 'disetujui' ? 'pill-green' : ($log->status_baru === 'dibatalkan' ? 'pill-gray' : ($log->status_baru === 'ditolak' ? 'pill-red' : 'pill-blue')) }}">
                                {{ $log->status_baru }}
                            </span>
                        </td>
                        <td><strong>{{ $log->aksi }}</strong></td>
                        <td>{{ $log->catatan }}</td>
                        <td>{{ $log->created_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: #94a3b8; padding: 2rem;">
                            Belum ada log trigger. Coba lakukan update status peminjaman (approve/reject/batal) untuk melihat trigger mencatat otomatis.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

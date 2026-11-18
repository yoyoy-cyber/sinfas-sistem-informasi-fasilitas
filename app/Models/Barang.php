<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

/**
 * Class Barang
 *
 * Model untuk mengelola inventaris aset, fasilitas, dan peralatan sekolah.
 *
 * Karakteristik & Aturan Bisnis:
 * - Menggunakan primary key non-incrementing string `kode_barang` (contoh: 'BRG001').
 * - Mencatat kondisi barang terpisah: `jumlah_baik`, `jumlah_kurang_baik`, `jumlah_rusak_berat`.
 * - Frekuensi peminjaman sah hanya menghitung pengajuan berstatus 'disetujui', 'dipinjam', 'sedang_dipinjam', 'selesai', atau 'dikembalikan'.
 *
 * @package App\Models
 */
class Barang extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     * @var string
     */
    protected $table = 'barang';

    /**
     * Primary key model berupa kode unik barang.
     * @var string
     */
    protected $primaryKey = 'kode_barang';

    /**
     * Menandakan primary key tidak auto-increment.
     * @var bool
     */
    public $incrementing = false;

    /**
     * Tipe data primary key.
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Daftar kolom yang dapat diisi secara massal (mass assignment).
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_barang',
        'id_kategori',
        'nama_barang',
        'status',
        'gambar',
        'foto',
        'deskripsi',
        'merk_model',
        'no_seri_pabrik',
        'ukuran_dimensi',
        'bahan',
        'tahun_pembelian',
        'jumlah_baik',
        'jumlah_kurang_baik',
        'jumlah_rusak_berat',
        'keterangan',
    ];

    /**
     * Accessor untuk kompatibilitas atribut 'foto' dan 'gambar'.
     *
     * Alasan:
     * Menjaga kompatibilitas antara ERD versi awal yang menggunakan kolom 'foto'
     * dan versi aplikasi web yang memakai nama kolom 'gambar'.
     *
     * @param string|null $value Nilai asli kolom foto.
     * @return string|null Path file gambar/foto barang.
     */
    public function getFotoAttribute(?string $value): ?string
    {
        return $value ?: ($this->attributes['gambar'] ?? null);
    }

    /**
     * Accessor untuk memastikan pemanggilan $barang->gambar selalu mengembalikan path valid.
     *
     * @param string|null $value Nilai asli kolom gambar.
     * @return string|null Path file gambar.
     */
    public function getGambarAttribute(?string $value): ?string
    {
        return $value ?: ($this->attributes['foto'] ?? null);
    }

    /**
     * Relasi many-to-one ke Kategori Barang.
     * Setiap barang dimiliki oleh satu kategori (misal: Elektronik, Olahraga, dll).
     *
     * @return BelongsTo Relasi ke model Kategori.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    /**
     * Relasi one-to-many ke seluruh riwayat permohonan peminjaman barang ini.
     * Termasuk status pending, disetujui, ditolak, dan dibatalkan.
     *
     * @return HasMany Relasi ke model PeminjamanRequest.
     */
    public function peminjamanRequests(): HasMany
    {
        return $this->hasMany(PeminjamanRequest::class, 'kode_barang', 'kode_barang');
    }

    /**
     * Relasi one-to-many khusus peminjaman yang SAH / VALID.
     *
     * Aturan Bisnis:
     * Peminjaman berstatus 'pending', 'ditolak', atau 'dibatalkan' tidak dihitung
     * sebagai barang yang benar-benar pernah dipinjam.
     *
     * @return HasMany Relasi terfilter status sah.
     */
    public function peminjamanDisetujui(): HasMany
    {
        return $this->hasMany(PeminjamanRequest::class, 'kode_barang', 'kode_barang')
            ->whereIn('status', ['disetujui', 'dipinjam', 'sedang_dipinjam', 'selesai', 'dikembalikan']);
    }

    /**
     * Accessor untuk mendapatkan total kali barang telah dipinjam secara sah.
     *
     * Alur Kerja:
     * 1. Jika query controller sudah memuat 'peminjaman_requests_count' (via withCount),
     *    gunakan hasil eager-load tersebut untuk efisiensi query (mencegah N+1 problem).
     * 2. Jika belum, hitung langsung melalui relasi peminjamanDisetujui().
     *
     * @return int Jumlah frekuensi peminjaman sah.
     */
    public function getTotalDipinjamAttribute(): int
    {
        if (isset($this->attributes['peminjaman_requests_count'])) {
            return (int) $this->attributes['peminjaman_requests_count'];
        }
        return (int) $this->peminjamanDisetujui()->count();
    }

    /**
     * Menghitung total peminjaman sah menggunakan Stored Function MySQL: func_total_dipinjam_barang()
     *
     * Tujuan:
     * Memanfaatkan kemampuan komputasi database engine MySQL secara langsung.
     *
     * @return int Total frekuensi peminjaman dari hasil eksekusi Stored Function.
     */
    public function getTotalDipinjamViaFunction(): int
    {
        try {
            $res = DB::selectOne("SELECT func_total_dipinjam_barang(?) as total", [$this->kode_barang]);
            return (int) ($res->total ?? 0);
        } catch (\Throwable $e) {
            // Fallback ke accessor model jika fungsi database belum terpasang
            return $this->total_dipinjam;
        }
    }

    /**
     * Mengecek status ketersediaan barang menggunakan Stored Function MySQL: func_status_ketersediaan_barang()
     *
     * @return string Status barang ('Tersedia' atau 'Habis Dipinjam').
     */
    public function getStatusViaFunction(): string
    {
        try {
            $res = DB::selectOne("SELECT func_status_ketersediaan_barang(?) as status", [$this->kode_barang]);
            return (string) ($res->status ?? 'Tersedia');
        } catch (\Throwable $e) {
            // Fallback ke kolom status bawaan tabel
            return $this->status;
        }
    }
}

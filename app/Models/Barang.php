<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $primaryKey = 'kode_barang';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_barang',
        'id_kategori',
        'nama_barang',
        'status',
        'gambar',           // ← PASTIKAN ADA INI!
        'foto',             // ERD 2 compatibility
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
     * ERD2 compatibility: accessor for foto
     */
    public function getFotoAttribute($value)
    {
        return $value ?: $this->attributes['gambar'] ?? null;
    }

    /**
     * ERD2 compatibility: accessor for gambar
     */
    public function getGambarAttribute($value)
    {
        return $value ?: $this->attributes['foto'] ?? null;
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function peminjamanRequests()
    {
        return $this->hasMany(PeminjamanRequest::class, 'kode_barang', 'kode_barang');
    }

    // Hanya peminjaman yang valid / disetujui / aktif / selesai
    public function peminjamanDisetujui()
    {
        return $this->hasMany(PeminjamanRequest::class, 'kode_barang', 'kode_barang')
            ->whereIn('status', ['disetujui', 'dipinjam', 'sedang_dipinjam', 'selesai', 'dikembalikan']);
    }

    // Accessor total kali dipinjam yang valid (tidak menghitung pending, ditolak, atau dibatalkan)
    public function getTotalDipinjamAttribute(): int
    {
        if (isset($this->attributes['peminjaman_requests_count'])) {
            return (int) $this->attributes['peminjaman_requests_count'];
        }
        return (int) $this->peminjamanDisetujui()->count();
    }

    /**
     * Memanggil Stored Function MySQL: func_total_dipinjam_barang()
     * Memenuhi konsep: Stored Function
     */
    public function getTotalDipinjamViaFunction(): int
    {
        try {
            $res = \Illuminate\Support\Facades\DB::selectOne("SELECT func_total_dipinjam_barang(?) as total", [$this->kode_barang]);
            return (int) ($res->total ?? 0);
        } catch (\Throwable $e) {
            return $this->total_dipinjam;
        }
    }

    /**
     * Memanggil Stored Function MySQL: func_status_ketersediaan_barang()
     * Memenuhi konsep: Stored Function
     */
    public function getStatusViaFunction(): string
    {
        try {
            $res = \Illuminate\Support\Facades\DB::selectOne("SELECT func_status_ketersediaan_barang(?) as status", [$this->kode_barang]);
            return (string) ($res->status ?? 'Tersedia');
        } catch (\Throwable $e) {
            return $this->status;
        }
    }
}
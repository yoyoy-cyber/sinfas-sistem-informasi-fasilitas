<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Pengembalian — sesuai ERD SINFAS
 * ERD: PK kode_kembali (string), FK kode_pinjam → peminjaman
 * Implementasi: PK id (auto-increment), kode_kembali (string unique), FK peminjaman_id → peminjaman_requests
 */
class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalian';

    protected $fillable = [
        'peminjaman_id',
        'id_peminjaman', // ERD2
        'kode_pinjam',   // ERD2
        'user_id',       // backward compat
        'username',      // FK → akun.username (sesuai ERD)
        'kode_kembali',  // kode unik sesuai ERD
        'kode_barang',
        'nama_barang',
        'tanggal_pengembalian',
        'kondisi_pengembalian',
        'kondisi_barang',      // ERD2 alias
        'catatan_kondisi',
        'catatan',             // ERD2 alias
        'bukti_foto',
        'bukti_foto_video',    // ERD2 alias
        'keterangan',
        'status',
        'alasan_penolakan',
        'verifikasi_oleh',
        'tanggal_verifikasi',
    ];

    public function getKondisiBarangAttribute($value)
    {
        return $value ?: $this->attributes['kondisi_pengembalian'] ?? null;
    }

    public function getCatatanAttribute($value)
    {
        return $value ?: $this->attributes['catatan_kondisi'] ?? null;
    }

    public function getBuktiFotoVideoAttribute($value)
    {
        return $value ?: $this->attributes['bukti_foto'] ?? null;
    }

    protected $casts = [
        'tanggal_pengembalian' => 'date',
        'tanggal_verifikasi'   => 'datetime',
    ];

    /* ----------------------------------------------------------------
     * Relasi ke PeminjamanRequest (belongs-to)
     * ---------------------------------------------------------------- */
    public function peminjaman()
    {
        return $this->belongsTo(PeminjamanRequest::class, 'peminjaman_id');
    }

    /* ----------------------------------------------------------------
     * Relasi ke User/Akun (belongs-to) via username
     * ---------------------------------------------------------------- */
    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }

    /* ----------------------------------------------------------------
     * Relasi ke Akun (belongs-to) — alias ke user()
     * ---------------------------------------------------------------- */
    public function akun()
    {
        return $this->belongsTo(Akun::class, 'username', 'username');
    }

    /* ----------------------------------------------------------------
     * Relasi ke Barang (belongs-to)
     * ---------------------------------------------------------------- */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'kode_barang', 'kode_barang');
    }
}

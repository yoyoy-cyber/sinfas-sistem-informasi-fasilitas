<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model PeminjamanRequest — tabel peminjaman_requests
 * Sesuai ERD: FK nis → siswa, FK kode_barang → barang
 * username (FK → akun) ditambahkan sebagai akses mudah ke data akun
 */
class PeminjamanRequest extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_requests';

    protected $fillable = [
        'user_id',       // backward compat ke tabel users lama
        'username',      // FK → akun.username (sesuai ERD)
        'nis',           // FK → siswa.nis (sesuai ERD)
        'kode_barang',
        'nama_barang',
        'nama_peminjam',
        'role_peminjam',
        'tanggal_pinjam',
        'tanggal_kembali',
        'no_telepon',
        'alasan_keperluan',
        'alasan_penolakan',
        'catatan_admin',
        'status',
    ];

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
     * Relasi ke Siswa (belongs-to) via nis
     * ---------------------------------------------------------------- */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }

    /* ----------------------------------------------------------------
     * Relasi ke Barang (belongs-to)
     * ---------------------------------------------------------------- */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'kode_barang', 'kode_barang');
    }

    /* ----------------------------------------------------------------
     * Relasi ke Pengembalian (1-to-1)
     * ---------------------------------------------------------------- */
    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'peminjaman_id');
    }
}
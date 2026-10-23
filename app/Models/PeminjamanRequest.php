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
        'id_peminjaman', // ERD2 format PMJ-000001
        'user_id',       // backward compat ke tabel users lama
        'username',      // FK → akun.username (sesuai ERD)
        'id_akun',       // FK → akun.id_akun (ERD2)
        'nis',           // FK → siswa.nis (sesuai ERD)
        'kode_barang',
        'nama_barang',
        'nama_peminjam',
        'role_peminjam',
        'tanggal_pinjam',
        'tanggal_kembali',
        'no_telepon',
        'alasan_keperluan',
        'lokasi_penggunaan',
        'keterangan_penggunaan',
        'alasan_penolakan',
        'catatan_admin',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (\Illuminate\Support\Facades\Schema::hasColumn('peminjaman_requests', 'id_peminjaman')) {
                if (empty($model->id_peminjaman)) {
                    $maxId = static::max('id') ?? 0;
                    $model->id_peminjaman = 'PMJ-' . str_pad($maxId + 1, 6, '0', STR_PAD_LEFT);
                }
            }
        });
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

    /* ----------------------------------------------------------------
     * Relasi ke Penyetujuan (1-to-many / 1-to-1 sesuai ERD2)
     * ---------------------------------------------------------------- */
    public function penyetujuan()
    {
        return $this->hasOne(Penyetujuan::class, 'peminjaman_id');
    }
}
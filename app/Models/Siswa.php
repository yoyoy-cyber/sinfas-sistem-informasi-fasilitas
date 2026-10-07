<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Siswa — sesuai ERD SINFAS
 * PK: nis
 * FK: username → akun
 */
class Siswa extends Model
{
    use HasFactory;

    protected $table      = 'siswa';
    protected $primaryKey = 'nis';
    public    $incrementing = false;
    protected $keyType    = 'string';

    protected $fillable = [
        'nis',
        'username',
        'nama',
        'kelas',
        'no_hp',
    ];

    /* ----------------------------------------------------------------
     * Relasi ke Akun (belongs-to, 1-to-1)
     * ---------------------------------------------------------------- */
    public function akun()
    {
        return $this->belongsTo(Akun::class, 'username', 'username');
    }

    /* ----------------------------------------------------------------
     * Relasi ke Peminjaman (1-to-many)
     * Siswa bisa punya banyak peminjaman via kolom 'nis'
     * ---------------------------------------------------------------- */
    public function peminjaman()
    {
        return $this->hasMany(PeminjamanRequest::class, 'nis', 'nis');
    }
}

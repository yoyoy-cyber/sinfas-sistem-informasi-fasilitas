<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Pegawai — sesuai ERD SINFAS
 * PK: nip
 * FK: username → akun
 */
class Pegawai extends Model
{
    use HasFactory;

    protected $table      = 'pegawai';
    protected $primaryKey = 'nip';
    public    $incrementing = false;
    protected $keyType    = 'string';

    protected $fillable = [
        'nip',
        'username',
        'nama',
        'jabatan',
        'no_hp',
    ];

    /* ----------------------------------------------------------------
     * Relasi ke Akun (belongs-to, 1-to-1)
     * ---------------------------------------------------------------- */
    public function akun()
    {
        return $this->belongsTo(Akun::class, 'username', 'username');
    }
}

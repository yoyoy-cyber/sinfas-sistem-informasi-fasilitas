<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Notifikasi — sesuai ERD SINFAS
 * PK: id_notifikasi
 * FK: username → akun
 */
class Notifikasi extends Model
{
    use HasFactory;

    protected $table      = 'notifikasi';
    protected $primaryKey = 'id_notifikasi';

    protected $fillable = [
        'username',
        'pesan',
        'status_baca',
    ];

    protected $casts = [
        'status_baca' => 'boolean',
    ];

    /* ----------------------------------------------------------------
     * Relasi ke Akun (belongs-to)
     * ---------------------------------------------------------------- */
    public function akun()
    {
        return $this->belongsTo(Akun::class, 'username', 'username');
    }

    /* ----------------------------------------------------------------
     * Helper: tandai sudah dibaca
     * ---------------------------------------------------------------- */
    public function tandaiDibaca(): void
    {
        $this->update(['status_baca' => true]);
    }

    /* ----------------------------------------------------------------
     * Scope: hanya notifikasi belum dibaca
     * ---------------------------------------------------------------- */
    public function scopeBelumDibaca($query)
    {
        return $query->where('status_baca', false);
    }
}

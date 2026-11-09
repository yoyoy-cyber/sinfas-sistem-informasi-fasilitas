<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitasPeminjaman extends Model
{
    // Model ini terhubung ke tabel log yang diisi otomatis oleh DATABASE TRIGGER (Konsep Trigger)
    protected $table = 'log_aktivitas_peminjaman';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_peminjaman',
        'kode_barang',
        'username',
        'status_lama',
        'status_baru',
        'aksi',
        'catatan',
        'created_at',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(PeminjamanRequest::class, 'id_peminjaman', 'id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'kode_barang', 'kode_barang');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }
}


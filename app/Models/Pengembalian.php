<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalian';

    protected $fillable = [
        'peminjaman_id',
        'user_id',
        'kode_barang',
        'nama_barang',
        'tanggal_pengembalian',
        'kondisi_pengembalian',
        'catatan_kondisi',
        'bukti_foto',
        'keterangan',
        'status',
        'alasan_penolakan',
        'verifikasi_oleh',
        'tanggal_verifikasi',
    ];

    protected $casts = [
        'tanggal_pengembalian' => 'date',
        'tanggal_verifikasi' => 'datetime',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(PeminjamanRequest::class, 'peminjaman_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'kode_barang', 'kode_barang');
    }
}

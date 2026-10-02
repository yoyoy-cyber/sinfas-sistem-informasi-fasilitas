<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanRequest extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_requests';

    protected $fillable = [
        'user_id',
        'kode_barang',
        'nama_barang',
        'nama_peminjam',
        'role_peminjam',
        'tanggal_pinjam',
        'tanggal_kembali',
        'no_telepon',
        'alasan_keperluan',
        'alasan_penolakan',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'kode_barang', 'kode_barang');
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'peminjaman_id');
    }
}
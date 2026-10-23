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
}
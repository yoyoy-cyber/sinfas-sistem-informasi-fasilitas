<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Penyetujuan — sesuai ERD 2 SINFAS
 * PK: id_penyetujuan (auto-increment)
 */
class Penyetujuan extends Model
{
    use HasFactory;

    protected $table = 'penyetujuan';
    protected $primaryKey = 'id_penyetujuan';

    protected $fillable = [
        'id_peminjaman',
        'peminjaman_id',
        'nip',
        'status',
        'catatan',
    ];

    public function peminjamanRequest()
    {
        return $this->belongsTo(PeminjamanRequest::class, 'peminjaman_id', 'id');
    }

    public function staffSarana()
    {
        return $this->belongsTo(StaffSarana::class, 'nip', 'nip');
    }
}

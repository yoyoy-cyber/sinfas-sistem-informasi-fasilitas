<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewPeminjamanLengkap extends Model
{
    // Model ini terhubung ke DATABASE VIEW 'view_peminjaman_lengkap' (Konsep Database View)
    protected $table = 'view_peminjaman_lengkap';
    protected $primaryKey = 'id_peminjaman';
    public $incrementing = false;
    public $timestamps = false;

    // View database bersifat read-only
    protected $guarded = ['*'];
}


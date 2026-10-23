<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model StaffSarana — sesuai ERD 2 SINFAS
 * PK: nip (string)
 */
class StaffSarana extends Model
{
    use HasFactory;

    protected $table = 'staff_sarana';
    protected $primaryKey = 'nip';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nip',
        'id_akun',
        'username',
        'nama',
        'no_hp',
    ];

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun', 'id_akun');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'nip', 'nip');
    }

    public function penyetujuan()
    {
        return $this->hasMany(Penyetujuan::class, 'nip', 'nip');
    }
}

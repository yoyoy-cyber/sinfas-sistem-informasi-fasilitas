<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model Akun — tabel utama autentikasi sesuai ERD SINFAS
 * PK: username (string)
 * Relasi: 1-to-1 dengan Siswa atau Pegawai
 */
class Akun extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table      = 'akun';
    protected $primaryKey = 'username';
    public    $incrementing = false;
    protected $keyType    = 'string';

    protected $fillable = [
        'username',
        'email',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /* ----------------------------------------------------------------
     * Relasi ke Siswa (1-to-1)
     * ---------------------------------------------------------------- */
    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'username', 'username');
    }

    /* ----------------------------------------------------------------
     * Relasi ke Pegawai (1-to-1)
     * ---------------------------------------------------------------- */
    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'username', 'username');
    }

    /* ----------------------------------------------------------------
     * Relasi ke Notifikasi (1-to-many)
     * ---------------------------------------------------------------- */
    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'username', 'username');
    }

    /* ----------------------------------------------------------------
     * Helper: ambil nama lengkap (dari siswa atau pegawai)
     * ---------------------------------------------------------------- */
    public function getNamaAttribute(): string
    {
        if ($this->siswa) {
            return $this->siswa->nama;
        }
        if ($this->pegawai) {
            return $this->pegawai->nama;
        }
        return $this->username;
    }

    /* ----------------------------------------------------------------
     * Helper: ambil no_hp
     * ---------------------------------------------------------------- */
    public function getNoHpAttribute(): ?string
    {
        return $this->siswa?->no_hp ?? $this->pegawai?->no_hp;
    }

    /* ----------------------------------------------------------------
     * Helper: apakah admin sarana
     * ---------------------------------------------------------------- */
    public function isAdminSarana(): bool
    {
        return $this->role === 'admin_sarana';
    }

    /* ----------------------------------------------------------------
     * Helper: apakah admin sistem
     * ---------------------------------------------------------------- */
    public function isAdminSistem(): bool
    {
        return $this->role === 'admin_sistem';
    }

    /* ----------------------------------------------------------------
     * Helper: apakah siswa
     * ---------------------------------------------------------------- */
    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    /* ----------------------------------------------------------------
     * Helper: apakah pegawai/guru
     * ---------------------------------------------------------------- */
    public function isPegawai(): bool
    {
        return $this->role === 'pegawai';
    }
}

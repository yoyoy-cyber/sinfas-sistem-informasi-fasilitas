<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model User — alias/wrapper untuk tabel 'akun' sesuai ERD SINFAS
 * PK: username (string)
 *
 * Digunakan oleh sistem Auth Laravel (config/auth.php).
 * Kolom 'username' digunakan sebagai primary key dan credential login.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Arahkan ke tabel 'akun' sesuai ERD
    protected $table      = 'akun';
    protected $primaryKey = 'username';
    public    $incrementing = false;
    protected $keyType    = 'string';

    protected $fillable = [
        'id_akun',
        'username',
        'nama',
        'email',
        'nomor_kontak',
        'foto',
        'role',
        'password',
        'is_active',
        'must_change_password',
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
     * Relasi ke PeminjamanRequest (1-to-many) via username
     * ---------------------------------------------------------------- */
    public function peminjamanRequests()
    {
        return $this->hasMany(PeminjamanRequest::class, 'username', 'username');
    }

    /* ----------------------------------------------------------------
     * Accessor: nama_lengkap — ambil dari siswa atau pegawai
     * ---------------------------------------------------------------- */
    public function getNamaLengkapAttribute(): string
    {
        return $this->siswa?->nama ?? $this->pegawai?->nama ?? $this->username;
    }

    /* ----------------------------------------------------------------
     * Accessor: nis_nip — ambil NIS (siswa) atau NIP (pegawai)
     * ---------------------------------------------------------------- */
    public function getNisNipAttribute(): ?string
    {
        return $this->siswa?->nis ?? $this->pegawai?->nip;
    }

    /* ----------------------------------------------------------------
     * Accessor: no_hp
     * ---------------------------------------------------------------- */
    public function getNoHpAttribute(): ?string
    {
        return $this->siswa?->no_hp ?? $this->pegawai?->no_hp;
    }

    /* ----------------------------------------------------------------
     * Helper: cek apakah admin sarana
     * ---------------------------------------------------------------- */
    public function isAdminSarana(): bool
    {
        return $this->role === 'admin_sarana';
    }

    /* ----------------------------------------------------------------
     * Helper: cek apakah admin sistem
     * ---------------------------------------------------------------- */
    public function isAdminSistem(): bool
    {
        return $this->role === 'admin_sistem';
    }

    /* ----------------------------------------------------------------
     * Helper: cek apakah siswa
     * ---------------------------------------------------------------- */
    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    /* ----------------------------------------------------------------
     * Helper: cek apakah pegawai/guru
     * ---------------------------------------------------------------- */
    public function isPegawai(): bool
    {
        return in_array($this->role, ['pegawai', 'guru']);
    }

    public function isGuru(): bool
    {
        return in_array($this->role, ['pegawai', 'guru']);
    }

    /* ----------------------------------------------------------------
     * Accessor: role_label
     * ---------------------------------------------------------------- */
    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'pegawai', 'guru' => 'Guru',
            'siswa' => 'Siswa',
            'admin_sarana' => 'Admin Sarana',
            'admin_sistem' => 'Admin Sistem',
            default => ucfirst(str_replace('_', ' ', $this->role ?? '')),
        };
    }
}
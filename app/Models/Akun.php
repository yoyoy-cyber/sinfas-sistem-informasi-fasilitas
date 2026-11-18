<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Akun
 *
 * Model utama untuk mengelola entitas pengguna dan proses autentikasi (login)
 * pada Sistem Informasi Fasilitas (SINFAS).
 *
 * Aturan Bisnis & Karakteristik:
 * - Menggunakan tabel 'akun' sesuai ERD SINFAS.
 * - Primary key berupa 'username' bertipe string (bukan auto-increment integer),
 *   sehingga $incrementing disetel ke false.
 * - Berelasi 1-to-1 dengan tabel 'siswa' (jika role siswa) atau 'pegawai' (jika role guru/staff).
 *
 * @package App\Models
 */
class Akun extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Nama tabel pada basis data.
     * @var string
     */
    protected $table = 'akun';

    /**
     * Kolom primary key tabel.
     * Menggunakan 'username' unik sebagai identitas akun.
     * @var string
     */
    protected $primaryKey = 'username';

    /**
     * Menandakan apakah primary key bersifat auto-increment.
     * Disetel false karena username diisi manual (misal NIS siswa atau NIP pegawai).
     * @var bool
     */
    public $incrementing = false;

    /**
     * Tipe data dari primary key.
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     * @var array<int, string>
     */
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

    /**
     * Atribut yang disembunyikan saat serialisasi model (JSON / array).
     * Digunakan untuk keamanan data sensitif seperti password hash.
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Menentukan konversi tipe data otomatis untuk atribut tertentu.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // Memastikan password otomatis di-hash secara aman menggunakan algoritma standar Laravel
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi 1-ke-1 ke tabel siswa.
     * Menghubungkan akun dengan profil siswa berdasarkan kolom foreign key 'username'.
     *
     * @return HasOne Relasi ke model Siswa
     */
    public function siswa(): HasOne
    {
        return $this->hasOne(Siswa::class, 'username', 'username');
    }

    /**
     * Relasi 1-ke-1 ke tabel pegawai.
     * Menghubungkan akun dengan profil guru/pegawai berdasarkan kolom foreign key 'username'.
     *
     * @return HasOne Relasi ke model Pegawai
     */
    public function pegawai(): HasOne
    {
        return $this->hasOne(Pegawai::class, 'username', 'username');
    }

    /**
     * Relasi 1-ke-banyak ke notifikasi sistem.
     *
     * @return HasMany Relasi ke daftar model Notifikasi
     */
    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'username', 'username');
    }

    /**
     * Accessor untuk mendapatkan nama lengkap pengguna secara dinamis.
     *
     * Alasan Logika:
     * Karena data nama asli dapat tersimpan di profil siswa atau pegawai,
     * method ini memeriksa keberadaan relasi tersebut terlebih dahulu sebelum fallback ke username.
     *
     * @return string Nama lengkap siswa/pegawai atau username jika relasi belum terisi.
     */
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

    /**
     * Accessor untuk mendapatkan nomor HP/telepon pengguna.
     *
     * @return string|null Nomor kontak dari profil siswa atau pegawai, null jika tidak ada.
     */
    public function getNoHpAttribute(): ?string
    {
        return $this->siswa?->no_hp ?? $this->pegawai?->no_hp;
    }

    /**
     * Memeriksa apakah akun memiliki hak akses sebagai Admin Sarana & Prasarana.
     *
     * @return bool True jika role adalah 'admin_sarana', false jika sebaliknya.
     */
    public function isAdminSarana(): bool
    {
        return $this->role === 'admin_sarana';
    }

    /**
     * Memeriksa apakah akun memiliki hak akses sebagai Super Admin / Admin Sistem.
     *
     * @return bool True jika role adalah 'admin_sistem', false jika sebaliknya.
     */
    public function isAdminSistem(): bool
    {
        return $this->role === 'admin_sistem';
    }

    /**
     * Memeriksa apakah akun berstatus sebagai Siswa peminjam.
     *
     * @return bool True jika role adalah 'siswa', false jika sebaliknya.
     */
    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    /**
     * Memeriksa apakah akun berstatus sebagai Pegawai atau Guru peminjam.
     *
     * @return bool True jika role adalah 'pegawai' atau 'guru', false jika sebaliknya.
     */
    public function isPegawai(): bool
    {
        return in_array($this->role, ['pegawai', 'guru']);
    }

    /**
     * Alias method untuk memeriksa apakah akun adalah Guru.
     *
     * @return bool True jika role adalah 'pegawai' atau 'guru'.
     */
    public function isGuru(): bool
    {
        return in_array($this->role, ['pegawai', 'guru']);
    }

    /**
     * Accessor untuk menghasilkan teks label role yang rapi dan mudah dibaca pada antarmuka pengguna.
     *
     * Contoh Penggunaan di Blade:
     * {{ Auth::user()->role_label }} -> Menghasilkan "Admin Sarana" atau "Siswa"
     *
     * @return string Label role dalam Bahasa Indonesia.
     */
    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'pegawai', 'guru' => 'Guru',
            'siswa'           => 'Siswa',
            'admin_sarana'    => 'Admin Sarana',
            'admin_sistem'    => 'Admin Sistem',
            default           => ucfirst(str_replace('_', ' ', $this->role ?? '')),
        };
    }
}

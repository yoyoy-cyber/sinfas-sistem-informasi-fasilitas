<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration: Fix semua tabel agar sesuai ERD SINFAS
 *
 * ERD menggunakan:
 *  - AKUN (PK: username, email, role, password)
 *  - SISWA (PK: nis, FK: username → akun, nama, kelas, no_hp)
 *  - PEGAWAI (PK: nip, FK: username → akun, nama, jabatan, no_hp)
 *  - BARANG (PK: kode_barang, FK: id_kategori)
 *  - PEMINJAMAN (PK: kode_pinjam, FK: nis → siswa, FK: kode_barang → barang)
 *  - PENGEMBALIAN (PK: kode_kembali, FK: kode_pinjam → peminjaman)
 *  - NOTIFIKASI (PK: id_notifikasi, FK: username → akun)
 *  - KATEGORI (PK: id_kategori)
 *
 * Masalah lama:
 *  - Sistem auth pakai tabel 'users' (bawaan Laravel), bukan tabel 'akun'
 *  - Tabel 'siswa' tidak punya kolom 'kelas' dan 'username'
 *  - Tabel 'pegawai' tidak punya kolom 'jabatan', 'no_hp', 'username'
 *  - Tabel 'akun' tidak dipakai sama sekali
 *  - Tabel 'notifikasi' pakai 'id_akun' (bigint) bukan 'username' (string)
 *  - Tabel 'peminjaman_requests' pakai 'user_id' → 'users', bukan 'nis' → 'siswa'
 *  - Tabel 'pengembalian' pakai 'user_id' → 'users', bukan 'kode_kembali' sesuai ERD
 */
return new class extends Migration
{
    public function up(): void
    {
        // ============================================================
        // STEP 0: Drop tabel notifikasi dulu (ada FK ke akun)
        // sebelum kita drop dan recreate tabel akun
        // ============================================================
        Schema::dropIfExists('notifikasi');

        // ============================================================
        // STEP 1: Perbaiki tabel 'akun' agar sesuai ERD
        // ERD: username(PK), email, role, password
        // DB lama: id_akun(PK bigint), nis, nip, nama, nomor_kontak, role, username(unique), password
        // Strategi: drop & recreate (tabel masih kosong)
        // ============================================================
        Schema::dropIfExists('akun');
        Schema::create('akun', function (Blueprint $table) {
            $table->string('username', 50)->primary();
            $table->string('email')->unique();
            $table->enum('role', ['siswa', 'pegawai', 'admin_sarana', 'admin_sistem'])->default('siswa');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // ============================================================
        // STEP 2: Perbaiki tabel 'siswa' agar sesuai ERD
        // ERD: nis(PK), username(FK → akun), nama, kelas, no_hp
        // DB lama: nis(PK), nama, email, no_hp
        // ============================================================
        Schema::table('siswa', function (Blueprint $table) {
            // Tambah kolom username (FK ke akun)
            if (!Schema::hasColumn('siswa', 'username')) {
                $table->string('username', 50)->nullable()->unique()->after('nis');
                $table->foreign('username')->references('username')->on('akun')->nullOnDelete();
            }
            // Tambah kolom kelas
            if (!Schema::hasColumn('siswa', 'kelas')) {
                $table->string('kelas', 20)->nullable()->after('nama');
            }
            // Hapus kolom email (tidak ada di ERD siswa)
            if (Schema::hasColumn('siswa', 'email')) {
                $table->dropColumn('email');
            }
        });

        // ============================================================
        // STEP 3: Perbaiki tabel 'pegawai' agar sesuai ERD
        // ERD: nip(PK), username(FK → akun), nama, jabatan, no_hp
        // DB lama: nip(PK), nama
        // ============================================================
        Schema::table('pegawai', function (Blueprint $table) {
            // Tambah kolom username (FK ke akun)
            if (!Schema::hasColumn('pegawai', 'username')) {
                $table->string('username', 50)->nullable()->unique()->after('nip');
                $table->foreign('username')->references('username')->on('akun')->nullOnDelete();
            }
            // Tambah kolom jabatan
            if (!Schema::hasColumn('pegawai', 'jabatan')) {
                $table->string('jabatan', 100)->nullable()->after('nama');
            }
            // Tambah kolom no_hp
            if (!Schema::hasColumn('pegawai', 'no_hp')) {
                $table->string('no_hp', 20)->nullable()->after('jabatan');
            }
        });

        // ============================================================
        // STEP 4: Perbaiki tabel 'notifikasi' agar sesuai ERD
        // ERD: id_notifikasi(PK), username(FK → akun), pesan, status_baca, created_at
        // DB lama: id_notifikasi(PK), id_akun(FK bigint → akun.id_akun), pesan, status_baca
        // ============================================================
        Schema::dropIfExists('notifikasi');
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id('id_notifikasi');
            $table->string('username', 50);
            $table->text('pesan');
            $table->boolean('status_baca')->default(false);
            $table->timestamps();

            $table->foreign('username')->references('username')->on('akun')->onDelete('cascade');
        });

        // ============================================================
        // STEP 5: Tambah kolom 'nis' ke 'peminjaman_requests'
        // agar peminjaman terhubung ke siswa sesuai ERD
        // (tetap simpan user_id untuk backward compat sementara)
        // ============================================================
        Schema::table('peminjaman_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjaman_requests', 'nis')) {
                $table->string('nis', 20)->nullable()->after('user_id');
                $table->foreign('nis')->references('nis')->on('siswa')->nullOnDelete();
            }
            if (!Schema::hasColumn('peminjaman_requests', 'username')) {
                $table->string('username', 50)->nullable()->after('nis');
                $table->foreign('username')->references('username')->on('akun')->nullOnDelete();
            }
        });

        // ============================================================
        // STEP 6: Perbaiki tabel 'pengembalian' - tambah kolom kode_kembali
        // agar sesuai ERD (ERD pakai PK: kode_kembali, FK: kode_pinjam)
        // Tabel pengembalian sudah ada dengan 'id' sebagai PK,
        // kita tambah kolom kode_kembali sebagai alias string unik
        // ============================================================
        Schema::table('pengembalian', function (Blueprint $table) {
            if (!Schema::hasColumn('pengembalian', 'kode_kembali')) {
                $table->string('kode_kembali', 30)->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('pengembalian', 'username')) {
                $table->string('username', 50)->nullable()->after('user_id');
                $table->foreign('username')->references('username')->on('akun')->nullOnDelete();
            }
        });

        // ============================================================
        // STEP 7: Migrate data dari tabel 'users' ke 'akun', 'siswa', 'pegawai'
        // ============================================================
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            $username = $user->nis_nip ?? $user->email;
            $role = $user->role ?? 'siswa';

            // Sesuaikan role ke format ERD
            if (!in_array($role, ['siswa', 'pegawai', 'admin_sarana', 'admin_sistem'])) {
                $role = ($role === 'guru') ? 'pegawai' : 'siswa';
            }

            // Insert ke tabel akun
            DB::table('akun')->insertOrIgnore([
                'username'   => $username,
                'email'      => $user->email ?? $username . '@sinfas.sch.id',
                'role'       => $role,
                'password'   => $user->password,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);

            // Insert ke tabel siswa jika role siswa
            if ($role === 'siswa' && !empty($user->nis_nip)) {
                DB::table('siswa')->insertOrIgnore([
                    'nis'        => $user->nis_nip,
                    'username'   => $username,
                    'nama'       => $user->nama_lengkap ?? 'Siswa',
                    'no_hp'      => $user->no_hp ?? null,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ]);
            }

            // Insert ke tabel pegawai jika role pegawai/admin
            if (in_array($role, ['pegawai', 'admin_sarana', 'admin_sistem']) && !empty($user->nis_nip)) {
                DB::table('pegawai')->insertOrIgnore([
                    'nip'        => $user->nis_nip,
                    'username'   => $username,
                    'nama'       => $user->nama_lengkap ?? 'Pegawai',
                    'no_hp'      => $user->no_hp ?? null,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ]);
            }
        }

        // ============================================================
        // STEP 8: Update peminjaman_requests - isi kolom nis dan username
        // berdasarkan user_id yang ada
        // ============================================================
        $peminjamanList = DB::table('peminjaman_requests')->get();
        foreach ($peminjamanList as $peminjaman) {
            $user = DB::table('users')->find($peminjaman->user_id);
            if ($user) {
                $username = $user->nis_nip ?? $user->email;
                $nis = (in_array($user->role ?? 'siswa', ['siswa'])) ? $user->nis_nip : null;
                DB::table('peminjaman_requests')->where('id', $peminjaman->id)->update([
                    'nis'      => $nis,
                    'username' => $username,
                ]);
            }
        }

        // ============================================================
        // STEP 9: Update pengembalian - isi kolom username dan kode_kembali
        // ============================================================
        $pengembalianList = DB::table('pengembalian')->get();
        foreach ($pengembalianList as $item) {
            $user = DB::table('users')->find($item->user_id);
            $username = $user ? ($user->nis_nip ?? $user->email) : null;
            $kodeKembali = 'KB-' . str_pad($item->id, 6, '0', STR_PAD_LEFT);
            DB::table('pengembalian')->where('id', $item->id)->update([
                'username'     => $username,
                'kode_kembali' => $kodeKembali,
            ]);
        }
    }

    public function down(): void
    {
        // Hapus foreign key dan kolom yang ditambahkan di pengembalian
        Schema::table('pengembalian', function (Blueprint $table) {
            if (Schema::hasColumn('pengembalian', 'username')) {
                $table->dropForeign(['username']);
                $table->dropColumn('username');
            }
            if (Schema::hasColumn('pengembalian', 'kode_kembali')) {
                $table->dropColumn('kode_kembali');
            }
        });

        // Hapus foreign key dan kolom yang ditambahkan di peminjaman_requests
        Schema::table('peminjaman_requests', function (Blueprint $table) {
            if (Schema::hasColumn('peminjaman_requests', 'username')) {
                $table->dropForeign(['username']);
                $table->dropColumn('username');
            }
            if (Schema::hasColumn('peminjaman_requests', 'nis')) {
                $table->dropForeign(['nis']);
                $table->dropColumn('nis');
            }
        });

        // Drop dan recreate notifikasi versi lama
        Schema::dropIfExists('notifikasi');
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id('id_notifikasi');
            $table->unsignedBigInteger('id_akun');
            $table->text('pesan');
            $table->boolean('status_baca')->default(false);
            $table->timestamps();
        });

        // Hapus kolom dari pegawai
        Schema::table('pegawai', function (Blueprint $table) {
            if (Schema::hasColumn('pegawai', 'username')) {
                $table->dropForeign(['username']);
                $table->dropColumn('username');
            }
            if (Schema::hasColumn('pegawai', 'jabatan')) {
                $table->dropColumn('jabatan');
            }
            if (Schema::hasColumn('pegawai', 'no_hp')) {
                $table->dropColumn('no_hp');
            }
        });

        // Hapus kolom dari siswa
        Schema::table('siswa', function (Blueprint $table) {
            if (Schema::hasColumn('siswa', 'username')) {
                $table->dropForeign(['username']);
                $table->dropColumn('username');
            }
            if (Schema::hasColumn('siswa', 'kelas')) {
                $table->dropColumn('kelas');
            }
            if (!Schema::hasColumn('siswa', 'email')) {
                $table->string('email')->nullable()->after('nama');
            }
        });

        // Recreate akun versi lama
        Schema::dropIfExists('akun');
        Schema::create('akun', function (Blueprint $table) {
            $table->id('id_akun');
            $table->string('nis', 20)->nullable();
            $table->string('nip', 20)->nullable();
            $table->string('nama');
            $table->string('nomor_kontak', 20)->nullable();
            $table->enum('role', ['siswa', 'admin_sarana', 'admin_sistem']);
            $table->string('username')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }
};

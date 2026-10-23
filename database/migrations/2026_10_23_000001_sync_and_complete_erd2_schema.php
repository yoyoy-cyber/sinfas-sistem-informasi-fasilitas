<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengadopsi 100% skema ERD2 tanpa menghapus atau merusak data yang sudah ada.
     */
    public function up(): void
    {
        // ============================================================
        // 1. Lengkapi tabel 'akun' dengan field ERD2
        // ============================================================
        if (!Schema::hasColumn('akun', 'id_akun')) {
            DB::statement('ALTER TABLE akun ADD COLUMN id_akun BIGINT UNSIGNED AUTO_INCREMENT UNIQUE FIRST');
        }

        Schema::table('akun', function (Blueprint $table) {
            if (!Schema::hasColumn('akun', 'nama')) {
                $table->string('nama', 255)->nullable()->after('id_akun');
            }
            if (!Schema::hasColumn('akun', 'nomor_kontak')) {
                $table->string('nomor_kontak', 20)->nullable()->after('email');
            }
            if (!Schema::hasColumn('akun', 'foto')) {
                $table->string('foto', 255)->nullable()->after('nomor_kontak');
            }
            if (!Schema::hasColumn('akun', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('foto');
            }
            if (!Schema::hasColumn('akun', 'must_change_password')) {
                $table->boolean('must_change_password')->default(false)->after('is_active');
            }
        });

        // Backfill nama & nomor_kontak di akun dari siswa & pegawai
        DB::statement("
            UPDATE akun a
            JOIN siswa s ON a.username = s.username
            SET a.nama = COALESCE(a.nama, s.nama),
                a.nomor_kontak = COALESCE(a.nomor_kontak, s.no_hp)
        ");

        DB::statement("
            UPDATE akun a
            JOIN pegawai p ON a.username = p.username
            SET a.nama = COALESCE(a.nama, p.nama),
                a.nomor_kontak = COALESCE(a.nomor_kontak, p.no_hp)
        ");

        // ============================================================
        // 2. Tambah kolom id_akun ke tabel 'siswa' & 'pegawai'
        // ============================================================
        Schema::table('siswa', function (Blueprint $table) {
            if (!Schema::hasColumn('siswa', 'id_akun')) {
                $table->unsignedBigInteger('id_akun')->nullable()->after('nis');
                $table->foreign('id_akun')->references('id_akun')->on('akun')->nullOnDelete();
            }
            if (!Schema::hasColumn('siswa', 'email')) {
                $table->string('email', 255)->nullable()->after('nama');
            }
        });

        DB::statement("
            UPDATE siswa s
            JOIN akun a ON s.username = a.username
            SET s.id_akun = a.id_akun,
                s.email = COALESCE(s.email, a.email)
        ");

        Schema::table('pegawai', function (Blueprint $table) {
            if (!Schema::hasColumn('pegawai', 'id_akun')) {
                $table->unsignedBigInteger('id_akun')->nullable()->after('nip');
                $table->foreign('id_akun')->references('id_akun')->on('akun')->nullOnDelete();
            }
            if (!Schema::hasColumn('pegawai', 'email')) {
                $table->string('email', 255)->nullable()->after('nama');
            }
        });

        DB::statement("
            UPDATE pegawai p
            JOIN akun a ON p.username = a.username
            SET p.id_akun = a.id_akun,
                p.email = COALESCE(p.email, a.email)
        ");

        // ============================================================
        // 3. Buat tabel 'staff_sarana' sesuai ERD2
        // ============================================================
        if (!Schema::hasTable('staff_sarana')) {
            Schema::create('staff_sarana', function (Blueprint $table) {
                $table->string('nip', 20)->primary();
                $table->unsignedBigInteger('id_akun')->nullable();
                $table->string('username', 50)->nullable();
                $table->string('nama', 255);
                $table->string('no_hp', 20)->nullable();
                $table->timestamps();

                $table->foreign('nip')->references('nip')->on('pegawai')->cascadeOnDelete();
                $table->foreign('id_akun')->references('id_akun')->on('akun')->nullOnDelete();
            });

            // Backfill staff_sarana dari akun/pegawai yang role-nya admin_sarana
            $adminSaranaPegawai = DB::table('pegawai')
                ->join('akun', 'pegawai.username', '=', 'akun.username')
                ->where('akun.role', 'admin_sarana')
                ->select('pegawai.nip', 'akun.id_akun', 'akun.username', 'pegawai.nama', 'pegawai.no_hp')
                ->get();

            foreach ($adminSaranaPegawai as $staff) {
                DB::table('staff_sarana')->insertOrIgnore([
                    'nip'        => $staff->nip,
                    'id_akun'    => $staff->id_akun,
                    'username'   => $staff->username,
                    'nama'       => $staff->nama,
                    'no_hp'      => $staff->no_hp,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // ============================================================
        // 4. Tambah kolom pelengkap di 'barang' (foto sebagai alias gambar)
        // ============================================================
        Schema::table('barang', function (Blueprint $table) {
            if (!Schema::hasColumn('barang', 'foto')) {
                $table->string('foto', 255)->nullable()->after('keterangan');
            }
        });

        // Sinkronisasi data gambar ke foto
        DB::statement("UPDATE barang SET foto = gambar WHERE foto IS NULL AND gambar IS NOT NULL");

        // ============================================================
        // 5. Tambah id_peminjaman & id_akun ke 'peminjaman_requests'
        // ============================================================
        Schema::table('peminjaman_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjaman_requests', 'id_peminjaman')) {
                $table->string('id_peminjaman', 30)->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('peminjaman_requests', 'id_akun')) {
                $table->unsignedBigInteger('id_akun')->nullable()->after('username');
                $table->foreign('id_akun')->references('id_akun')->on('akun')->nullOnDelete();
            }
            if (!Schema::hasColumn('peminjaman_requests', 'lokasi_penggunaan')) {
                $table->string('lokasi_penggunaan', 255)->nullable()->after('alasan_keperluan');
            }
            if (!Schema::hasColumn('peminjaman_requests', 'keterangan_penggunaan')) {
                $table->text('keterangan_penggunaan')->nullable()->after('lokasi_penggunaan');
            }
        });

        // Backfill id_peminjaman (format PMJ-000001) dan id_akun
        DB::statement("UPDATE peminjaman_requests SET id_peminjaman = CONCAT('PMJ-', LPAD(id, 6, '0')) WHERE id_peminjaman IS NULL");
        DB::statement("
            UPDATE peminjaman_requests pr
            JOIN akun a ON pr.username = a.username
            SET pr.id_akun = a.id_akun
            WHERE pr.id_akun IS NULL
        ");

        // ============================================================
        // 6. Buat tabel 'penyetujuan' sesuai ERD2
        // ============================================================
        if (!Schema::hasTable('penyetujuan')) {
            Schema::create('penyetujuan', function (Blueprint $table) {
                $table->id('id_penyetujuan');
                $table->string('id_peminjaman', 30)->nullable();
                $table->unsignedBigInteger('peminjaman_id')->nullable();
                $table->string('nip', 20)->nullable();
                $table->string('status', 50)->default('disetujui');
                $table->text('catatan')->nullable();
                $table->timestamps();

                $table->foreign('id_peminjaman')->references('id_peminjaman')->on('peminjaman_requests')->nullOnDelete();
                $table->foreign('peminjaman_id')->references('id')->on('peminjaman_requests')->cascadeOnDelete();
            });

            // Backfill data penyetujuan dari peminjaman yang sudah disetujui / ditolak
            $approvedStaff = DB::table('staff_sarana')->first();
            $staffNip = $approvedStaff?->nip ?? 'NIP002';

            $processedRequests = DB::table('peminjaman_requests')
                ->whereIn('status', ['disetujui', 'ditolak', 'selesai'])
                ->get();

            foreach ($processedRequests as $req) {
                DB::table('penyetujuan')->insertOrIgnore([
                    'id_peminjaman' => $req->id_peminjaman ?? ('PMJ-' . str_pad($req->id, 6, '0', STR_PAD_LEFT)),
                    'peminjaman_id' => $req->id,
                    'nip'           => $staffNip,
                    'status'        => $req->status,
                    'catatan'       => $req->catatan_admin ?? $req->alasan_penolakan ?? 'Diproses melalui sistem',
                    'created_at'    => $req->updated_at ?? now(),
                    'updated_at'    => $req->updated_at ?? now(),
                ]);
            }
        }

        // ============================================================
        // 7. Lengkapi tabel 'pengembalian' dengan field alias ERD2
        // ============================================================
        Schema::table('pengembalian', function (Blueprint $table) {
            if (!Schema::hasColumn('pengembalian', 'id_peminjaman')) {
                $table->string('id_peminjaman', 30)->nullable()->after('peminjaman_id');
            }
            if (!Schema::hasColumn('pengembalian', 'kode_pinjam')) {
                $table->string('kode_pinjam', 30)->nullable()->after('id_peminjaman');
            }
            if (!Schema::hasColumn('pengembalian', 'kondisi_barang')) {
                $table->string('kondisi_barang', 255)->nullable()->after('kondisi_pengembalian');
            }
            if (!Schema::hasColumn('pengembalian', 'catatan')) {
                $table->text('catatan')->nullable()->after('catatan_kondisi');
            }
            if (!Schema::hasColumn('pengembalian', 'bukti_foto_video')) {
                $table->string('bukti_foto_video', 255)->nullable()->after('bukti_foto');
            }
        });

        // Sinkronkan data pengembalian
        DB::statement("
            UPDATE pengembalian p
            JOIN peminjaman_requests pr ON p.peminjaman_id = pr.id
            SET p.id_peminjaman = pr.id_peminjaman,
                p.kode_pinjam = pr.id_peminjaman,
                p.kondisi_barang = COALESCE(p.kondisi_barang, p.kondisi_pengembalian),
                p.catatan = COALESCE(p.catatan, p.catatan_kondisi),
                p.bukti_foto_video = COALESCE(p.bukti_foto_video, p.bukti_foto)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyetujuan');
        Schema::dropIfExists('staff_sarana');

        Schema::table('pengembalian', function (Blueprint $table) {
            $table->dropColumn(['id_peminjaman', 'kode_pinjam', 'kondisi_barang', 'catatan', 'bukti_foto_video']);
        });

        Schema::table('peminjaman_requests', function (Blueprint $table) {
            $table->dropForeign(['id_akun']);
            $table->dropColumn(['id_peminjaman', 'id_akun', 'lokasi_penggunaan', 'keterangan_penggunaan']);
        });

        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn(['foto']);
        });

        Schema::table('pegawai', function (Blueprint $table) {
            $table->dropForeign(['id_akun']);
            $table->dropColumn(['id_akun', 'email']);
        });

        Schema::table('siswa', function (Blueprint $table) {
            $table->dropForeign(['id_akun']);
            $table->dropColumn(['id_akun', 'email']);
        });

        Schema::table('akun', function (Blueprint $table) {
            $table->dropColumn(['nama', 'nomor_kontak', 'foto', 'is_active', 'must_change_password']);
        });
    }
};

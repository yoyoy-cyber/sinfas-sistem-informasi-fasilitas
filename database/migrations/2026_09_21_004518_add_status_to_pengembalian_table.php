<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengembalian', function (Blueprint $table) {
            if (!Schema::hasColumn('pengembalian', 'status')) {
                $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending')->after('keterangan');
            }
            if (!Schema::hasColumn('pengembalian', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('peminjaman_id');
            }
            if (!Schema::hasColumn('pengembalian', 'alasan_penolakan')) {
                $table->text('alasan_penolakan')->nullable()->after('status');
            }
            if (!Schema::hasColumn('pengembalian', 'verifikasi_oleh')) {
                $table->string('verifikasi_oleh')->nullable()->after('alasan_penolakan');
            }
            if (!Schema::hasColumn('pengembalian', 'tanggal_verifikasi')) {
                $table->timestamp('tanggal_verifikasi')->nullable()->after('verifikasi_oleh');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengembalian', function (Blueprint $table) {
            $table->dropColumn(['status', 'user_id', 'alasan_penolakan', 'verifikasi_oleh', 'tanggal_verifikasi']);
        });
    }
};
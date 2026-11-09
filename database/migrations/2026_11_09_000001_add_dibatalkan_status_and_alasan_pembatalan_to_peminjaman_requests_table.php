<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('peminjaman_requests', function (Blueprint $table) {
            // Ubah tipe status menjadi string VARCHAR(50) agar mendukung status 'dibatalkan'
            // dan tidak error data truncation saat MySQL ENUM aktif
            if (Schema::hasColumn('peminjaman_requests', 'status')) {
                // Di MySQL kita gunakan statement ALTER TABLE agar aman dari ketergantungan doctrine/dbal
                try {
                    DB::statement("ALTER TABLE peminjaman_requests MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'pending'");
                } catch (\Throwable $e) {
                    // Fallback jika bukan MySQL
                }
            }

            // Tambahkan kolom alasan_pembatalan jika belum ada
            if (!Schema::hasColumn('peminjaman_requests', 'alasan_pembatalan')) {
                $table->text('alasan_pembatalan')->nullable()->after('alasan_penolakan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_requests', function (Blueprint $table) {
            if (Schema::hasColumn('peminjaman_requests', 'alasan_pembatalan')) {
                $table->dropColumn('alasan_pembatalan');
            }
        });
    }
};

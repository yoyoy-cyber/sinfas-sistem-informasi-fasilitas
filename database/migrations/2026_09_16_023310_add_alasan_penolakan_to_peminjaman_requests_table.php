<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman_requests', function (Blueprint $table) {
            // Tambah kolom alasan_keperluan jika belum ada
            if (!Schema::hasColumn('peminjaman_requests', 'alasan_keperluan')) {
                $table->text('alasan_keperluan')->nullable()->after('no_telepon');
            }
            
            // Tambah kolom alasan_penolakan jika belum ada
            if (!Schema::hasColumn('peminjaman_requests', 'alasan_penolakan')) {
                $table->text('alasan_penolakan')->nullable()->after('alasan_keperluan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman_requests', function (Blueprint $table) {
            if (Schema::hasColumn('peminjaman_requests', 'alasan_penolakan')) {
                $table->dropColumn('alasan_penolakan');
            }
            if (Schema::hasColumn('peminjaman_requests', 'alasan_keperluan')) {
                $table->dropColumn('alasan_keperluan');
            }
        });
    }
};
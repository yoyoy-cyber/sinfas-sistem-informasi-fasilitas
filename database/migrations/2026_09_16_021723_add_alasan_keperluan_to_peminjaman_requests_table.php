<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjaman_requests', 'alasan_keperluan')) {
                $table->text('alasan_keperluan')->nullable()->after('no_telepon');
            }
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman_requests', function (Blueprint $table) {
            $table->dropColumn('alasan_keperluan');
        });
    }
};
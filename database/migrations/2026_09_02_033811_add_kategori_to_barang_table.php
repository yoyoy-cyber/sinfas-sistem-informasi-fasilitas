<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            // Tambahkan kolom yang hilang
            if (!Schema::hasColumn('barang', 'kategori')) {
                $table->string('kategori')->nullable()->after('nama_barang');
            }
            
            if (!Schema::hasColumn('barang', 'status')) {
                $table->enum('status', ['tersedia', 'dipinjam', 'rusak'])->default('tersedia')->after('kategori');
            }
            
            if (!Schema::hasColumn('barang', 'gambar')) {
                $table->string('gambar')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('barang', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('gambar');
            }
        });
    }

    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'status', 'gambar', 'deskripsi']);
        });
    }
};
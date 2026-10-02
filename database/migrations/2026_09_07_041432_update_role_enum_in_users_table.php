<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        // LANGKAH 1: Ubah kolom role jadi VARCHAR dulu (bebas, tidak ada batasan ENUM)
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(50) DEFAULT 'siswa'");
        
        // LANGKAH 2: Update semua data 'admin' menjadi 'admin_sarana'
        DB::table('users')->where('role', 'admin')->update(['role' => 'admin_sarana']);
        
        // LANGKAH 3: Baru ubah kembali ke ENUM dengan nilai yang lengkap
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('siswa', 'guru', 'admin_sarana', 'admin_sistem') DEFAULT 'siswa'");
    }

    public function down(): void
    {
        // Kembalikan semua admin_sarana ke admin
        DB::table('users')->where('role', 'admin_sarana')->update(['role' => 'admin']);
        
        // Ubah ENUM kembali ke versi lama
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('siswa', 'guru', 'admin') DEFAULT 'siswa'");
    }
};
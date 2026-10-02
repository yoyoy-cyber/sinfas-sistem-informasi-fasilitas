<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        // Cek apakah sudah ada data
        $existingCount = DB::table('kategori')->count();
        
        if ($existingCount > 0) {
            $this->command->info('Kategori sudah ada, skip seeding...');
            return;
        }

        $kategoris = [
            ['nama_kategori' => 'Elektronik'],
            ['nama_kategori' => 'Audio'],
            ['nama_kategori' => 'Aksesoris'],
            ['nama_kategori' => 'Alat Tulis'],
            ['nama_kategori' => 'Jaringan'],
            ['nama_kategori' => 'Furniture'],
        ];

        foreach ($kategoris as $kategori) {
            DB::table('kategori')->insert([
                'nama_kategori' => $kategori['nama_kategori'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Kategori berhasil ditambahkan!');
    }
}
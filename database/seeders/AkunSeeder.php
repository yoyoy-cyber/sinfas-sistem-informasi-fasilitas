<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Pegawai;
use App\Models\Kategori;

/**
 * Seeder: Buat akun default untuk SINFAS
 * Sesuai ERD: akun (username PK), siswa (FK username), pegawai (FK username)
 */
class AkunSeeder extends Seeder
{
    public function run(): void
    {
        // ── Kategori awal ─────────────────────────────────────────────
        $kategoriList = [
            'Alat Tulis Kantor',
            'Peralatan Elektronik',
            'Furnitur',
            'Peralatan Olahraga',
            'Peralatan Laboratorium',
            'Peralatan Kebersihan',
        ];

        foreach ($kategoriList as $nama) {
            Kategori::firstOrCreate(['nama_kategori' => $nama]);
        }

        // ── Admin Sistem ──────────────────────────────────────────────
        User::firstOrCreate(['username' => 'admin.sistem'], [
            'username' => 'admin.sistem',
            'email'    => 'admin.sistem@sinfas.sch.id',
            'role'     => 'admin_sistem',
            'password' => Hash::make('admin123'),
        ]);

        Pegawai::firstOrCreate(['nip' => 'NIP001'], [
            'nip'      => 'NIP001',
            'username' => 'admin.sistem',
            'nama'     => 'Administrator Sistem',
            'jabatan'  => 'Admin Sistem',
            'no_hp'    => '08100000001',
        ]);

        // ── Admin Sarana ───────────────────────────────────────────────
        User::firstOrCreate(['username' => 'admin.sarana'], [
            'username' => 'admin.sarana',
            'email'    => 'admin.sarana@sinfas.sch.id',
            'role'     => 'admin_sarana',
            'password' => Hash::make('admin123'),
        ]);

        Pegawai::firstOrCreate(['nip' => 'NIP002'], [
            'nip'      => 'NIP002',
            'username' => 'admin.sarana',
            'nama'     => 'Administrator Sarana',
            'jabatan'  => 'Staff Sarana',
            'no_hp'    => '08100000002',
        ]);

        // ── Contoh Siswa ──────────────────────────────────────────────
        User::firstOrCreate(['username' => 'siswa.demo'], [
            'username' => 'siswa.demo',
            'email'    => 'siswa.demo@sinfas.sch.id',
            'role'     => 'siswa',
            'password' => Hash::make('siswa123'),
        ]);

        Siswa::firstOrCreate(['nis' => '2024001'], [
            'nis'      => '2024001',
            'username' => 'siswa.demo',
            'nama'     => 'Siswa Demo',
            'kelas'    => 'XII IPA 1',
            'no_hp'    => '08200000001',
        ]);

        // ── Contoh Pegawai/Guru ───────────────────────────────────────
        User::firstOrCreate(['username' => 'guru.demo'], [
            'username' => 'guru.demo',
            'email'    => 'guru.demo@sinfas.sch.id',
            'role'     => 'pegawai',
            'password' => Hash::make('guru123'),
        ]);

        Pegawai::firstOrCreate(['nip' => 'NIP003'], [
            'nip'      => 'NIP003',
            'username' => 'guru.demo',
            'nama'     => 'Guru Demo',
            'jabatan'  => 'Guru Mata Pelajaran',
            'no_hp'    => '08300000001',
        ]);

        $this->command->info('✅ Akun default berhasil dibuat:');
        $this->command->table(
            ['Username', 'Password', 'Role'],
            [
                ['admin.sistem', 'admin123', 'Admin Sistem'],
                ['admin.sarana', 'admin123', 'Admin Sarana'],
                ['siswa.demo',   'siswa123', 'Siswa'],
                ['guru.demo',    'guru123',  'Pegawai/Guru'],
            ]
        );
    }
}

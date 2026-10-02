<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run()
    {
        // Cek apakah sudah ada data
        $existingCount = Barang::count();
        
        if ($existingCount > 0) {
            $this->command->info('Barang sudah ada, skip seeding...');
            return;
        }

        $barangs = [
            [
                'kode_barang' => 'BRG001',
                'id_kategori' => 1, // Elektronik
                'nama_barang' => 'Proyektor',
                'merk_model' => 'Epson EB-X05',
                'no_seri_pabrik' => 'SN123456',
                'ukuran_dimensi' => '30x25x10 cm',
                'bahan' => 'Plastik & Logam',
                'tahun_pembelian' => 2023,
                'jumlah_baik' => 5,
                'jumlah_kurang_baik' => 1,
                'jumlah_rusak_berat' => 0,
                'keterangan' => 'Proyektor untuk presentasi',
            ],
            [
                'kode_barang' => 'BRG002',
                'id_kategori' => 2, // Audio
                'nama_barang' => 'Mikrofon',
                'merk_model' => 'Shure SM58',
                'no_seri_pabrik' => 'SN789012',
                'ukuran_dimensi' => '20x5x5 cm',
                'bahan' => 'Logam',
                'tahun_pembelian' => 2022,
                'jumlah_baik' => 3,
                'jumlah_kurang_baik' => 0,
                'jumlah_rusak_berat' => 1,
                'keterangan' => 'Mikrofon wireless',
            ],
            [
                'kode_barang' => 'BRG003',
                'id_kategori' => 1, // Elektronik
                'nama_barang' => 'Kamera DSLR',
                'merk_model' => 'Canon EOS 80D',
                'no_seri_pabrik' => 'SN345678',
                'ukuran_dimensi' => '15x12x10 cm',
                'bahan' => 'Logam & Plastik',
                'tahun_pembelian' => 2023,
                'jumlah_baik' => 2,
                'jumlah_kurang_baik' => 1,
                'jumlah_rusak_berat' => 0,
                'keterangan' => 'Kamera untuk dokumentasi',
            ],
            [
                'kode_barang' => 'BRG004',
                'id_kategori' => 1, // Elektronik
                'nama_barang' => 'Laptop',
                'merk_model' => 'ASUS VivoBook',
                'no_seri_pabrik' => 'SN901234',
                'ukuran_dimensi' => '35x25x2 cm',
                'bahan' => 'Logam & Plastik',
                'tahun_pembelian' => 2024,
                'jumlah_baik' => 10,
                'jumlah_kurang_baik' => 2,
                'jumlah_rusak_berat' => 1,
                'keterangan' => 'Laptop untuk lab komputer',
            ],
            [
                'kode_barang' => 'BRG005',
                'id_kategori' => 2, // Audio
                'nama_barang' => 'Speaker Aktif',
                'merk_model' => 'JBL EON610',
                'no_seri_pabrik' => 'SN567890',
                'ukuran_dimensi' => '50x30x30 cm',
                'bahan' => 'Kayu & Logam',
                'tahun_pembelian' => 2023,
                'jumlah_baik' => 4,
                'jumlah_kurang_baik' => 0,
                'jumlah_rusak_berat' => 0,
                'keterangan' => 'Speaker untuk acara',
            ],
            [
                'kode_barang' => 'BRG006',
                'id_kategori' => 3, // Aksesoris
                'nama_barang' => 'Tripod',
                'merk_model' => 'Manfrotto 055',
                'no_seri_pabrik' => 'SN234567',
                'ukuran_dimensi' => '150x20x20 cm',
                'bahan' => 'Aluminium',
                'tahun_pembelian' => 2022,
                'jumlah_baik' => 6,
                'jumlah_kurang_baik' => 1,
                'jumlah_rusak_berat' => 0,
                'keterangan' => 'Tripod kamera',
            ],
            [
                'kode_barang' => 'BRG007',
                'id_kategori' => 4, // Alat Tulis
                'nama_barang' => 'Whiteboard',
                'merk_model' => 'Standard',
                'no_seri_pabrik' => null,
                'ukuran_dimensi' => '120x80 cm',
                'bahan' => 'Besi & Kaca',
                'tahun_pembelian' => 2020,
                'jumlah_baik' => 0,
                'jumlah_kurang_baik' => 2,
                'jumlah_rusak_berat' => 3,
                'keterangan' => 'Whiteboard rusak berat',
            ],
            [
                'kode_barang' => 'BRG008',
                'id_kategori' => 1, // Elektronik
                'nama_barang' => 'Printer',
                'merk_model' => 'Epson L3210',
                'no_seri_pabrik' => 'SN678901',
                'ukuran_dimensi' => '40x30x20 cm',
                'bahan' => 'Plastik',
                'tahun_pembelian' => 2023,
                'jumlah_baik' => 3,
                'jumlah_kurang_baik' => 1,
                'jumlah_rusak_berat' => 0,
                'keterangan' => 'Printer inkjet',
            ],
            [
                'kode_barang' => 'BRG009',
                'id_kategori' => 1, // Elektronik
                'nama_barang' => 'Scanner',
                'merk_model' => 'Fujitsu ScanSnap',
                'no_seri_pabrik' => 'SN890123',
                'ukuran_dimensi' => '30x20x15 cm',
                'bahan' => 'Plastik',
                'tahun_pembelian' => 2022,
                'jumlah_baik' => 2,
                'jumlah_kurang_baik' => 0,
                'jumlah_rusak_berat' => 0,
                'keterangan' => 'Scanner dokumen',
            ],
            [
                'kode_barang' => 'BRG010',
                'id_kategori' => 2, // Audio
                'nama_barang' => 'Headset',
                'merk_model' => 'Logitech G Pro X',
                'no_seri_pabrik' => 'SN456789',
                'ukuran_dimensi' => '20x18x10 cm',
                'bahan' => 'Plastik & Karet',
                'tahun_pembelian' => 2023,
                'jumlah_baik' => 8,
                'jumlah_kurang_baik' => 2,
                'jumlah_rusak_berat' => 0,
                'keterangan' => 'Headset gaming',
            ],
            [
                'kode_barang' => 'BRG011',
                'id_kategori' => 1, // Elektronik
                'nama_barang' => 'Webcam',
                'merk_model' => 'Logitech C920',
                'no_seri_pabrik' => 'SN012345',
                'ukuran_dimensi' => '10x8x8 cm',
                'bahan' => 'Plastik',
                'tahun_pembelian' => 2023,
                'jumlah_baik' => 5,
                'jumlah_kurang_baik' => 1,
                'jumlah_rusak_berat' => 0,
                'keterangan' => 'Webcam HD',
            ],
            [
                'kode_barang' => 'BRG012',
                'id_kategori' => 5, // Jaringan
                'nama_barang' => 'Modem WiFi',
                'merk_model' => 'TP-Link Archer',
                'no_seri_pabrik' => 'SN135790',
                'ukuran_dimensi' => '20x15x5 cm',
                'bahan' => 'Plastik',
                'tahun_pembelian' => 2024,
                'jumlah_baik' => 4,
                'jumlah_kurang_baik' => 1,
                'jumlah_rusak_berat' => 0,
                'keterangan' => 'Modem WiFi router',
            ],
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }

        $this->command->info('Barang berhasil ditambahkan!');
    }
}
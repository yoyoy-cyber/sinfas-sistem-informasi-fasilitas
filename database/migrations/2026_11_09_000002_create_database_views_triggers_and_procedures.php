<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // -------------------------------------------------------------
        // 1. TABEL AUDIT LOG (Untuk Mencatat Aktivitas dari TRIGGER)
        // -------------------------------------------------------------
        DB::statement("
            CREATE TABLE IF NOT EXISTS log_aktivitas_peminjaman (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                id_peminjaman BIGINT UNSIGNED NOT NULL,
                kode_barang VARCHAR(50) NULL,
                username VARCHAR(50) NULL,
                status_lama VARCHAR(50) NULL,
                status_baru VARCHAR(50) NOT NULL,
                aksi VARCHAR(50) NOT NULL,
                catatan TEXT NULL,
                created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // -------------------------------------------------------------
        // 2. DATABASE VIEW: view_peminjaman_lengkap
        // -------------------------------------------------------------
        DB::statement("
            CREATE OR REPLACE VIEW view_peminjaman_lengkap AS
            SELECT 
                pr.id AS id_peminjaman,
                pr.kode_barang,
                COALESCE(b.nama_barang, pr.nama_barang) AS nama_barang,
                k.nama_kategori,
                pr.username,
                a.nama AS nama_akun,
                pr.nama_peminjam,
                pr.role_peminjam,
                pr.tanggal_pinjam,
                pr.tanggal_kembali,
                pr.status,
                pr.alasan_keperluan,
                pr.alasan_penolakan,
                pr.alasan_pembatalan,
                pr.created_at,
                pr.updated_at,
                CASE 
                    WHEN pr.status IN ('disetujui', 'dipinjam', 'sedang_dipinjam') AND pr.tanggal_kembali < CURDATE() THEN 'Terlambat'
                    WHEN pr.status IN ('disetujui', 'dipinjam', 'sedang_dipinjam') THEN 'Sedang Dipinjam'
                    WHEN pr.status = 'pending' THEN 'Menunggu Persetujuan'
                    WHEN pr.status = 'selesai' OR pr.status = 'dikembalikan' THEN 'Sudah Dikembalikan'
                    WHEN pr.status = 'ditolak' THEN 'Ditolak'
                    WHEN pr.status = 'dibatalkan' THEN 'Dibatalkan'
                    ELSE pr.status
                END AS status_deskripsi
            FROM peminjaman_requests pr
            LEFT JOIN barang b ON pr.kode_barang = b.kode_barang
            LEFT JOIN kategori k ON b.id_kategori = k.id_kategori
            LEFT JOIN akun a ON pr.username = a.username;
        ");

        // -------------------------------------------------------------
        // 3. DATABASE TRIGGERS
        // -------------------------------------------------------------
        // Trigger 1: Catat aktivitas saat peminjaman baru diajukan (AFTER INSERT)
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_insert_peminjaman;");
        DB::unprepared("
            CREATE TRIGGER trg_after_insert_peminjaman
            AFTER INSERT ON peminjaman_requests
            FOR EACH ROW
            BEGIN
                INSERT INTO log_aktivitas_peminjaman (
                    id_peminjaman, kode_barang, username, status_lama, status_baru, aksi, catatan, created_at
                ) VALUES (
                    NEW.id, 
                    NEW.kode_barang, 
                    NEW.username, 
                    NULL, 
                    NEW.status, 
                    'PENGAJUAN_BARU', 
                    CONCAT('Pengajuan peminjaman baru diajukan untuk barang ', COALESCE(NEW.nama_barang, NEW.kode_barang)), 
                    NOW()
                );
            END;
        ");

        // Trigger 2: Catat aktivitas saat status berubah (AFTER UPDATE)
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_update_peminjaman;");
        DB::unprepared("
            CREATE TRIGGER trg_after_update_peminjaman
            AFTER UPDATE ON peminjaman_requests
            FOR EACH ROW
            BEGIN
                IF OLD.status <> NEW.status THEN
                    INSERT INTO log_aktivitas_peminjaman (
                        id_peminjaman, kode_barang, username, status_lama, status_baru, aksi, catatan, created_at
                    ) VALUES (
                        NEW.id, 
                        NEW.kode_barang, 
                        NEW.username, 
                        OLD.status, 
                        NEW.status, 
                        CASE 
                            WHEN NEW.status = 'disetujui' THEN 'DISETUJUI'
                            WHEN NEW.status = 'ditolak' THEN 'DITOLAK'
                            WHEN NEW.status = 'dibatalkan' THEN 'DIBATALKAN'
                            WHEN NEW.status = 'dikembalikan' THEN 'DIKEMBALIKAN'
                            ELSE 'UPDATE_STATUS'
                        END,
                        CASE 
                            WHEN NEW.status = 'dibatalkan' THEN CONCAT('Peminjaman dibatalkan oleh peminjam. Alasan: ', COALESCE(NEW.alasan_pembatalan, '-'))
                            WHEN NEW.status = 'ditolak' THEN CONCAT('Peminjaman ditolak oleh admin. Alasan: ', COALESCE(NEW.alasan_penolakan, '-'))
                            WHEN NEW.status = 'disetujui' THEN 'Peminjaman disetujui oleh admin sarana.'
                            ELSE CONCAT('Status peminjaman diubah dari ', OLD.status, ' menjadi ', NEW.status)
                        END,
                        NOW()
                    );
                END IF;
            END;
        ");

        // -------------------------------------------------------------
        // 4. STORED FUNCTIONS
        // -------------------------------------------------------------
        // Function 1: Menghitung total peminjaman valid dari suatu barang
        DB::unprepared("DROP FUNCTION IF EXISTS func_total_dipinjam_barang;");
        DB::unprepared("
            CREATE FUNCTION func_total_dipinjam_barang(p_kode_barang VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci)
            RETURNS INT
            DETERMINISTIC
            READS SQL DATA
            BEGIN
                DECLARE total INT DEFAULT 0;
                SELECT COUNT(*) INTO total
                FROM peminjaman_requests
                WHERE kode_barang = p_kode_barang
                  AND status IN ('disetujui', 'dipinjam', 'sedang_dipinjam', 'selesai', 'dikembalikan');
                RETURN total;
            END;
        ");

        // Function 2: Mengembalikan status ketersediaan barang (Tersedia / Habis)
        DB::unprepared("DROP FUNCTION IF EXISTS func_status_ketersediaan_barang;");
        DB::unprepared("
            CREATE FUNCTION func_status_ketersediaan_barang(p_kode_barang VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci)
            RETURNS VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
            DETERMINISTIC
            READS SQL DATA
            BEGIN
                DECLARE v_jumlah_baik INT DEFAULT 0;
                DECLARE v_status VARCHAR(30) DEFAULT 'Tidak Diketahui';

                SELECT COALESCE(jumlah_baik, 0) INTO v_jumlah_baik
                FROM barang
                WHERE kode_barang = p_kode_barang
                LIMIT 1;

                IF v_jumlah_baik > 0 THEN
                    SET v_status = 'Tersedia';
                ELSE
                    SET v_status = 'Habis Dipinjam';
                END IF;

                RETURN v_status;
            END;
        ");

        // -------------------------------------------------------------
        // 5. STORED PROCEDURES
        // -------------------------------------------------------------
        // Procedure 1: Rekap ringkasan statistik status peminjaman
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_rekap_status_peminjaman;");
        DB::unprepared("
            CREATE PROCEDURE sp_rekap_status_peminjaman(
                OUT p_total INT,
                OUT p_pending INT,
                OUT p_disetujui INT,
                OUT p_dibatalkan INT,
                OUT p_ditolak INT
            )
            BEGIN
                SELECT COUNT(*) INTO p_total FROM peminjaman_requests;
                SELECT COUNT(*) INTO p_pending FROM peminjaman_requests WHERE status = 'pending';
                SELECT COUNT(*) INTO p_disetujui FROM peminjaman_requests WHERE status IN ('disetujui', 'dipinjam', 'sedang_dipinjam');
                SELECT COUNT(*) INTO p_dibatalkan FROM peminjaman_requests WHERE status = 'dibatalkan';
                SELECT COUNT(*) INTO p_ditolak FROM peminjaman_requests WHERE status = 'ditolak';
            END;
        ");

        // Procedure 2: Mengambil data laporan peminjaman per periode tanggal
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_laporan_peminjaman_per_periode;");
        DB::unprepared("
            CREATE PROCEDURE sp_laporan_peminjaman_per_periode(
                IN p_start_date DATE,
                IN p_end_date DATE
            )
            BEGIN
                SELECT 
                    pr.id,
                    pr.kode_barang,
                    COALESCE(b.nama_barang, pr.nama_barang) AS nama_barang,
                    pr.nama_peminjam,
                    pr.role_peminjam,
                    pr.tanggal_pinjam,
                    pr.tanggal_kembali,
                    pr.status,
                    pr.alasan_keperluan,
                    pr.alasan_pembatalan,
                    pr.created_at
                FROM peminjaman_requests pr
                LEFT JOIN barang b ON pr.kode_barang = b.kode_barang
                WHERE DATE(pr.tanggal_pinjam) BETWEEN p_start_date AND p_end_date
                ORDER BY pr.tanggal_pinjam DESC;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_laporan_peminjaman_per_periode;");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_rekap_status_peminjaman;");
        DB::unprepared("DROP FUNCTION IF EXISTS func_status_ketersediaan_barang;");
        DB::unprepared("DROP FUNCTION IF EXISTS func_total_dipinjam_barang;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_update_peminjaman;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_insert_peminjaman;");
        DB::statement("DROP VIEW IF EXISTS view_peminjaman_lengkap;");
        Schema::dropIfExists('log_aktivitas_peminjaman');
    }
};

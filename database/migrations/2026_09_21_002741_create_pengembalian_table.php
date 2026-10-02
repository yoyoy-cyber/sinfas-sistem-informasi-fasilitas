<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('pengembalian');
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peminjaman_id');
            $table->string('kode_barang');
            $table->string('nama_barang');
            $table->date('tanggal_pengembalian');
            $table->enum('kondisi_pengembalian', ['baik', 'rusak_ringan', 'rusak_berat']);
            $table->text('catatan_kondisi')->nullable();
            $table->string('bukti_foto')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('peminjaman_id')->references('id')->on('peminjaman_requests')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
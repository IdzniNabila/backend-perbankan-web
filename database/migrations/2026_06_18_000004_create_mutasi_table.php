<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mutasi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('rekening_id');
            $table->uuid('referensi_transaksi_id')->nullable();
            $table->enum('jenis_transaksi', ['DEBIT', 'CREDIT'])->index();
            $table->string('keterangan');
            $table->decimal('nominal', 18, 2);
            $table->decimal('saldo_sebelum', 18, 2);
            $table->decimal('saldo_setelah', 18, 2);
            $table->string('referensi_eksternal')->nullable()->unique();
            $table->uuid('diinisiasi_oleh')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('device_info')->nullable();
            $table->enum('status_proses', ['pending', 'berhasil', 'gagal'])->default('berhasil');
            $table->text('alasan_gagal')->nullable();
            $table->timestamp('waktu_transaksi')->useCurrent();
            $table->timestamps();

            $table->foreign('rekening_id')->references('id')->on('rekening')->onDelete('cascade');
            $table->foreign('diinisiasi_oleh')->references('id')->on('pengguna')->onDelete('set null');
            $table->index('rekening_id');
            $table->index('jenis_transaksi');
            $table->index('waktu_transaksi');
            $table->index('referensi_transaksi_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutasi');
    }
};

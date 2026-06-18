<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekening', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('nasabah_id');
            $table->string('nomor_rekening')->unique();
            $table->enum('jenis_rekening', ['tabungan', 'giro', 'deposito'])->default('tabungan');
            $table->decimal('saldo', 18, 2)->default(0);
            $table->decimal('saldo_minimum', 18, 2)->default(0);
            $table->string('mata_uang', 3)->default('IDR');
            $table->text('keterangan')->nullable();
            $table->enum('status', ['aktif', 'nonaktif', 'terblokir'])->default('aktif');
            $table->timestamp('tanggal_buka')->nullable();
            $table->timestamp('tanggal_tutup')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('nasabah_id')->references('id')->on('nasabah')->onDelete('cascade');
            $table->index('status');
            $table->index('tanggal_buka');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekening');
    }
};

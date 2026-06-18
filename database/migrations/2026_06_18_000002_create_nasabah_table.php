<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nasabah', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->unique();
            $table->string('nama_nasabah');
            $table->string('nomor_identitas')->unique();
            $table->enum('jenis_identitas', ['KTP', 'NIM', 'NIDN', 'NPK'])->default('NIM');
            $table->string('nomor_telepon')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->string('kota')->nullable();
            $table->string('kodepos')->nullable();
            $table->enum('status_kepemilikan', ['individu', 'bersama', 'perusahaan'])->default('individu');
            $table->date('tanggal_daftar')->nullable();
            $table->enum('status', ['aktif', 'nonaktif', 'suspended'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('pengguna')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nasabah');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengguna', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('pin_hash');
            $table->string('email')->nullable()->unique();
            $table->string('nama_lengkap')->nullable();
            $table->string('nomor_identitas')->nullable()->unique();
            $table->enum('jenis_identitas', ['KTP', 'NIM', 'NIDN', 'NPK'])->default('NIM');
            $table->string('nomor_telepon')->nullable();
            $table->text('alamat')->nullable();
            $table->enum('status', ['aktif', 'nonaktif', 'suspended'])->default('aktif');
            $table->timestamp('terakhir_login')->nullable();
            $table->string('ip_terakhir_login')->nullable();
            $table->string('device_terakhir_login')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};

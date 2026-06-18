<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('device_name');
            $table->string('user_agent')->nullable();
            $table->string('ip_address');
            $table->enum('device_type', ['web', 'mobile', 'tablet', 'desktop'])->default('web');
            $table->enum('status', ['aktif', 'dikhususkan', 'dicurigai'])->default('aktif');
            $table->timestamp('login_pertama_kali')->nullable();
            $table->timestamp('aktivitas_terakhir')->nullable();
            $table->timestamp('logout_time')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('pengguna')->onDelete('cascade');
            $table->index('user_id');
            $table->index('status');
            $table->index('ip_address');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('password_reset_tokens');
        // FIX: jangan hapus 'personal_access_tokens' — tabel ini dipakai Laravel Sanctum
        // untuk menyimpan token login (HasApiTokens::createToken()). Jika tabel ini
        // dihapus, proses login akan selalu gagal dengan error "table doesn't exist".
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');
    }

    public function down(): void
    {
        // Cleanup migration is not easily reversible because these tables were only
        // created for Laravel default scaffolding and are not part of the API.
    }
};
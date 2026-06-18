<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old tables yang sudah diganti dengan struktur baru
        Schema::dropIfExists('api_users');
    }

    public function down(): void
    {
        // Untuk rollback, tidak perlu recreate karena schema sudah ada di migration lama
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->string('action');
            $table->string('model_name')->nullable();
            $table->uuid('model_id')->nullable();
            $table->text('perubahan')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->enum('tipe_event', [
                'LOGIN',
                'LOGOUT',
                'TRANSFER',
                'DEPOSIT',
                'WITHDRAWAL',
                'ACCOUNT_CREATE',
                'ACCOUNT_MODIFY',
                'PASSWORD_CHANGE',
                'PIN_CHANGE',
                'DEVICE_LOGIN',
                'SUSPICIOUS_ACTIVITY',
                'OTHER'
            ])->default('OTHER')->index();
            $table->text('deskripsi')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('pengguna')->onDelete('set null');
            $table->index('user_id');
            $table->index('tipe_event');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // Tambahkan namespace Schema

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Mencegah error Index Database (terutama di MySQL/MariaDB versi lama)
        // saat menjalankan migration atau membuat tabel unik
        Schema::defaultStringLength(191);
    }
}
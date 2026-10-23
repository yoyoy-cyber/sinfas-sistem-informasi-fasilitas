<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon; // ← Baris ini ditambahkan

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Deteksi jika public_html digunakan (DirectAdmin / cPanel hosting)
        if (is_dir(base_path('public_html'))) {
            $this->app->usePublicPath(base_path('public_html'));
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 2 Baris ini ditambahkan agar tanggal menjadi Bahasa Indonesia
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID');
    }
}
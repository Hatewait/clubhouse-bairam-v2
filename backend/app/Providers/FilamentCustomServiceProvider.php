<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentAsset;

class FilamentCustomServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Подключаем кастомные стили для Filament
        FilamentAsset::register([
            \Filament\Support\Assets\Css::make('filament-custom', resource_path('css/filament-custom.css')),
        ]);
    }
}

<?php
// CreateOption.php
namespace App\Filament\Resources\OptionResource\Pages;

use App\Filament\Resources\OptionResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Cache;

class CreateOption extends CreateRecord
{
    protected static string $resource = OptionResource::class;
    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function afterCreate(): void
    {
        // Очищаем кеш фронтенда при создании опции
        Cache::forget('frontend_options');
    }
}
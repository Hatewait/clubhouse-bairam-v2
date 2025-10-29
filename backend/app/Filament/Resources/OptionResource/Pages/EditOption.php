<?php
// EditOption.php
namespace App\Filament\Resources\OptionResource\Pages;

use App\Filament\Resources\OptionResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;

class EditOption extends EditRecord
{
    protected static string $resource = OptionResource::class;
    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            // по умолчанию только «Сохранить», удаление можно добавить при необходимости
        ];
    }

    protected function afterSave(): void
    {
        // Очищаем кеш фронтенда при обновлении опции
        Cache::forget('frontend_options');
    }

    protected function afterDelete(): void
    {
        // Очищаем кеш фронтенда при удалении опции
        Cache::forget('frontend_options');
    }
}
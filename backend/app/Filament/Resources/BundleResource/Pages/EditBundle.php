<?php

namespace App\Filament\Resources\BundleResource\Pages;

use App\Filament\Resources\BundleResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;

class EditBundle extends EditRecord
{
    protected static string $resource = BundleResource::class;
    
    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function afterSave(): void
    {
        // Очищаем кеш фронтенда при сохранении изменений
        Cache::forget('frontend_bundles');
        Cache::forget('frontend_bundles_updated_at');
    }
}
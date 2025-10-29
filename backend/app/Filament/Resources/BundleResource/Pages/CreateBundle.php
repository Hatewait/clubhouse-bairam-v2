<?php

namespace App\Filament\Resources\BundleResource\Pages;

use App\Filament\Resources\BundleResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Cache;
use Filament\Notifications\Notification;
use Filament\Actions;

class CreateBundle extends CreateRecord
{
    protected static string $resource = BundleResource::class;
    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function beforeCreate(): void
    {
        // Проверяем лимит форматов отдыха
        if (\App\Models\Bundle::count() >= 2) {
            Notification::make()
                ->title('Превышен лимит')
                ->body('Максимальное количество форматов отдыха: 2')
                ->danger()
                ->send();
            
            $this->halt();
        }
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function afterCreate(): void
    {
        // Очищаем кеш фронтенда при создании бандла
        Cache::forget('frontend_bundles');
        Cache::forget('frontend_services');
    }
}
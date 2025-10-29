<?php

namespace App\Filament\Resources\BundleResource\Pages;

use App\Filament\Resources\BundleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBundles extends ListRecords
{
    protected static string $resource = BundleResource::class;
    
    public function getTitle(): string
    {
        return 'Форматы отдыха';
    }
    
    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        // Оставляем единственную «верхнюю правую» кнопку создания
        return [
            Actions\CreateAction::make()
                ->label('Добавить формат отдыха')
                ->hidden(fn () => \App\Models\Bundle::count() >= 2),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }
}
<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use Filament\Resources\Pages\EditRecord;

class EditService extends EditRecord
{
    protected static string $resource = ServiceResource::class;
    
    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Изменения сохранены';
    }
}
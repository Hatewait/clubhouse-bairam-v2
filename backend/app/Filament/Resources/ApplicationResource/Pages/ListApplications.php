<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListApplications extends ListRecords
{
    protected static string $resource = ApplicationResource::class;
    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Создать заявку'),
        ];
    }

    public function getTitle(): string
    {
        return 'Заявки';
    }

}
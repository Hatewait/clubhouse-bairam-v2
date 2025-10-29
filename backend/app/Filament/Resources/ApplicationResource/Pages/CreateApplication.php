<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateApplication extends CreateRecord
{
    protected static string $resource = ApplicationResource::class;
    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()->title('Заявка создана')->success();
    }

    public function getTitle(): string
    {
        return 'Новая заявка';
    }
}
<?php

namespace App\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

abstract class BaseCreateRecord extends CreateRecord
{
    /**
     * Единое русское уведомление после создания записи.
     * Убирает дубли и приводит текст к единому стилю.
     */
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Запись создана')
            ->success();
    }
}
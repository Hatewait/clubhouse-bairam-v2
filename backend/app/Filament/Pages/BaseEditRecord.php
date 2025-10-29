<?php

namespace App\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

abstract class BaseEditRecord extends EditRecord
{
    /**
     * Единое русское уведомление после сохранения записи.
     * Убирает дубли и приводит текст к единому стилю.
     */
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Изменения сохранены')
            ->success();
    }
}
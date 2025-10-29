<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Resources\Pages\EditRecord;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;
    public function getBreadcrumbs(): array
    {
        return [];
    }

    // Локализация заголовка страницы
    protected static ?string $title = 'Редактировать клиента';

    // После успешного сохранения — возврат к списку
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            // Зелёная кнопка "Сохранить и выйти"
            $this->getSaveFormAction()
                ->label('Сохранить и выйти')
                ->color('success')
                ->keyBindings(['mod+s']),

            // Красная кнопка "Закрыть" (без сохранения — уходим в список)
            $this->getCancelFormAction()
                ->label('Закрыть')
                ->color('danger'),
        ];
    }
}
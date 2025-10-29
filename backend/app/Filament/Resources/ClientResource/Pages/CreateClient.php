<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;
    public function getBreadcrumbs(): array
    {
        return [];
    }

    // Локализация заголовка страницы
    protected static ?string $title = 'Создать клиента';

    // В твоей версии Filament это свойство статическое — оставляем статическим
    protected static bool $canCreateAnother = false;

    // После успешного сохранения переходим к списку
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            // Зелёная кнопка "Сохранить и выйти"
            $this->getCreateFormAction()
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
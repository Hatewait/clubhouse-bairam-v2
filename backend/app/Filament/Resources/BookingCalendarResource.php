<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingCalendarResource\Pages;
use Filament\Resources\Resource;

class BookingCalendarResource extends Resource
{
    protected static ?string $model = null; // Не используем модель для календаря

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Календарь бронирований';
    protected static ?string $modelLabel = 'Календарь бронирований';
    protected static ?string $pluralModelLabel = 'Календарь бронирований';
    protected static ?int $navigationSort = 70;

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookingCalendars::route('/'),
        ];
    }
}

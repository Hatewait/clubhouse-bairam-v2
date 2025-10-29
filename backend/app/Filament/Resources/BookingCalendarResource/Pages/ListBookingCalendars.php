<?php

namespace App\Filament\Resources\BookingCalendarResource\Pages;

use App\Filament\Resources\BookingCalendarResource;
use App\Services\CalendarService;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ListBookingCalendars extends Page
{
    protected static string $resource = BookingCalendarResource::class;
    protected static string $view = 'filament.resources.booking-calendar-resource.pages.list-booking-calendars';

    public function getTitle(): string | Htmlable
    {
        return 'Календарь бронирований';
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function getViewData(): array
    {
        $calendarService = app(CalendarService::class);
        $currentYear = now()->year;
        
        // Получаем данные для всех 12 месяцев года
        $yearData = [];
        for ($month = 1; $month <= 12; $month++) {
            $yearData[$month] = $calendarService->getMonth($currentYear, $month);
        }
        
        return [
            'yearData' => $yearData,
            'currentYear' => $currentYear,
            'monthNames' => [
                1 => 'Январь', 2 => 'Февраль', 3 => 'Март', 4 => 'Апрель',
                5 => 'Май', 6 => 'Июнь', 7 => 'Июль', 8 => 'Август',
                9 => 'Сентябрь', 10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь'
            ]
        ];
    }
}

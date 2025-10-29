<?php

namespace App\Services;

use App\Models\Application;
use App\Models\BlockedDate;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CalendarService
{
    /**
     * Получить статус дат в диапазоне
     */
    public function getDatesInRange(string $startDate, string $endDate): array
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        $available = [];
        $booked = [];
        $blocked = [];
        
        // Получаем заблокированные даты
        $blockedDates = BlockedDate::whereBetween('date', [$start, $end])
            ->pluck('date')
            ->map(fn($date) => $date->format('Y-m-d'))
            ->toArray();
        
        // Получаем занятые даты из заявок и заказов
        $bookedDates = $this->getBookedDates($start, $end);
        
        // Генерируем все даты в диапазоне
        $current = $start->copy();
        while ($current->lte($end)) {
            $dateStr = $current->format('Y-m-d');
            
            if (in_array($dateStr, $blockedDates)) {
                $blocked[] = $dateStr;
            } elseif (in_array($dateStr, $bookedDates)) {
                $booked[] = $dateStr;
            } else {
                $available[] = $dateStr;
            }
            
            $current->addDay();
        }
        
        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'available' => $available,
            'booked' => $booked,
            'blocked' => $blocked,
            'total_available' => count($available),
            'total_booked' => count($booked),
            'total_blocked' => count($blocked),
        ];
    }
    
    /**
     * Получить статус дат для всего года
     */
    public function getYear(int $year): array
    {
        $months = [];
        $summary = ['total_available' => 0, 'total_booked' => 0, 'total_blocked' => 0];
        
        for ($month = 1; $month <= 12; $month++) {
            $monthData = $this->getMonth($year, $month);
            $months[$month] = $monthData;
            
            $summary['total_available'] += $monthData['total_available'];
            $summary['total_booked'] += $monthData['total_booked'];
            $summary['total_blocked'] += $monthData['total_blocked'];
        }
        
        return [
            'year' => $year,
            'months' => $months,
            'summary' => $summary,
        ];
    }

    /**
     * Получить статус дат для месяца
     */
    public function getMonth(int $year, int $month): array
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();
        
        $data = $this->getDatesInRange($start->format('Y-m-d'), $end->format('Y-m-d'));
        
        $monthNames = [
            1 => 'январь', 2 => 'февраль', 3 => 'март', 4 => 'апрель',
            5 => 'май', 6 => 'июнь', 7 => 'июль', 8 => 'август',
            9 => 'сентябрь', 10 => 'октябрь', 11 => 'ноябрь', 12 => 'декабрь'
        ];
        
        // Создаем календарную сетку
        $days = [];
        $firstDayOfWeek = $start->dayOfWeek; // 0 = воскресенье, 1 = понедельник
        $daysInMonth = $start->daysInMonth;
        $today = now()->format('Y-m-d');
        
        // Добавляем дни предыдущего месяца
        $prevMonth = $start->copy()->subMonth();
        $daysInPrevMonth = $prevMonth->daysInMonth;
        $startDay = $firstDayOfWeek === 0 ? 6 : $firstDayOfWeek - 1; // Конвертируем в понедельник = 0
        
        for ($i = $startDay - 1; $i >= 0; $i--) {
            $day = $daysInPrevMonth - $i;
            $date = $prevMonth->copy()->day($day)->format('Y-m-d');
            $days[] = [
                'day' => $day,
                'date' => $date,
                'isCurrentMonth' => false,
                'isBooked' => in_array($date, $data['booked']),
                'isBlocked' => in_array($date, $data['blocked']),
                'isToday' => $date === $today,
            ];
        }
        
        // Добавляем дни текущего месяца
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = $start->copy()->day($day)->format('Y-m-d');
            $days[] = [
                'day' => $day,
                'date' => $date,
                'isCurrentMonth' => true,
                'isBooked' => in_array($date, $data['booked']),
                'isBlocked' => in_array($date, $data['blocked']),
                'isToday' => $date === $today,
            ];
        }
        
        // Добавляем дни следующего месяца
        $nextMonth = $start->copy()->addMonth();
        $remainingDays = 42 - count($days); // 6 недель * 7 дней = 42
        for ($day = 1; $day <= $remainingDays; $day++) {
            $date = $nextMonth->copy()->day($day)->format('Y-m-d');
            $days[] = [
                'day' => $day,
                'date' => $date,
                'isCurrentMonth' => false,
                'isBooked' => in_array($date, $data['booked']),
                'isBlocked' => in_array($date, $data['blocked']),
                'isToday' => $date === $today,
            ];
        }
        
        // Разбиваем на недели
        $weeks = array_chunk($days, 7);
        
        return [
            'year' => $year,
            'month' => $month,
            'month_name' => $monthNames[$month] ?? '',
            'days' => $weeks,
            'total_available' => $data['total_available'],
            'total_booked' => $data['total_booked'],
            'total_blocked' => $data['total_blocked'],
        ];
    }
    
    /**
     * Проверить доступность конкретной даты
     */
    public function checkDate(string $date): array
    {
        $checkDate = Carbon::parse($date);
        
        // Проверяем заблокированные даты
        $blocked = BlockedDate::where('date', $checkDate)->first();
        if ($blocked) {
            return [
                'date' => $date,
                'available' => false,
                'details' => [
                    'date' => $date,
                    'status' => 'blocked',
                    'reason' => $blocked->reason,
                    'blocked_by' => $blocked->user?->name ?? 'Администратор',
                    'blocked_at' => $blocked->created_at->toISOString(),
                ]
            ];
        }
        
        // Проверяем занятые даты
        $bookedData = $this->getBookedDatesForDate($checkDate);
        if (!empty($bookedData)) {
            return [
                'date' => $date,
                'available' => false,
                'details' => [
                    'date' => $date,
                    'status' => 'booked',
                    'orders' => $bookedData
                ]
            ];
        }
        
        return [
            'date' => $date,
            'available' => true,
            'details' => [
                'date' => $date,
                'status' => 'available'
            ]
        ];
    }
    
    /**
     * Получить статистику по месяцам года
     */
    public function getStats(?int $year = null): array
    {
        $year = $year ?? now()->year;
        $months = [];
        $summary = ['total_available' => 0, 'total_booked' => 0, 'total_blocked' => 0];
        
        for ($month = 1; $month <= 12; $month++) {
            $monthData = $this->getMonth($year, $month);
            $months[] = [
                'year' => $year,
                'month' => $month,
                'month_name' => $monthData['month_name'],
                'available_days' => $monthData['total_available'],
                'booked_days' => $monthData['total_booked'],
                'blocked_days' => $monthData['total_blocked'],
                'total_days' => $monthData['total_available'] + $monthData['total_booked'] + $monthData['total_blocked'],
            ];
            
            $summary['total_available'] += $monthData['total_available'];
            $summary['total_booked'] += $monthData['total_booked'];
            $summary['total_blocked'] += $monthData['total_blocked'];
        }
        
        return [
            'year' => $year,
            'months' => $months,
            'summary' => $summary,
        ];
    }
    
    /**
     * Получить ближайшие доступные даты
     */
    public function getNextAvailable(int $days = 7, ?string $fromDate = null): array
    {
        $from = $fromDate ? Carbon::parse($fromDate) : now();
        $end = $from->copy()->addDays($days * 2); // Ищем в два раза больше дней
        
        $available = [];
        $current = $from->copy();
        
        while ($current->lte($end) && count($available) < $days) {
            $check = $this->checkDate($current->format('Y-m-d'));
            if ($check['available']) {
                $available[] = $current->format('Y-m-d');
            }
            $current->addDay();
        }
        
        return [
            'available_dates' => $available,
            'total_found' => count($available),
            'requested' => $days,
        ];
    }
    
    /**
     * Получить события для FullCalendar
     */
    public function getEvents(string $start, string $end): array
    {
        $startDate = Carbon::parse($start);
        $endDate = Carbon::parse($end);
        
        $events = [];
        
        // Заблокированные даты
        $blockedDates = BlockedDate::whereBetween('date', [$startDate, $endDate])
            ->with('user')
            ->get();
            
        foreach ($blockedDates as $blocked) {
            $events[] = [
                'id' => 'blocked_' . $blocked->id,
                'title' => 'Заблокировано',
                'start' => $blocked->date->format('Y-m-d'),
                'end' => $blocked->date->format('Y-m-d'),
                'status' => 'blocked',
                'extendedProps' => [
                    'status' => 'blocked',
                    'reason' => $blocked->reason,
                    'blocked_by' => $blocked->user?->name ?? 'Администратор',
                ],
                'backgroundColor' => '#6b7280',
                'borderColor' => '#6b7280',
            ];
        }
        
        // Занятые даты
        $bookedDates = $this->getBookedDatesWithDetails($startDate, $endDate);
        foreach ($bookedDates as $date => $bookings) {
            $events[] = [
                'id' => 'booked_' . $date,
                'title' => 'Занято (' . count($bookings) . ')',
                'start' => $date,
                'end' => $date,
                'status' => 'booked',
                'extendedProps' => [
                    'status' => 'booked',
                    'bookings' => $bookings,
                ],
                'backgroundColor' => '#dc2626',
                'borderColor' => '#dc2626',
            ];
        }
        
        return $events;
    }
    
    /**
     * Заблокировать дату
     */
    public function blockDate(string $date, ?string $reason = null, ?int $userId = null): bool
    {
        try {
            BlockedDate::create([
                'date' => $date,
                'reason' => $reason,
                'blocked_by' => $userId,
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Разблокировать дату
     */
    public function unblockDate(string $date): bool
    {
        try {
            BlockedDate::where('date', $date)->delete();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Получить занятые даты в диапазоне
     */
    private function getBookedDates(Carbon $start, Carbon $end): array
    {
        $bookedDates = [];
        
        // Получаем даты из заявок со статусом "оплачена" или "завершена"
        $applications = Application::whereIn('status', [Application::STATUS_PAID, Application::STATUS_COMPLETED])
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('booking_date', [$start, $end])
                      ->orWhereBetween('booking_end_date', [$start, $end])
                      ->orWhere(function ($q) use ($start, $end) {
                          $q->where('booking_date', '<=', $start)
                            ->where('booking_end_date', '>=', $end);
                      });
            })
            ->get(['booking_date', 'booking_end_date']);
        
        foreach ($applications as $app) {
            $current = Carbon::parse($app->booking_date);
            $endDate = Carbon::parse($app->booking_end_date);
            
            while ($current->lte($endDate)) {
                $bookedDates[] = $current->format('Y-m-d');
                $current->addDay();
            }
        }
        
        // Получаем даты из активных заказов
        $orders = Order::where('status', Order::STATUS_ACTIVE)
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('booking_date', [$start, $end])
                      ->orWhereBetween('booking_end_date', [$start, $end])
                      ->orWhere(function ($q) use ($start, $end) {
                          $q->where('booking_date', '<=', $start)
                            ->where('booking_end_date', '>=', $end);
                      });
            })
            ->get(['booking_date', 'booking_end_date']);
        
        foreach ($orders as $order) {
            $current = Carbon::parse($order->booking_date);
            $endDate = Carbon::parse($order->booking_end_date);
            
            while ($current->lte($endDate)) {
                $bookedDates[] = $current->format('Y-m-d');
                $current->addDay();
            }
        }
        
        return array_unique($bookedDates);
    }
    
    /**
     * Получить заблокированные даты для DatePicker (в формате Y-m-d)
     * Блокирует даты, которые при выборе создадут пересечение с уже забронированными диапазонами
     */
    public function getBlockedDatesForDatePicker(?string $selectedStartDate = null, ?string $selectedEndDate = null, ?int $excludeApplicationId = null): array
    {
        $blockedDates = [];
        
        // Получаем заблокированные даты из BlockedDate
        $blocked = BlockedDate::pluck('date')
            ->map(fn($date) => $date->format('Y-m-d'))
            ->toArray();
        $blockedDates = array_merge($blockedDates, $blocked);
        
        // Получаем все забронированные диапазоны
        $bookedRanges = $this->getAllBookedRanges($excludeApplicationId);
        
        // Если выбрана начальная дата, блокируем даты, которые создадут пересечение
        if ($selectedStartDate) {
            $start = Carbon::parse($selectedStartDate);
            
            foreach ($bookedRanges as $range) {
                $rangeStart = Carbon::parse($range['start_date']);
                $rangeEnd = Carbon::parse($range['end_date']);
                
                // Блокируем даты от начала забронированного диапазона до конца
                // плюс минимальный период (2 дня) для предотвращения пересечений
                $blockStart = $rangeStart->copy()->subDays(1);
                $blockEnd = $rangeEnd->copy()->addDays(1);
                
                $current = $blockStart;
                while ($current->lte($blockEnd)) {
                    $blockedDates[] = $current->format('Y-m-d');
                    $current->addDay();
                }
            }
        } else {
            // Если начальная дата не выбрана, блокируем все даты в забронированных диапазонах
            foreach ($bookedRanges as $range) {
                $current = Carbon::parse($range['start_date']);
                $endDate = Carbon::parse($range['end_date']);
                
                while ($current->lte($endDate)) {
                    $blockedDates[] = $current->format('Y-m-d');
                    $current->addDay();
                }
            }
        }
        
        return array_unique($blockedDates);
    }
    
    /**
     * Получить заблокированные даты для второй даты (дата по) с учетом умной логики
     * Учитывает минимальные/максимальные ограничения и пересечения с забронированными диапазонами
     */
    public function getBlockedDatesForEndDatePicker(string $selectedStartDate, ?int $excludeApplicationId = null): array
    {
        $blockedDates = [];
        $start = Carbon::parse($selectedStartDate);
        
        // Получаем заблокированные даты из BlockedDate
        $blocked = BlockedDate::pluck('date')
            ->map(fn($date) => $date->format('Y-m-d'))
            ->toArray();
        $blockedDates = array_merge($blockedDates, $blocked);
        
        // Получаем все забронированные диапазоны
        $bookedRanges = $this->getAllBookedRanges($excludeApplicationId);
        
        // Блокируем даты, которые нарушают минимальные/максимальные ограничения
        $minDays = 2; // минимум 2 дня
        $maxDays = 10; // максимум 10 дней
        
        // Блокируем даты, которые дают меньше 2 дней (НЕ включая саму начальную дату)
        $current = $start->copy()->addDay(); // начинаем со следующего дня
        $minEndDate = $start->copy()->addDays($minDays - 1);
        while ($current->lte($minEndDate)) {
            $blockedDates[] = $current->format('Y-m-d');
            $current->addDay();
        }
        
        // Блокируем даты, которые дают больше 10 дней
        $maxEndDate = $start->copy()->addDays($maxDays - 1);
        $current = $maxEndDate->copy()->addDay();
        $limitDate = $current->copy()->addDays(30); // ограничиваем поиск на 30 дней вперед
        
        while ($current->lte($limitDate)) {
            $blockedDates[] = $current->format('Y-m-d');
            $current->addDay();
        }
        
        // Блокируем даты, которые создадут пересечение с забронированными диапазонами
        foreach ($bookedRanges as $range) {
            $rangeStart = Carbon::parse($range['start_date']);
            $rangeEnd = Carbon::parse($range['end_date']);
            
            // Блокируем весь забронированный диапазон
            $current = $rangeStart->copy();
            while ($current->lte($rangeEnd)) {
                $blockedDates[] = $current->format('Y-m-d');
                $current->addDay();
            }
            
            // Дополнительная логика для предотвращения пересечений:
            // Если выбранная начальная дата находится до забронированного диапазона,
            // блокируем даты после конца диапазона, которые создадут пересечение
            if ($start->lt($rangeStart)) {
                // Проверяем, может ли конечная дата попасть в забронированный диапазон
                // Если да, то блокируем все даты после конца диапазона
                $potentialEndDate = $start->copy()->addDays($maxDays - 1);
                if ($potentialEndDate->gte($rangeStart)) {
                    $current = $rangeEnd->copy()->addDay();
                    $limitDate = $current->copy()->addDays(30);
                    
                    while ($current->lte($limitDate)) {
                        $blockedDates[] = $current->format('Y-m-d');
                        $current->addDay();
                    }
                }
            }
        }
        
        return array_unique($blockedDates);
    }
    
    /**
     * Получить все забронированные диапазоны
     */
    private function getAllBookedRanges(?int $excludeApplicationId = null): array
    {
        $ranges = [];
        
        // Получаем диапазоны из заявок
        $applications = Application::whereIn('status', [Application::STATUS_PAID, Application::STATUS_COMPLETED])
            ->when($excludeApplicationId, fn($q) => $q->where('id', '!=', $excludeApplicationId))
            ->get(['booking_date', 'booking_end_date']);
        
        foreach ($applications as $app) {
            $ranges[] = [
                'start_date' => $app->booking_date->format('Y-m-d'),
                'end_date' => $app->booking_end_date->format('Y-m-d'),
                'type' => 'application',
                'id' => $app->id,
            ];
        }
        
        // Получаем диапазоны из активных заказов
        $orders = Order::where('status', Order::STATUS_ACTIVE)
            ->get(['booking_date', 'booking_end_date', 'id']);
        
        foreach ($orders as $order) {
            $ranges[] = [
                'start_date' => $order->booking_date->format('Y-m-d'),
                'end_date' => $order->booking_end_date->format('Y-m-d'),
                'type' => 'order',
                'id' => $order->id,
            ];
        }
        
        return $ranges;
    }
    
    /**
     * Проверить, пересекается ли диапазон дат с уже забронированными
     */
    public function checkDateRangeConflict(string $startDate, string $endDate, ?int $excludeApplicationId = null): array
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        $conflicts = [];
        
        // Проверяем заблокированные даты
        $blockedDates = BlockedDate::whereBetween('date', [$start, $end])
            ->get();
        
        foreach ($blockedDates as $blocked) {
            $conflicts[] = [
                'type' => 'blocked',
                'date' => $blocked->date->format('Y-m-d'),
                'reason' => $blocked->reason,
                'blocked_by' => $blocked->user?->name ?? 'Администратор',
            ];
        }
        
        // Проверяем пересечения с заявками
        $applications = Application::whereIn('status', [Application::STATUS_PAID, Application::STATUS_COMPLETED])
            ->when($excludeApplicationId, fn($q) => $q->where('id', '!=', $excludeApplicationId))
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    // Новый диапазон начинается внутри существующего
                    $q->where('booking_date', '<=', $start)
                      ->where('booking_end_date', '>=', $start);
                })->orWhere(function ($q) use ($start, $end) {
                    // Новый диапазон заканчивается внутри существующего
                    $q->where('booking_date', '<=', $end)
                      ->where('booking_end_date', '>=', $end);
                })->orWhere(function ($q) use ($start, $end) {
                    // Новый диапазон полностью содержит существующий
                    $q->where('booking_date', '>=', $start)
                      ->where('booking_end_date', '<=', $end);
                })->orWhere(function ($q) use ($start, $end) {
                    // Существующий диапазон полностью содержит новый
                    $q->where('booking_date', '<=', $start)
                      ->where('booking_end_date', '>=', $end);
                });
            })
            ->with('client')
            ->get();
        
        foreach ($applications as $app) {
            $conflicts[] = [
                'type' => 'application',
                'id' => $app->id,
                'start_date' => $app->booking_date->format('Y-m-d'),
                'end_date' => $app->booking_end_date->format('Y-m-d'),
                'client_name' => $app->client->name ?? 'Не указано',
                'status' => Application::STATUSES[$app->status] ?? $app->status,
            ];
        }
        
        // Проверяем пересечения с активными заказами
        $orders = Order::where('status', Order::STATUS_ACTIVE)
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    // Новый диапазон начинается внутри существующего
                    $q->where('booking_date', '<=', $start)
                      ->where('booking_end_date', '>=', $start);
                })->orWhere(function ($q) use ($start, $end) {
                    // Новый диапазон заканчивается внутри существующего
                    $q->where('booking_date', '<=', $end)
                      ->where('booking_end_date', '>=', $end);
                })->orWhere(function ($q) use ($start, $end) {
                    // Новый диапазон полностью содержит существующий
                    $q->where('booking_date', '>=', $start)
                      ->where('booking_end_date', '<=', $end);
                })->orWhere(function ($q) use ($start, $end) {
                    // Существующий диапазон полностью содержит новый
                    $q->where('booking_date', '<=', $start)
                      ->where('booking_end_date', '>=', $end);
                });
            })
            ->get();
        
        foreach ($orders as $order) {
            $conflicts[] = [
                'type' => 'order',
                'id' => $order->id,
                'number' => $order->number,
                'start_date' => $order->booking_date->format('Y-m-d'),
                'end_date' => $order->booking_end_date->format('Y-m-d'),
                'client_name' => $order->client_name_snapshot ?? 'Не указано',
                'status' => Order::STATUSES[$order->status] ?? $order->status,
            ];
        }
        
        return [
            'has_conflicts' => !empty($conflicts),
            'conflicts' => $conflicts,
        ];
    }
    
    /**
     * Получить детали занятых дат для конкретной даты
     */
    private function getBookedDatesForDate(Carbon $date): array
    {
        $bookings = [];
        
        // Заявки
        $applications = Application::whereIn('status', [Application::STATUS_PAID, Application::STATUS_COMPLETED])
            ->where(function ($query) use ($date) {
                $query->where('booking_date', '<=', $date)
                      ->where('booking_end_date', '>=', $date);
            })
            ->with('client', 'bundle')
            ->get();
        
        foreach ($applications as $app) {
            $bookings[] = [
                'id' => $app->id,
                'number' => 'APP-' . $app->id,
                'client_name' => $app->client->name ?? 'Не указано',
                'client_phone' => $app->client->phone_pretty ?? 'Не указано',
                'people_count' => $app->people_count,
                'booking_date' => $app->booking_date->format('Y-m-d'),
                'booking_end_date' => $app->booking_end_date->format('Y-m-d'),
                'bundle_name' => $app->bundle->name ?? 'Не указано',
            ];
        }
        
        // Заказы
        $orders = Order::where('status', Order::STATUS_ACTIVE)
            ->where(function ($query) use ($date) {
                $query->where('booking_date', '<=', $date)
                      ->where('booking_end_date', '>=', $date);
            })
            ->get();
        
        foreach ($orders as $order) {
            $bookings[] = [
                'id' => $order->id,
                'number' => $order->number,
                'client_name' => $order->client_name_snapshot,
                'client_phone' => $order->client_phone_snapshot,
                'people_count' => $order->people_count,
                'booking_date' => $order->booking_date->format('Y-m-d'),
                'booking_end_date' => $order->booking_end_date->format('Y-m-d'),
                'bundle_name' => $order->bundle_name_snapshot ?? 'Не указано',
            ];
        }
        
        return $bookings;
    }
    
    /**
     * Получить занятые даты с деталями для FullCalendar
     */
    private function getBookedDatesWithDetails(Carbon $start, Carbon $end): array
    {
        $bookedDates = [];
        
        // Заявки
        $applications = Application::whereIn('status', [Application::STATUS_PAID, Application::STATUS_COMPLETED])
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('booking_date', [$start, $end])
                      ->orWhereBetween('booking_end_date', [$start, $end])
                      ->orWhere(function ($q) use ($start, $end) {
                          $q->where('booking_date', '<=', $start)
                            ->where('booking_end_date', '>=', $end);
                      });
            })
            ->with('client', 'bundle')
            ->get();
        
        foreach ($applications as $app) {
            $current = Carbon::parse($app->booking_date);
            $endDate = Carbon::parse($app->booking_end_date);
            
            while ($current->lte($endDate)) {
                $dateStr = $current->format('Y-m-d');
                if (!isset($bookedDates[$dateStr])) {
                    $bookedDates[$dateStr] = [];
                }
                
                $bookedDates[$dateStr][] = [
                    'id' => $app->id,
                    'number' => 'APP-' . $app->id,
                    'client_name' => $app->client->name ?? 'Не указано',
                    'client_phone' => $app->client->phone_pretty ?? 'Не указано',
                    'people_count' => $app->people_count,
                    'booking_date' => $app->booking_date->format('Y-m-d'),
                    'booking_end_date' => $app->booking_end_date->format('Y-m-d'),
                    'bundle_name' => $app->bundle->name ?? 'Не указано',
                ];
                
                $current->addDay();
            }
        }
        
        // Заказы
        $orders = Order::where('status', Order::STATUS_ACTIVE)
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('booking_date', [$start, $end])
                      ->orWhereBetween('booking_end_date', [$start, $end])
                      ->orWhere(function ($q) use ($start, $end) {
                          $q->where('booking_date', '<=', $start)
                            ->where('booking_end_date', '>=', $end);
                      });
            })
            ->get();
        
        foreach ($orders as $order) {
            $current = Carbon::parse($order->booking_date);
            $endDate = Carbon::parse($order->booking_end_date);
            
            while ($current->lte($endDate)) {
                $dateStr = $current->format('Y-m-d');
                if (!isset($bookedDates[$dateStr])) {
                    $bookedDates[$dateStr] = [];
                }
                
                $bookedDates[$dateStr][] = [
                    'id' => $order->id,
                    'number' => $order->number,
                    'client_name' => $order->client_name_snapshot,
                    'client_phone' => $order->client_phone_snapshot,
                    'people_count' => $order->people_count,
                    'booking_date' => $order->booking_date->format('Y-m-d'),
                    'booking_end_date' => $order->booking_end_date->format('Y-m-d'),
                    'bundle_name' => $order->bundle_name_snapshot ?? 'Не указано',
                ];
                
                $current->addDay();
            }
        }
        
        return $bookedDates;
    }
}

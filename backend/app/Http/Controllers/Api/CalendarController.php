<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CalendarService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CalendarController extends Controller
{
    public function __construct(
        private CalendarService $calendarService
    ) {}

    /**
     * Получить статус дат в диапазоне
     */
    public function getDates(Request $request): JsonResponse
    {
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        $data = $this->calendarService->getDatesInRange(
            $request->input('start'),
            $request->input('end')
        );

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Получить статус дат для всего года
     */
    public function getYear(Request $request): JsonResponse
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        $data = $this->calendarService->getYear($request->input('year'));

        return response()->json($data);
    }

    /**
     * Получить статус дат для месяца
     */
    public function getMonth(Request $request): JsonResponse
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:2030',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $data = $this->calendarService->getMonth(
            $request->input('year'),
            $request->input('month')
        );

        return response()->json($data);
    }

    /**
     * Проверить доступность конкретной даты
     */
    public function checkDate(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $data = $this->calendarService->checkDate($request->input('date'));

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Получить статистику по месяцам года
     */
    public function getStats(Request $request): JsonResponse
    {
        $request->validate([
            'year' => 'nullable|integer|min:2020|max:2030',
        ]);

        $data = $this->calendarService->getStats($request->input('year'));

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Получить ближайшие доступные даты
     */
    public function getNextAvailable(Request $request): JsonResponse
    {
        $request->validate([
            'days' => 'nullable|integer|min:1|max:30',
            'from' => 'nullable|date',
        ]);

        $data = $this->calendarService->getNextAvailable(
            $request->input('days', 7),
            $request->input('from')
        );

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Получить события для FullCalendar
     */
    public function getEvents(Request $request): JsonResponse
    {
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        $events = $this->calendarService->getEvents(
            $request->input('start'),
            $request->input('end')
        );

        // Подсчитываем статистику для отображения
        $stats = [
            'available' => 0,
            'booked' => 0,
            'blocked' => 0,
        ];

        foreach ($events as $event) {
            $status = $event['extendedProps']['status'] ?? 'available';
            if (isset($stats[$status])) {
                $stats[$status]++;
            }
        }

        return response()->json([
            'success' => true,
            'events' => $events,
            'stats' => $stats,
        ]);
    }

    /**
     * Заблокировать дату
     */
    public function blockDate(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'reason' => 'nullable|string|max:255',
        ]);

        $success = $this->calendarService->blockDate(
            $request->input('date'),
            $request->input('reason'),
            auth()->id()
        );

        if (!$success) {
            return response()->json([
                'success' => false,
                'message' => 'Не удалось заблокировать дату. Возможно, она уже заблокирована.',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Дата успешно заблокирована.',
        ]);
    }

    /**
     * Разблокировать дату
     */
    public function unblockDate(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $success = $this->calendarService->unblockDate($request->input('date'));

        if (!$success) {
            return response()->json([
                'success' => false,
                'message' => 'Не удалось разблокировать дату.',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Дата успешно разблокирована.',
        ]);
    }
}



<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\Application;
use App\Services\TelegramService;

class BookingController extends Controller
{
    /**
     * Обработка формы бронирования путешествия
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Валидация данных
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'tel' => 'required|string|max:20',
                'email' => 'required|email|max:255',
            ], [
                'name.required' => 'Поле "Имя" обязательно для заполнения',
                'tel.required' => 'Поле "Телефон" обязательно для заполнения',
                'email.required' => 'Поле "Email" обязательно для заполнения',
                'email.email' => 'Некорректный формат email',
            ]);

            // Нормализация контактов
            $normEmail = Client::normalizeEmail($validated['email']);
            $normPhone = Client::normalizePhone($validated['tel']);
            
            if (!$normPhone) {
                return response()->json([
                    'success' => false,
                    'message' => 'Некорректный номер телефона.',
                ], 422);
            }

            // Создаем клиента и заявку в транзакции
            [$client, $application] = DB::transaction(function () use ($validated, $normEmail, $normPhone) {
                // 1) Находим или создаем клиента
                $client = Client::query()
                    ->when($normEmail, fn ($q) => $q->orWhere('email', $normEmail))
                    ->orWhere('phone', $normPhone)
                    ->first();

                if (!$client) {
                    // Создаем нового клиента
                    $client = Client::create([
                        'name' => $validated['name'],
                        'email' => $normEmail,
                        'phone' => $normPhone,
                        'comment' => null,
                        'client_wishes' => 'Заявка на бронирование путешествия',
                    ]);
                } else {
                    // Обновляем существующего клиента, если нужно
                    $updates = [];
                    if (empty($client->name) && !empty($validated['name'])) {
                        $updates['name'] = $validated['name'];
                    }
                    if ($normEmail && empty($client->email)) {
                        $updates['email'] = $normEmail;
                    }
                    
                    if ($updates) {
                        $client->update($updates);
                    }
                }

                // 2) Создаем новую заявку на бронирование
                $application = Application::create([
                    'client_id' => $client->id,
                    'service_id' => null, // Для формы бронирования услуга не выбрана
                    'bundle_id' => null,  // Для формы бронирования пакет не выбран
                    'nights' => 2,        // Минимальное количество ночей
                    'status' => 'new',    // Всегда новая заявка
                    'client_wishes' => 'Заявка на бронирование путешествия',
                    'comment' => null,    // Комментарий менеджера
                ]);

                return [$client, $application];
            });

            // Логируем успешное создание
            Log::info('Форма бронирования обработана', [
                'client_id' => $client->id,
                'application_id' => $application->id,
                'client_name' => $client->name,
                'client_phone' => $client->phone,
                'client_email' => $client->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
            ]);

            // Отправляем уведомление в Telegram (если настроен)
            try {
                if (config('services.telegram.bot_token')) {
                    $telegramService = app(TelegramService::class);
                    $telegramService->sendNewApplicationNotification([
                        'client_name' => $client->name,
                        'client_phone' => $client->phone_pretty ?? $client->phone,
                        'client_email' => $client->email,
                        'comment' => 'Заявка на бронирование путешествия',
                        'application_id' => $application->id,
                        'booking_date' => null,
                        'booking_end_date' => null,
                        'people_count' => null,
                        'bundle_name' => null,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Ошибка отправки Telegram уведомления', [
                    'error' => $e->getMessage(),
                    'application_id' => $application->id,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Спасибо за заявку! Мы свяжемся с вами в ближайшее время для уточнения деталей бронирования.',
                'data' => [
                    'client_id' => $client->id,
                    'application_id' => $application->id,
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка валидации данных',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Ошибка обработки формы бронирования', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка при отправке формы. Попробуйте еще раз.',
            ], 500);
        }
    }
}

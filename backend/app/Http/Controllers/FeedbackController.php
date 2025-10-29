<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\Application;
use App\Services\TelegramService;

class FeedbackController extends Controller
{
    /**
     * Обработка формы обратной связи
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Валидация данных
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'comment' => 'nullable|string|max:1000',
                'privacy_agreement' => 'required|accepted',
            ], [
                'name.required' => 'Поле "Имя" обязательно для заполнения',
                'phone.required' => 'Поле "Телефон" обязательно для заполнения',
                'email.required' => 'Поле "Email" обязательно для заполнения',
                'email.email' => 'Некорректный формат email',
                'privacy_agreement.required' => 'Необходимо согласие на обработку персональных данных',
                'privacy_agreement.accepted' => 'Необходимо согласие на обработку персональных данных',
            ]);

            // Нормализация контактов
            $normEmail = Client::normalizeEmail($validated['email']);
            $normPhone = Client::normalizePhone($validated['phone']);
            
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
                        'client_wishes' => $validated['comment'] ?? null,
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
                    if (!empty($validated['comment'])) {
                        $updates['client_wishes'] = $validated['comment'];
                    }
                    
                    if ($updates) {
                        $client->update($updates);
                    }
                }

                // 2) Создаем новую заявку
                $application = Application::create([
                    'client_id' => $client->id,
                    'service_id' => null, // Для формы обратной связи услуга не выбрана
                    'bundle_id' => null,  // Для формы обратной связи пакет не выбран
                    'nights' => 2,        // Минимальное количество ночей для формы обратной связи
                    'status' => 'new',    // Всегда новая заявка
                    'client_wishes' => $validated['comment'] ?? null,
                    'comment' => null,    // Комментарий менеджера
                ]);

                return [$client, $application];
            });

            // Логируем успешное создание
            Log::info('Форма обратной связи обработана', [
                'client_id' => $client->id,
                'application_id' => $application->id,
                'client_name' => $client->name,
                'client_phone' => $client->phone,
                'client_email' => $client->email,
                'comment' => $validated['comment'] ?? null,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
            ]);

            // Отправляем уведомление в Telegram
            try {
                $telegramService = app(TelegramService::class);
                $telegramService->sendNewApplicationNotification([
                    'client_name' => $client->name,
                    'client_phone' => $client->phone_pretty ?? $client->phone,
                    'client_email' => $client->email,
                    'comment' => $validated['comment'] ?? null,
                    'application_id' => $application->id,
                    'booking_date' => null,
                    'booking_end_date' => null,
                    'people_count' => null,
                    'bundle_name' => null,
                ]);
            } catch (\Exception $e) {
                Log::error('Ошибка отправки Telegram уведомления', [
                    'error' => $e->getMessage(),
                    'application_id' => $application->id,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Спасибо за обращение! Мы свяжемся с вами в ближайшее время.',
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
            Log::error('Ошибка обработки формы обратной связи', [
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
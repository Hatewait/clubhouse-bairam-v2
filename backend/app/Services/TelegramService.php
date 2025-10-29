<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    private string $botToken;
    private string $chatId;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token');
        $this->chatId = config('services.telegram.chat_id');
    }

    /**
     * Отправить уведомление о новой заявке
     */
    public function sendNewApplicationNotification(array $applicationData): bool
    {
        if (!$this->botToken || !$this->chatId) {
            Log::warning('Telegram bot token or chat ID not configured');
            return false;
        }

        $message = $this->formatApplicationMessage($applicationData);
        
        return $this->sendMessage($message);
    }

    /**
     * Отправить сообщение в Telegram
     */
    private function sendMessage(string $message): bool
    {
        try {
            $response = Http::post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
                'chat_id' => $this->chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true,
            ]);

            if ($response->successful()) {
                Log::info('Telegram notification sent successfully', [
                    'chat_id' => $this->chatId,
                    'response' => $response->json()
                ]);
                return true;
            } else {
                Log::error('Failed to send Telegram notification', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Exception while sending Telegram notification', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Форматировать сообщение о заявке
     */
    private function formatApplicationMessage(array $data): string
    {
        $message = "🆕 <b>Новая заявка с сайта</b>\n\n";
        
        $message .= "👤 <b>Клиент:</b> " . ($data['client_name'] ?? 'Не указано') . "\n";
        $message .= "📞 <b>Телефон:</b> " . ($data['client_phone'] ?? 'Не указано') . "\n";
        $message .= "📧 <b>Email:</b> " . ($data['client_email'] ?? 'Не указано') . "\n";
        
        if (!empty($data['comment'])) {
            $message .= "💬 <b>Комментарий:</b> " . $data['comment'] . "\n";
        }
        
        if (!empty($data['bundle_name'])) {
            $message .= "🏖️ <b>Формат отдыха:</b> " . $data['bundle_name'] . "\n";
        }
        
        if (!empty($data['booking_date']) && !empty($data['booking_end_date'])) {
            $message .= "📅 <b>Даты:</b> " . $data['booking_date'] . " - " . $data['booking_end_date'] . "\n";
        }
        
        if (!empty($data['people_count'])) {
            $message .= "👥 <b>Количество людей:</b> " . $data['people_count'] . "\n";
        }
        
        $message .= "\n🔗 <b>ID заявки:</b> #" . ($data['application_id'] ?? 'N/A') . "\n";
        $message .= "⏰ <b>Время:</b> " . now()->format('d.m.Y H:i');
        
        return $message;
    }

    /**
     * Отправить тестовое сообщение
     */
    public function sendTestMessage(): bool
    {
        $message = "🧪 <b>Тестовое сообщение</b>\n\n";
        $message .= "Telegram уведомления настроены корректно!\n";
        $message .= "⏰ " . now()->format('d.m.Y H:i:s');
        
        return $this->sendMessage($message);
    }
}

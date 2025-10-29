<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetTelegramChatId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:chat-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получить Chat ID для Telegram бота';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $botToken = config('services.telegram.bot_token');
        
        if (!$botToken) {
            $this->error('❌ TELEGRAM_BOT_TOKEN не настроен в .env файле');
            return;
        }

        $this->info('🔍 Получение обновлений от бота...');
        $this->info('📝 Отправьте любое сообщение боту в Telegram, затем нажмите Enter');
        
        $this->ask('Нажмите Enter после отправки сообщения боту');

        try {
            $response = Http::get("https://api.telegram.org/bot{$botToken}/getUpdates");
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['result']) && !empty($data['result'])) {
                    $lastUpdate = end($data['result']);
                    
                    if (isset($lastUpdate['message']['chat']['id'])) {
                        $chatId = $lastUpdate['message']['chat']['id'];
                        $chatType = $lastUpdate['message']['chat']['type'] ?? 'unknown';
                        $chatTitle = $lastUpdate['message']['chat']['title'] ?? $lastUpdate['message']['chat']['first_name'] ?? 'Unknown';
                        
                        $this->info('✅ Chat ID найден!');
                        $this->info("📋 Chat ID: {$chatId}");
                        $this->info("👤 Тип чата: {$chatType}");
                        $this->info("🏷️ Название: {$chatTitle}");
                        $this->newLine();
                        $this->info('Добавьте в .env файл:');
                        $this->line("TELEGRAM_CHAT_ID={$chatId}");
                    } else {
                        $this->error('❌ Chat ID не найден в ответе');
                    }
                } else {
                    $this->error('❌ Нет обновлений. Убедитесь, что вы отправили сообщение боту');
                }
            } else {
                $this->error('❌ Ошибка API: ' . $response->status());
                $this->error('Ответ: ' . $response->body());
            }
        } catch (\Exception $e) {
            $this->error('❌ Исключение: ' . $e->getMessage());
        }
    }
}
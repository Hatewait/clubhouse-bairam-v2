<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TelegramService;

class TestTelegramNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Отправить тестовое уведомление в Telegram';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Отправка тестового уведомления в Telegram...');

        try {
            $telegramService = app(TelegramService::class);
            $result = $telegramService->sendTestMessage();

            if ($result) {
                $this->info('✅ Тестовое уведомление отправлено успешно!');
            } else {
                $this->error('❌ Ошибка отправки уведомления. Проверьте логи.');
            }
        } catch (\Exception $e) {
            $this->error('❌ Исключение: ' . $e->getMessage());
        }
    }
}
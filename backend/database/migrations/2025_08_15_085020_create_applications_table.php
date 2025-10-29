<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();

            // Связи
            $table->foreignId('client_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();

            // Даты тура (может быть одна ночь)
            $table->date('booking_date')->nullable();
            $table->date('booking_end_date')->nullable();

            // Параметры
            $table->unsignedSmallInteger('people_count')->default(1);

            // Статус: new / approved / cancelled
            $table->string('status', 20)->default('new')->index();

            // Комментарий клиента и менеджера
            $table->text('comment')->nullable();

            // Кэш рассчитанной суммы на момент изменения (для быстрого списка)
            $table->unsignedInteger('total_price')->default(0);

            $table->timestamps();

            // Индексы для фильтров
            $table->index(['booking_date', 'booking_end_date']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
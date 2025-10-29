<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Одна заявка -> один заказ
            $table->foreignId('application_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->unique('application_id');

            // Дублируем ссылки (для удобства фильтров/отчётов)
            $table->foreignId('client_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();

            // Номер заказа
            $table->string('number', 32)->unique()->index();

            // Снапшоты на момент оформления (ничего из этого в будущем не меняется)
            $table->string('service_name_snapshot', 255);
            $table->unsignedInteger('price_snapshot');         // цена услуги на момент заказа
            $table->string('price_type_snapshot', 20);         // per_person / per_group

            $table->string('client_name_snapshot', 255);
            $table->string('client_email_snapshot', 190)->nullable();
            $table->string('client_phone_snapshot', 32)->nullable();

            // Даты и параметры
            $table->date('booking_date')->nullable();
            $table->date('booking_end_date')->nullable();
            $table->unsignedSmallInteger('people_count')->default(1);

            // Итоги
            $table->unsignedInteger('total_price')->default(0);
            $table->unsignedInteger('discount_amount')->default(0);
            $table->unsignedInteger('final_total')->default(0);

            // Статусы (менять можно только если захочешь — сейчас редактируется ТОЛЬКО comment)
            $table->string('status', 20)->default('active')->index();          // active/completed/cancelled
            $table->string('payment_status', 20)->default('unpaid')->index();  // unpaid/partial/paid/refunded

            // Единственное редактируемое поле
            $table->text('comment')->nullable();

            $table->timestamps();

            // Индексы для фильтров
            $table->index(['booking_date', 'booking_end_date']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
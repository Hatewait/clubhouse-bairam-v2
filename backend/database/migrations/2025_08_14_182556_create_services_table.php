<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            $table->string('name');                         // Название услуги
            $table->enum('service_type', ['tour','extra']); // Тип: тур / опция
            $table->enum('price_type', ['per_person','per_group']); // Расчет: за человека / за группу

            $table->unsignedInteger('price');               // Стоимость (в рублях, целое число)
            $table->unsignedSmallInteger('max_people')->nullable(); // Макс. людей (актуально для "за человека")

            $table->text('description')->nullable();        // Описание (покажем на сайте)
            $table->text('comment')->nullable();            // Комментарий (служебный для менеджера)

            $table->boolean('is_active')->default(true);    // Активность услуги

            $table->timestamps();

            $table->index(['name', 'service_type', 'price_type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
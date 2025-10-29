<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Удаляем старую таблицу application_addon
        Schema::dropIfExists('application_addon');
        
        // Создаем новую таблицу application_addon с ссылкой на options
        Schema::create('application_addon', function (Blueprint $table) {
            $table->id();

            $table->foreignId('application_id')
                ->constrained('applications')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('option_id')
                ->constrained('options')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->unsignedSmallInteger('quantity')->default(1);

            $table->unique(['application_id', 'option_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Удаляем новую таблицу
        Schema::dropIfExists('application_addon');
        
        // Восстанавливаем старую таблицу
        Schema::create('application_addon', function (Blueprint $table) {
            $table->id();

            $table->foreignId('application_id')
                ->constrained('applications')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->unsignedSmallInteger('quantity')->default(1);

            $table->unique(['application_id', 'service_id']);
            $table->timestamps();
        });
    }
};
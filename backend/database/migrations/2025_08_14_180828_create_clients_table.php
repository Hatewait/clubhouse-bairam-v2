<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // Имя (обязательно)
            $table->string('phone')->unique();      // Телефон (обязательно, уникальный)
            $table->string('email')->nullable();    // Email (необязательный)
            $table->text('comment')->nullable();    // Комментарий
            $table->timestamps();

            $table->index(['name', 'phone', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
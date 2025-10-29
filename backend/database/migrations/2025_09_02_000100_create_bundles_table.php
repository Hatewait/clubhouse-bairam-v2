<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bundles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 190);
            $table->unsignedTinyInteger('nights')->default(2)->index(); // минимум 2
            $table->unsignedInteger('price')->default(0);               // фикс-цена за весь пакет
            $table->boolean('is_active')->default(true)->index();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'nights']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bundles');
    }
};
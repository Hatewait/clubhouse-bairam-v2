<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // наименование
            $table->text('description')->nullable();// описание для сайта
            $table->string('image_path')->nullable();// путь к изображению (disk=public)
            $table->unsignedInteger('price')->default(0); // цена в рублях (если хочешь в копейках — поменяем на bigInteger)
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('options');
    }
};
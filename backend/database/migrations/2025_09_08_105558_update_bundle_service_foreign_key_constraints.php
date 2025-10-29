<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bundle_service', function (Blueprint $table) {
            // Удаляем старые внешние ключи
            $table->dropForeign(['bundle_id']);
            $table->dropForeign(['service_id']);
            
            // Создаем новые с CASCADE для удаления
            $table->foreign('bundle_id')
                ->references('id')
                ->on('bundles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
                
            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('bundle_service', function (Blueprint $table) {
            // Удаляем новые внешние ключи
            $table->dropForeign(['bundle_id']);
            $table->dropForeign(['service_id']);
            
            // Восстанавливаем старые с RESTRICT
            $table->foreign('bundle_id')
                ->references('id')
                ->on('bundles')
                ->onUpdate('cascade')
                ->onDelete('restrict');
                
            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }
};
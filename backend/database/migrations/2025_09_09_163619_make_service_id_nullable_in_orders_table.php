<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Удаляем foreign key constraint
            $table->dropForeign(['service_id']);
            
            // Делаем поле nullable
            $table->unsignedBigInteger('service_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Восстанавливаем foreign key constraint
            $table->foreign('service_id')->references('id')->on('services')->cascadeOnUpdate()->restrictOnDelete();
        });
    }
};
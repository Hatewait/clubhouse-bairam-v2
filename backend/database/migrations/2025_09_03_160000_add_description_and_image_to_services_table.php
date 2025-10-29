<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('services', 'image_path')) {
                $table->string('image_path')->nullable();
            }
            if (!Schema::hasColumn('services', 'is_active')) {
                $table->boolean('is_active')->default(true)->index();
            }
            // Ничего не дропаем (sqlite капризничает). Просто перестаём использовать старые поля.
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('services', 'image_path')) {
                $table->dropColumn('image_path');
            }
            if (Schema::hasColumn('services', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
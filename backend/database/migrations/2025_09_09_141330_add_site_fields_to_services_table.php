<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('services')) return;

        Schema::table('services', function (Blueprint $table) {
            // Добавляем только если нет — переносы "после ..." опускаем, чтобы не спорить с порядком
            if (! Schema::hasColumn('services', 'site_title')) {
                $table->string('site_title')->nullable();
            }
            if (! Schema::hasColumn('services', 'site_subtitle')) {
                $table->string('site_subtitle')->nullable();
            }
            if (! Schema::hasColumn('services', 'site_description')) {
                $table->text('site_description')->nullable();
            }
            if (! Schema::hasColumn('services', 'site_image_path')) {
                $table->string('site_image_path')->nullable();
            }
            if (! Schema::hasColumn('services', 'price')) {
                $table->integer('price')->default(0);
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('services')) return;

        Schema::table('services', function (Blueprint $table) {
            // Дропаем только существующие
            if (Schema::hasColumn('services', 'price')) {
                $table->dropColumn('price');
            }
            if (Schema::hasColumn('services', 'site_image_path')) {
                $table->dropColumn('site_image_path');
            }
            if (Schema::hasColumn('services', 'site_description')) {
                $table->dropColumn('site_description');
            }
            if (Schema::hasColumn('services', 'site_subtitle')) {
                $table->dropColumn('site_subtitle');
            }
            if (Schema::hasColumn('services', 'site_title')) {
                $table->dropColumn('site_title');
            }
        });
    }
};

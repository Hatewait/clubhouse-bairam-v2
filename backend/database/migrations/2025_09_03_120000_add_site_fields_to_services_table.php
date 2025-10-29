<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            if (! Schema::hasColumn('services', 'site_description')) {
                $table->text('site_description')->nullable()->after('is_active');
            }
            if (! Schema::hasColumn('services', 'image_path')) {
                $table->string('image_path', 255)->nullable()->after('site_description');
            }
            if (! Schema::hasColumn('services', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'image_path')) {
                $table->dropColumn('image_path');
            }
            if (Schema::hasColumn('services', 'site_description')) {
                $table->dropColumn('site_description');
            }
            // is_active оставим — он уже использовался.
        });
    }
};
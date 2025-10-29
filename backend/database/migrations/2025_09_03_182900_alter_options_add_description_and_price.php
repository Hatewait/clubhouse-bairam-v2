<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // В SQLite можно безопасно только добавлять новые колонки.
        Schema::table('options', function (Blueprint $table) {
            // описание для сайта (оставляем старое site_description нетронутым — вдруг где-то используется)
            if (! Schema::hasColumn('options', 'description')) {
                $table->text('description')->nullable()->after('name');
            }

            // цена опции (по умолчанию 0)
            if (! Schema::hasColumn('options', 'price')) {
                // используем integer в SQLite для простоты. При переходе на MySQL можно сменить на decimal(10,2)
                $table->unsignedInteger('price')->default(0)->after('image_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('options', function (Blueprint $table) {
            if (Schema::hasColumn('options', 'price')) {
                $table->dropColumn('price');
            }
            if (Schema::hasColumn('options', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
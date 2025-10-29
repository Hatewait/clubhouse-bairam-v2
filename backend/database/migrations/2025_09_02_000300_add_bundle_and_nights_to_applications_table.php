<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // НОВОЕ: заявка может ссылаться на бандл (опционально, если клиент выбрал просто ночи)
            if (! Schema::hasColumn('applications', 'bundle_id')) {
                $table->foreignId('bundle_id')
                    ->nullable()
                    ->after('service_id') // чтобы осталось рядом с «старой» логикой
                    ->constrained('bundles')
                    ->cascadeOnUpdate()
                    ->nullOnDelete();
            }

            // НОВОЕ: фиксируем число ночей как источник правды (для диапазона дат)
            if (! Schema::hasColumn('applications', 'nights')) {
                $table->unsignedSmallInteger('nights')
                    ->default(2)
                    ->after('booking_end_date')
                    ->comment('Количество ночей (>=2). Если выбран бандл — копия bundle.nights');
            }

            $table->index(['bundle_id', 'nights']);
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            if (Schema::hasColumn('applications', 'bundle_id')) {
                $table->dropConstrainedForeignId('bundle_id');
            }
            if (Schema::hasColumn('applications', 'nights')) {
                $table->dropColumn('nights');
            }
        });
    }
};
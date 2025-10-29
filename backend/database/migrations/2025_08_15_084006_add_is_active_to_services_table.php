<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // services: добавляем is_active, если ещё нет
        Schema::table('services', function (Blueprint $table) {
            if (! Schema::hasColumn('services', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('id');
            }
        });

        // applications: индекс по service_id, если ещё нет
        if (Schema::hasTable('applications') && Schema::hasColumn('applications', 'service_id')) {
            Schema::table('applications', function (Blueprint $table) {
                // имя индекса фиксируем явно
                if (! $this->indexExists('applications', 'idx_applications_service_id')) {
                    $table->index('service_id', 'idx_applications_service_id');
                }
            });
        }
    }

    public function down(): void
    {
        // откатываем индекс в applications
        if ($this->indexExists('applications', 'idx_applications_service_id')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->dropIndex('idx_applications_service_id');
            });
        }

        // убираем колонку is_active из services
        if (Schema::hasColumn('services', 'is_active')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        $res = DB::select(
            'SELECT COUNT(1) AS c FROM information_schema.statistics WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ?',
            [$table, $index]
        );
        return !empty($res) && ((int)($res[0]->c ?? 0) > 0);
    }
};

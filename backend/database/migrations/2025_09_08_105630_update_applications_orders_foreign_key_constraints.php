<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Убедимся, что колонки, которые будут SET NULL, действительно nullable
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'service_id')) {
            // меняем NOT NULL -> NULL без DBAL
            DB::statement("ALTER TABLE `orders` MODIFY `service_id` BIGINT UNSIGNED NULL");
        }

        if (Schema::hasTable('applications') && Schema::hasColumn('applications', 'service_id')) {
            // мало ли, если предыдущая миграция не сработала
            DB::statement("ALTER TABLE `applications` MODIFY `service_id` BIGINT UNSIGNED NULL");
        }

        // 2) Сносим старые FKs, если вдруг есть
        $this->dropFkIfExists('orders', 'orders_service_id_foreign');
        $this->dropFkIfExists('applications', 'applications_service_id_foreign');

        // 3) Вешаем правильные внешние ключи
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'service_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreign('service_id', 'orders_service_id_foreign')
                      ->references('id')->on('services')
                      ->nullOnDelete()
                      ->cascadeOnUpdate();
            });
        }

        if (Schema::hasTable('applications') && Schema::hasColumn('applications', 'service_id')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->foreign('service_id', 'applications_service_id_foreign')
                      ->references('id')->on('services')
                      ->nullOnDelete()
                      ->cascadeOnUpdate();
            });
        }
    }

    public function down(): void
    {
        // снимаем FKs в откате
        $this->dropFkIfExists('orders', 'orders_service_id_foreign');
        $this->dropFkIfExists('applications', 'applications_service_id_foreign');

        // возвращать NOT NULL не будем — эта миграция про FK, а не про бизнес-правила.
    }

    private function dropFkIfExists(string $table, string $fkName): void
    {
        // есть ли такой FK?
        $rows = DB::select(
            "SELECT CONSTRAINT_NAME 
               FROM information_schema.REFERENTIAL_CONSTRAINTS 
              WHERE CONSTRAINT_SCHEMA = DATABASE() 
                AND TABLE_NAME = ? 
                AND CONSTRAINT_NAME = ?",
            [$table, $fkName]
        );

        if (!empty($rows)) {
            DB::statement("ALTER TABLE `$table` DROP FOREIGN KEY `$fkName`");
        }
    }
};

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
            // Добавляем только недостающие поля
            if (!Schema::hasColumn('orders', 'bundle_price_snapshot')) {
                $table->integer('bundle_price_snapshot')->nullable()->after('bundle_nights_snapshot');
            }
            if (!Schema::hasColumn('orders', 'bundle_services_snapshot')) {
                $table->text('bundle_services_snapshot')->nullable()->after('bundle_price_snapshot');
            }
            if (!Schema::hasColumn('orders', 'client_comment')) {
                $table->text('client_comment')->nullable()->after('client_phone_snapshot');
            }
            if (!Schema::hasColumn('orders', 'addons_snapshot')) {
                $table->text('addons_snapshot')->nullable()->after('client_comment');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'bundle_price_snapshot')) {
                $table->dropColumn('bundle_price_snapshot');
            }
            if (Schema::hasColumn('orders', 'bundle_services_snapshot')) {
                $table->dropColumn('bundle_services_snapshot');
            }
            if (Schema::hasColumn('orders', 'client_comment')) {
                $table->dropColumn('client_comment');
            }
            if (Schema::hasColumn('orders', 'addons_snapshot')) {
                $table->dropColumn('addons_snapshot');
            }
        });
    }
};

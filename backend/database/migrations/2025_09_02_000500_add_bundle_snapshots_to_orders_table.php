<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Чтобы удобнее было строить отчёты, можно сохранить ссылку на бандл (необязательно)
            if (! Schema::hasColumn('orders', 'bundle_id')) {
                $table->foreignId('bundle_id')
                    ->nullable()
                    ->after('service_id') // рядом со «старым» полем
                    ->constrained('bundles')
                    ->cascadeOnUpdate()
                    ->nullOnDelete();
            }

            // Снапшоты бандла на момент оплаты (immutable)
            if (! Schema::hasColumn('orders', 'bundle_name_snapshot')) {
                $table->string('bundle_name_snapshot', 190)->nullable()->after('number');
            }
            if (! Schema::hasColumn('orders', 'bundle_nights_snapshot')) {
                $table->unsignedSmallInteger('bundle_nights_snapshot')->default(2)->after('bundle_name_snapshot');
            }
            if (! Schema::hasColumn('orders', 'bundle_price_snapshot')) {
                $table->unsignedInteger('bundle_price_snapshot')->default(0)->after('bundle_nights_snapshot');
            }

            // Опционально: список «включённых в бандл» основных услуг в виде JSON (для печати)
            if (! Schema::hasColumn('orders', 'bundle_services_snapshot')) {
                $table->json('bundle_services_snapshot')->nullable()->after('bundle_price_snapshot');
            }

            $table->index(['bundle_id']);
        });

        // Отдельная таблица снапшотов доп.услуг заказа
        Schema::create('order_addons', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Всё — снапшоты (название/цена/тип/qty/total) — никаких FK на services
            $table->string('name', 190);
            $table->unsignedInteger('price')->default(0);
            $table->string('price_type', 20)->default('per_unit'); // per_unit | per_person
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->unsignedInteger('total')->default(0);

            $table->timestamps();

            $table->index(['order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_addons');

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'bundle_id')) {
                $table->dropConstrainedForeignId('bundle_id');
            }
            if (Schema::hasColumn('orders', 'bundle_name_snapshot')) {
                $table->dropColumn('bundle_name_snapshot');
            }
            if (Schema::hasColumn('orders', 'bundle_nights_snapshot')) {
                $table->dropColumn('bundle_nights_snapshot');
            }
            if (Schema::hasColumn('orders', 'bundle_price_snapshot')) {
                $table->dropColumn('bundle_price_snapshot');
            }
            if (Schema::hasColumn('orders', 'bundle_services_snapshot')) {
                $table->dropColumn('bundle_services_snapshot');
            }
        });
    }
};
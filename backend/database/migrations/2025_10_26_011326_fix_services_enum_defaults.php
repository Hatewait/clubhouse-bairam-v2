<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->enum('service_type', ['tour', 'extra'])->default('extra')->change();
            $table->enum('price_type', ['per_person', 'per_group'])->default('per_person')->change();
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->enum('service_type', ['tour', 'extra'])->default(null)->change();
            $table->enum('price_type', ['per_person', 'per_group'])->default(null)->change();
        });
    }
};

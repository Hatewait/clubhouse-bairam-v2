<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'email') && ! $this->indexExists('clients', 'clients_email_unique')) {
                $table->unique('email', 'clients_email_unique');
            }
            if (Schema::hasColumn('clients', 'phone') && ! $this->indexExists('clients', 'clients_phone_unique')) {
                $table->unique('phone', 'clients_phone_unique');
            }
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            if ($this->indexExists('clients', 'clients_email_unique')) {
                $table->dropUnique('clients_email_unique');
            }
            if ($this->indexExists('clients', 'clients_phone_unique')) {
                $table->dropUnique('clients_phone_unique');
            }
        });
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

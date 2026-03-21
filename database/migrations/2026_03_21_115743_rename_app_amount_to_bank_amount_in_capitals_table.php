<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('capital', function (Blueprint $table) {
            $table->decimal('bank_amount', 12, 2)->default(0)->after('cash_amount')->change();
        });
        DB::statement('ALTER TABLE capital RENAME COLUMN bank_amount TO app_amount;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE capital CHANGE COLUMN `bank_amount` `app_amount` DECIMAL(12,2) DEFAULT 0 AFTER `cash_amount`;');
    }
};

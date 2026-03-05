<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('capital', function (Blueprint $table) {
            $table->decimal('cash_amount', 12, 2)->default(0)->after('previous_profit');
            $table->decimal('app_amount', 12, 2)->default(0)->after('cash_amount');
        });

        if (Schema::hasColumn('sales', 'transport_cost')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->dropColumn('transport_cost');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('sales', 'transport_cost') === false) {
            Schema::table('sales', function (Blueprint $table) {
                $table->decimal('transport_cost', 12, 2)->default(0)->after('sale_price');
            });
        }

        Schema::table('capital', function (Blueprint $table) {
            $table->dropColumn(['cash_amount', 'app_amount']);
        });
    }
};

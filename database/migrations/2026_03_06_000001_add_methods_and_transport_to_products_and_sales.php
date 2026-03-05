<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('purchase_method', 20)->default('cash')->after('name');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->string('sale_method', 20)->default('cash')->after('product_id');
            $table->decimal('transport_cost', 12, 2)->default(0)->after('sale_price');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['sale_method', 'transport_cost']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('purchase_method');
        });
    }
};

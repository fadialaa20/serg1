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
        Schema::table('capital', function (Blueprint $table) {
            $table->renameColumn('app_amount', 'bank_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('capital', function (Blueprint $table) {
            $table->renameColumn('bank_amount', 'app_amount');
        });
    }
};

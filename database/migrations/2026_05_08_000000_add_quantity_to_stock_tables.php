<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_in', function (Blueprint $table) {
            $table->unsignedInteger('quantity')->after('productIn');
        });

        Schema::table('stock_out', function (Blueprint $table) {
            $table->unsignedInteger('quantity')->after('productOut');
        });
    }

    public function down(): void
    {
        Schema::table('stock_in', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });

        Schema::table('stock_out', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
};

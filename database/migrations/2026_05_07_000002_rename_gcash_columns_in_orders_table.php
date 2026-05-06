<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'gcash_reference')
                && !Schema::hasColumn('orders', 'payment_reference')) {
                DB::statement('ALTER TABLE `orders` CHANGE `gcash_reference` `payment_reference` VARCHAR(255) NULL');
            }

            if (Schema::hasColumn('orders', 'gcash_proof_path')
                && !Schema::hasColumn('orders', 'payment_proof_path')) {
                DB::statement('ALTER TABLE `orders` CHANGE `gcash_proof_path` `payment_proof_path` VARCHAR(255) NULL');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'payment_reference')
                && !Schema::hasColumn('orders', 'gcash_reference')) {
                DB::statement('ALTER TABLE `orders` CHANGE `payment_reference` `gcash_reference` VARCHAR(255) NULL');
            }

            if (Schema::hasColumn('orders', 'payment_proof_path')
                && !Schema::hasColumn('orders', 'gcash_proof_path')) {
                DB::statement('ALTER TABLE `orders` CHANGE `payment_proof_path` `gcash_proof_path` VARCHAR(255) NULL');
            }
        });
    }
};

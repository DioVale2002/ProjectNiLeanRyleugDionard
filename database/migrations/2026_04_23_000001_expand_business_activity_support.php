<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('Format')->nullable()->after('Genre');
            $table->string('Language')->nullable()->after('Format');
            $table->date('Publication_Date')->nullable()->after('Language');
            $table->string('Subject')->nullable()->after('Publication_Date');
            $table->string('Branch')->nullable()->after('Subject');
            $table->text('Description')->nullable()->after('Review');
        });

        Schema::table('vouchers', function (Blueprint $table) {
            $table->date('valid_from')->nullable()->after('voucherAmount');
            $table->date('valid_until')->nullable()->after('valid_from');
            $table->decimal('minimum_order_amount', 10, 2)->nullable()->after('valid_until');
            $table->unsignedInteger('max_uses')->nullable()->after('minimum_order_amount');
            $table->unsignedInteger('per_customer_limit')->nullable()->after('max_uses');
            $table->boolean('is_active')->default(true)->after('per_customer_limit');
        });

        DB::statement("ALTER TABLE orders MODIFY order_status ENUM('Pending','Processing','Shipped','Delivered','Completed','Cancelled','Failed') DEFAULT 'Pending'");

        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');

        DB::statement("ALTER TABLE orders MODIFY order_status ENUM('Pending','Processing','Completed','Cancelled','Failed') DEFAULT 'Pending'");

        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn([
                'valid_from',
                'valid_until',
                'minimum_order_amount',
                'max_uses',
                'per_customer_limit',
                'is_active',
            ]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'Format',
                'Language',
                'Publication_Date',
                'Subject',
                'Branch',
                'Description',
            ]);
        });
    }
};

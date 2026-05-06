<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_reference')->nullable()->after('paymentMethod_id');
            $table->string('payment_proof_path')->nullable()->after('payment_reference');
            $table->enum('payment_review_status', ['pending', 'approved', 'rejected'])->default('pending')->after('payment_proof_path');
            $table->text('cancellation_note')->nullable()->after('payment_review_status');
            $table->boolean('is_first_party_delivery')->default(false)->after('cancellation_note');
            $table->enum('delivery_status', ['N/A', 'Preparing', 'Out for Delivery', 'Delivered'])
                ->default('N/A')
                ->after('is_first_party_delivery');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_reference',
                'payment_proof_path',
                'payment_review_status',
                'cancellation_note',
                'is_first_party_delivery',
                'delivery_status',
            ]);
        });
    }
};

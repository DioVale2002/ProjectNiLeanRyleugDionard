<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Products table
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_ID');
            $table->string('Title');
            $table->string('Author');
            $table->decimal('Rating', 3, 2)->nullable();
            $table->text('Review')->nullable();
            $table->decimal('Price', 10, 2);
            $table->integer('Stock');
            $table->string('ISBN');
            $table->string('Publisher');
            $table->string('Genre');
            $table->string('Age_Group')->nullable();
            $table->integer('Length')->nullable();
            $table->integer('Width')->nullable();
            $table->timestamps();
        });

        // Payment Methods table
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id('paymentMethod_id');
            $table->string('methodName');
            $table->timestamps();
        });

        // Vouchers table
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id('voucher_id');
            $table->string('voucherName');
            $table->string('voucherType');
            $table->integer('voucherAmount');
            $table->integer('voucherUsed')->default(0);
            $table->timestamps();
        });

        // Carts table
        Schema::create('carts', function (Blueprint $table) {
            $table->id('cart_id');
            $table->date('createdDate');
            $table->enum('status', ['active', 'checked_out', 'abandoned'])->default('active');
            $table->foreignId('cus_id')->constrained('customers', 'cus_id')->onDelete('cascade');
            $table->timestamps();
        });

        // Cart Items table
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id('cartitems_id');
            $table->integer('quantity');
            $table->decimal('unitPrice', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->foreignId('cart_id')->constrained('carts', 'cart_id')->onDelete('cascade');
            $table->foreignId('product_ID')->constrained('products', 'product_ID')->onDelete('cascade');
            $table->timestamps();
        });

        // Orders table
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->enum('order_status', ['Pending', 'Processing', 'Completed', 'Cancelled', 'Failed'])->default('Pending');
            $table->date('order_date');
            $table->decimal('total_price', 10, 2);
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers', 'voucher_id')->onDelete('set null');
            $table->foreignId('add_id')->nullable()->constrained('addresses', 'add_id')->onDelete('set null');
            $table->foreignId('paymentMethod_id')->constrained('payment_methods', 'paymentMethod_id')->onDelete('cascade');
            $table->foreignId('cus_id')->constrained('customers', 'cus_id')->onDelete('cascade');
            $table->foreignId('cart_id')->constrained('carts', 'cart_id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('vouchers');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('products');
    }
};

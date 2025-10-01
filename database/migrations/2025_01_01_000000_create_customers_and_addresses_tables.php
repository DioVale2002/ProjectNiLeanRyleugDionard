<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id('cus_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('contact_num');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->id('add_id');
            $table->string('country');
            $table->string('province');
            $table->string('city');
            $table->string('barangay');
            $table->string('zip_postal_code');
            $table->foreignId('cus_id')->constrained('customers', 'cus_id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('customers');
    }
};
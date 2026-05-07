<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_action_otps', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('action');
            $table->json('payload')->nullable();
            $table->string('code_hash');
            $table->timestamp('expires_at');
            $table->timestamp('consumed_at')->nullable();
            $table->timestamps();

            $table->index(['email', 'action', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_action_otps');
    }
};

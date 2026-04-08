<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('archive', function (Blueprint $table) {
            $table->id('archived_id');
            $table->date('archived_date');
            $table->foreignId('archivedProduct')->constrained('products', 'product_ID')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('archive');
    }
};
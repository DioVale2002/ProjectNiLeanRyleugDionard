<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\PaymentMethod::insert([
            ['methodName' => 'Cash on Delivery', 'created_at' => now(), 'updated_at' => now()],
            ['methodName' => 'GCash', 'created_at' => now(), 'updated_at' => now()],
            ['methodName' => 'Maya', 'created_at' => now(), 'updated_at' => now()],
            ['methodName' => 'Bank Transfer', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\StockBatch;
use Illuminate\Database\Seeder;

class StockBatchSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::orderBy('id')->limit(5)->pluck('id')->toArray();
        $users = User::orderBy('id')->limit(5)->pluck('id')->toArray();

        for ($i = 0; $i < 5; $i++) {
            StockBatch::create([
                'product_id' => $products[$i],
                'quantity' => 20 + ($i * 5),
                'prepared_date' => now()->subDays(5 - $i),
                'expiry_date' => now()->addDays(30 + $i),
                'prepared_by' => $users[$i],
            ]);
        }
    }
}

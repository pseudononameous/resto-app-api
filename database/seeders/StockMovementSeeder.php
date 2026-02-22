<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Database\Seeder;

class StockMovementSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::orderBy('id')->limit(5)->pluck('id')->toArray();
        $types = ['prepared', 'sold', 'waste', 'adjustment', 'purchase'];
        $quantities = [10, -2, -1, 5, 15];

        for ($i = 0; $i < 5; $i++) {
            StockMovement::create([
                'product_id' => $products[$i],
                'movement_type' => $types[$i],
                'quantity' => $quantities[$i],
                'reference_id' => $i + 1,
            ]);
        }
    }
}

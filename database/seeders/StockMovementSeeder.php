<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StockBatch;
use App\Models\StockMovement;
use Illuminate\Database\Seeder;

class StockMovementSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        if ($products->isEmpty()) {
            return;
        }

        $types = ['purchase', 'sold', 'prepared', 'waste', 'adjustment'];

        foreach ($products as $product) {
            $batches = StockBatch::where('product_id', $product->id)->pluck('id')->toArray();
            $movementsPerProduct = rand(3, 6);
            for ($i = 0; $i < $movementsPerProduct; $i++) {
                $type = $types[array_rand($types)];
                $qty = match ($type) {
                    'purchase'   => rand(15, 50),
                    'sold'       => -rand(1, 6),
                    'prepared'   => -rand(1, 4),
                    'waste'      => -rand(1, 3),
                    'adjustment' => rand(-4, 4),
                };
                StockMovement::create([
                    'product_id'    => $product->id,
                    'batch_id'      => $batches ? $batches[array_rand($batches)] : null,
                    'movement_type' => $type,
                    'quantity'      => $qty,
                    'reference_id'  => rand(1000, 9999),
                    'notes'         => rand(0, 3) === 0 ? 'Cafe stock' : null,
                ]);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\ComboMeal;
use App\Models\Product;
use App\Models\ComboItem;
use Illuminate\Database\Seeder;

class ComboItemSeeder extends Seeder
{
    public function run(): void
    {
        $combos = ComboMeal::orderBy('id')->limit(5)->pluck('id')->toArray();
        $products = Product::orderBy('id')->limit(5)->pluck('id')->toArray();

        for ($i = 0; $i < 5; $i++) {
            ComboItem::firstOrCreate(
                [
                    'combo_id' => $combos[$i],
                    'product_id' => $products[$i],
                ],
                [
                    'combo_id' => $combos[$i],
                    'product_id' => $products[$i],
                    'quantity' => $i + 1,
                ]
            );
        }
    }
}

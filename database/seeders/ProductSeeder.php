<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::orderBy('id')->limit(5)->pluck('id')->toArray();
        $brands = Brand::orderBy('id')->limit(5)->pluck('id')->toArray();
        $stores = Store::orderBy('id')->limit(5)->pluck('id')->toArray();

        $products = [
            ['name' => 'Fresh Milk 1L', 'sku' => 'BEV-001', 'price' => 85.00, 'cost_price' => 55.00, 'qty' => 50, 'reorder_level' => 10],
            ['name' => 'Tomato Paste 400g', 'sku' => 'DRY-002', 'price' => 120.00, 'cost_price' => 75.00, 'qty' => 30, 'reorder_level' => 5],
            ['name' => 'Chicken Breast kg', 'sku' => 'MEAT-003', 'price' => 280.00, 'cost_price' => 220.00, 'qty' => 20, 'reorder_level' => 5],
            ['name' => 'Lettuce Head', 'sku' => 'PROD-004', 'price' => 45.00, 'cost_price' => 25.00, 'qty' => 40, 'reorder_level' => 10],
            ['name' => 'Olive Oil 500ml', 'sku' => 'DRY-005', 'price' => 350.00, 'cost_price' => 280.00, 'qty' => 25, 'reorder_level' => 5],
        ];

        foreach ($products as $i => $data) {
            Product::firstOrCreate(
                ['sku' => $data['sku']],
                array_merge($data, [
                    'category_id' => $categories[$i],
                    'brand_id' => $brands[$i],
                    'store_id' => $stores[$i],
                    'availability' => true,
                ])
            );
        }
    }
}

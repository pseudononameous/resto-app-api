<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::orderBy('id')->limit(5)->pluck('id')->toArray();
        $categories = MenuCategory::orderBy('id')->limit(5)->pluck('id')->toArray();

        $items = [
            ['display_name' => 'House Salad', 'base_price' => 150.00],
            ['display_name' => 'Grilled Chicken Plate', 'base_price' => 280.00],
            ['display_name' => 'Pumpkin Soup', 'base_price' => 120.00],
            ['display_name' => 'Chocolate Cake', 'base_price' => 180.00],
            ['display_name' => 'Iced Lemonade', 'base_price' => 80.00],
        ];

        foreach ($items as $i => $data) {
            MenuItem::firstOrCreate(
                [
                    'product_id' => $products[$i],
                    'menu_category_id' => $categories[$i],
                ],
                array_merge($data, [
                    'product_id' => $products[$i],
                    'menu_category_id' => $categories[$i],
                    'is_available' => true,
                ])
            );
        }
    }
}

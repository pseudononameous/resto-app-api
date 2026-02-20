<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\MenuItemIngredient;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $productIds = Product::orderBy('id')->pluck('id')->toArray();
        $categories = MenuCategory::orderBy('id')->limit(5)->pluck('id')->toArray();

        $items = [
            ['display_name' => 'House Salad', 'base_price' => 150.00, 'image_path' => null],
            ['display_name' => 'Grilled Chicken Plate', 'base_price' => 280.00, 'image_path' => null],
            ['display_name' => 'Pumpkin Soup', 'base_price' => 120.00, 'image_path' => null],
            ['display_name' => 'Chocolate Cake', 'base_price' => 180.00, 'image_path' => null],
            ['display_name' => 'Iced Lemonade', 'base_price' => 80.00, 'image_path' => null],
        ];

        foreach ($items as $i => $data) {
            $item = MenuItem::firstOrCreate(
                ['display_name' => $data['display_name']],
                array_merge($data, [
                    'menu_category_id' => $categories[$i % count($categories)],
                    'is_available' => true,
                ])
            );
            if ($item->ingredients()->count() === 0 && count($productIds) >= 2) {
                $ids = array_slice($productIds, $i % (count($productIds) - 1), 2);
                foreach ($ids as $j => $pid) {
                    MenuItemIngredient::create([
                        'menu_item_id' => $item->id,
                        'product_id' => $pid,
                        'quantity_per_serving' => $j + 1,
                    ]);
                }
            }
        }
    }
}

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
        $categoryIds = Category::take(5)->pluck('id')->toArray();
        $brandIds    = Brand::take(5)->pluck('id')->toArray();
        $storeIds    = Store::take(5)->pluck('id')->toArray();

        $products = [
            ['Espresso Blend', 120, 80, 'kg'],
            ['Arabica Beans', 95, 60, 'kg'],
            ['Milk Full Cream', 75, 45, 'L'],
            ['Oat Milk', 85, 50, 'L'],
            ['Croissant', 45, 28, 'pc'],
            ['Chocolate Muffin', 55, 35, 'pc'],
            ['Butter Croissant', 48, 30, 'pc'],
            ['Cinnamon Roll', 65, 40, 'pc'],
            ['Soda Syrup Cola', 120, 70, 'L'],
            ['Vanilla Syrup', 95, 55, 'L'],
            ['Caramel Syrup', 98, 58, 'L'],
            ['Iced Tea Concentrate', 110, 65, 'L'],
            ['Sparkling Water', 35, 20, 'bottle'],
            ['Potato Chips', 42, 25, 'pack'],
            ['Granola Bar', 38, 22, 'pc'],
            ['Cookies Assorted', 55, 32, 'pack'],
            ['Sandwich Wrap', 72, 45, 'pc'],
            ['Yogurt Cup', 48, 28, 'pc'],
            ['Cream Cheese', 88, 52, 'kg'],
            ['Butter Block', 95, 58, 'kg'],
            ['Filter Paper', 25, 15, 'box'],
            ['Paper Cup 8oz', 18, 10, 'sleeve'],
            ['Lid 8oz', 12, 7, 'sleeve'],
            ['Straws', 8, 5, 'pack'],
            ['Napkins', 15, 9, 'box'],
        ];

        foreach ($products as $i => [$name, $price, $cost, $unit]) {
            Product::create([
                'name'          => $name,
                'sku'           => 'CAFE-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'price'         => $price,
                'cost_price'    => $cost,
                'qty'           => rand(15, 80),
                'reorder_level' => rand(5, 15),
                'category_id'   => $categoryIds[$i % 5],
                'brand_id'      => $brandIds[$i % 5],
                'store_id'      => $storeIds[$i % 5],
                'availability'  => true,
            ]);
        }
    }
}

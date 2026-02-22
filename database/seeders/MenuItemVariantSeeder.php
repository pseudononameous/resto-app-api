<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\MenuItemVariant;
use Illuminate\Database\Seeder;

class MenuItemVariantSeeder extends Seeder
{
    public function run(): void
    {
        $menuItems = MenuItem::orderBy('id')->limit(5)->pluck('id')->toArray();
        $variants = [
            ['variant_name' => 'Small', 'price_adjustment' => -20.00],
            ['variant_name' => 'Large', 'price_adjustment' => 50.00],
            ['variant_name' => 'Bowl', 'price_adjustment' => 30.00],
            ['variant_name' => 'Slice', 'price_adjustment' => -100.00],
            ['variant_name' => 'Large', 'price_adjustment' => 20.00],
        ];

        for ($i = 0; $i < 5; $i++) {
            MenuItemVariant::firstOrCreate(
                [
                    'menu_item_id' => $menuItems[$i],
                    'variant_name' => $variants[$i]['variant_name'],
                ],
                array_merge($variants[$i], ['menu_item_id' => $menuItems[$i]])
            );
        }
    }
}

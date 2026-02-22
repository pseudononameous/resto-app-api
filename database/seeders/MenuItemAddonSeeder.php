<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\MenuItemAddon;
use Illuminate\Database\Seeder;

class MenuItemAddonSeeder extends Seeder
{
    public function run(): void
    {
        $menuItems = MenuItem::orderBy('id')->limit(5)->pluck('id')->toArray();
        $addons = [
            ['addon_name' => 'Extra Dressing', 'price' => 25.00],
            ['addon_name' => 'Extra Rice', 'price' => 35.00],
            ['addon_name' => 'Croutons', 'price' => 20.00],
            ['addon_name' => 'Ice Cream', 'price' => 50.00],
            ['addon_name' => 'Extra Lemon', 'price' => 15.00],
        ];

        for ($i = 0; $i < 5; $i++) {
            MenuItemAddon::firstOrCreate(
                [
                    'menu_item_id' => $menuItems[$i],
                    'addon_name' => $addons[$i]['addon_name'],
                ],
                array_merge($addons[$i], ['menu_item_id' => $menuItems[$i]])
            );
        }
    }
}

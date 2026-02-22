<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use Illuminate\Database\Seeder;

class MenuCategorySeeder extends Seeder
{
    public function run(): void
    {
        $names = ['Appetizers', 'Main Course', 'Soups', 'Desserts', 'Drinks'];

        foreach ($names as $name) {
            MenuCategory::firstOrCreate(['name' => $name], ['active' => true]);
        }
    }
}

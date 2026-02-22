<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $names = ['Beverages', 'Dairy', 'Produce', 'Meat & Seafood', 'Dry Goods'];

        foreach ($names as $name) {
            Category::firstOrCreate(['name' => $name], ['active' => true]);
        }
    }
}

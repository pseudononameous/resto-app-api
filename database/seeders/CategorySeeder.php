<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $names = ['Coffee & Espresso', 'Pastries & Bakery', 'Beverages', 'Snacks', 'Dairy & Refrigerated'];
        foreach ($names as $name) {
            Category::firstOrCreate(['name' => $name], ['active' => true]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\ComboMeal;
use Illuminate\Database\Seeder;

class ComboMealSeeder extends Seeder
{
    public function run(): void
    {
        $combos = [
            ['name' => 'Breakfast Combo', 'price' => 199.00, 'active' => true],
            ['name' => 'Lunch Special', 'price' => 249.00, 'active' => true],
            ['name' => 'Family Bundle', 'price' => 599.00, 'active' => true],
            ['name' => 'Quick Bite', 'price' => 149.00, 'active' => true],
            ['name' => 'Dinner for Two', 'price' => 449.00, 'active' => true],
        ];

        foreach ($combos as $data) {
            ComboMeal::firstOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
    }
}

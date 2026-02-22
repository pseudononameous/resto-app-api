<?php

namespace Database\Factories;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuItemFactory extends Factory
{
    protected $model = MenuItem::class;

    public function definition(): array
    {
        return [
            "product_id" => Product::query()->inRandomOrder()->first()?->id,
            "menu_category_id" => MenuCategory::query()->inRandomOrder()->first()?->id,
            "display_name" => fake()->words(3, true),
            "base_price" => fake()->randomFloat(2, 3, 25),
            "is_available" => true,
        ];
    }
}

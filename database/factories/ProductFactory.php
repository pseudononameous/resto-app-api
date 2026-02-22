<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            "name" => fake()->words(3, true),
            "sku" => strtoupper(fake()->unique()->bothify("SKU-####")),
            "price" => fake()->randomFloat(2, 2, 50),
            "cost_price" => fake()->randomFloat(2, 1, 25),
            "qty" => fake()->numberBetween(0, 200),
            "reorder_level" => fake()->numberBetween(5, 30),
            "category_id" => Category::query()->inRandomOrder()->first()?->id,
            "brand_id" => Brand::query()->inRandomOrder()->first()?->id,
            "store_id" => Store::query()->inRandomOrder()->first()?->id,
            "availability" => true,
        ];
    }
}

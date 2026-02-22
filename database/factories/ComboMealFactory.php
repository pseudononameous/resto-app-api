<?php

namespace Database\Factories;

use App\Models\ComboMeal;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComboMealFactory extends Factory
{
    protected $model = ComboMeal::class;

    public function definition(): array
    {
        return [
            "name" => fake()->words(3, true) . " Combo",
            "price" => fake()->randomFloat(2, 8, 25),
            "active" => true,
        ];
    }
}

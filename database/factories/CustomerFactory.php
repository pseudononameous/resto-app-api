<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            "customer_code" => "C" . fake()->unique()->numberBetween(1000, 99999),
            "first_name" => fake()->firstName(),
            "last_name" => fake()->lastName(),
            "phone" => fake()->unique()->e164PhoneNumber(),
            "email" => fake()->unique()->safeEmail(),
            "marketing_opt_in" => fake()->boolean(70),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition(): array
    {
        return [
            "cart_code" => "CART-" . strtoupper(Str::random(8)),
            "user_id" => User::query()->inRandomOrder()->first()?->id,
            "customer_id" => Customer::query()->inRandomOrder()->first()?->id,
            "table_number" => null,
            "status" => "active",
            "total" => 0,
        ];
    }
}

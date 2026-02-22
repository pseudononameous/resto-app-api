<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::orderBy('id')->limit(5)->pluck('id')->toArray();
        $customers = Customer::orderBy('id')->limit(5)->pluck('id')->toArray();
        $statuses = ['active', 'checked_out', 'active', 'cancelled', 'active'];
        $totals = [0, 450.00, 0, 0, 280.00];

        for ($i = 0; $i < 5; $i++) {
            Cart::firstOrCreate(
                ['cart_code' => 'CART-' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT)],
                [
                    'cart_code' => 'CART-' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                    'user_id' => $users[$i],
                    'customer_id' => $customers[$i],
                    'table_number' => $i < 3 ? (string) ($i + 1) : null,
                    'status' => $statuses[$i],
                    'total' => $totals[$i],
                ]
            );
        }
    }
}

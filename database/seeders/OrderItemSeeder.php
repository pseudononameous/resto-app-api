<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::orderBy('id')->limit(5)->pluck('id')->toArray();
        $products = Product::orderBy('id')->limit(5)->pluck('id')->toArray();
        $amounts = [85.00, 280.00, 120.00, 180.00, 350.00];

        for ($i = 0; $i < 5; $i++) {
            OrderItem::firstOrCreate(
                [
                    'order_id' => $orders[$i],
                    'product_id' => $products[$i],
                ],
                [
                    'order_id' => $orders[$i],
                    'product_id' => $products[$i],
                    'qty' => $i + 1,
                    'amount' => $amounts[$i] * ($i + 1),
                ]
            );
        }
    }
}

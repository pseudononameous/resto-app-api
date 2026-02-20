<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Product;
use App\Models\MenuItem;
use App\Models\CartItem;
use Illuminate\Database\Seeder;

class CartItemSeeder extends Seeder
{
    public function run(): void
    {
        $carts = Cart::orderBy('id')->limit(5)->pluck('id')->toArray();
        $products = Product::orderBy('id')->limit(5)->pluck('id')->toArray();
        $menuItems = MenuItem::orderBy('id')->limit(5)->pluck('id')->toArray();
        $quantities = [2, 1, 3, 1, 2];
        $prices = [300.00, 280.00, 360.00, 180.00, 160.00];

        for ($i = 0; $i < 5; $i++) {
            CartItem::firstOrCreate(
                [
                    'cart_id' => $carts[$i],
                    'menu_item_id' => $menuItems[$i],
                ],
                [
                    'cart_id' => $carts[$i],
                    'product_id' => null,
                    'menu_item_id' => $menuItems[$i],
                    'quantity' => $quantities[$i],
                    'total_price' => $prices[$i],
                ]
            );
        }
    }
}

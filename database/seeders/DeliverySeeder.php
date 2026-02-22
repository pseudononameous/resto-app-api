<?php

namespace Database\Seeders;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\DeliveryAddress;
use App\Models\DeliveryZone;
use App\Models\User;
use Illuminate\Database\Seeder;

class DeliverySeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::orderBy('id')->limit(5)->pluck('id')->toArray();
        $addresses = DeliveryAddress::orderBy('id')->limit(5)->pluck('id')->toArray();
        $zones = DeliveryZone::orderBy('id')->limit(5)->pluck('id')->toArray();
        $users = User::orderBy('id')->limit(5)->pluck('id')->toArray();
        $statuses = ['delivered', 'out_for_delivery', 'pending', 'delivered', 'cancelled'];

        for ($i = 0; $i < 5; $i++) {
            Delivery::firstOrCreate(
                [
                    'order_id' => $orders[$i],
                ],
                [
                    'order_id' => $orders[$i],
                    'address_id' => $addresses[$i],
                    'zone_id' => $zones[$i],
                    'rider_id' => $users[$i],
                    'status' => $statuses[$i],
                ]
            );
        }
    }
}

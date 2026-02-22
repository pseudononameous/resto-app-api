<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderType;
use App\Models\Customer;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $orderTypes = OrderType::orderBy('id')->limit(3)->pluck('id')->toArray();
        $customers = Customer::orderBy('id')->limit(5)->pluck('id')->toArray();
        $users = User::orderBy('id')->limit(5)->pluck('id')->toArray();
        $reservations = Reservation::orderBy('id')->limit(5)->pluck('id')->toArray();

        for ($i = 1; $i <= 5; $i++) {
            Order::firstOrCreate(
                ['bill_no' => 'BILL-' . str_pad((string) $i, 4, '0')],
                [
                    'bill_no' => 'BILL-' . str_pad((string) $i, 4, '0'),
                    'order_type_id' => $orderTypes[($i - 1) % 3],
                    'reservation_id' => $i <= 3 ? $reservations[$i - 1] : null,
                    'customer_id' => $customers[$i - 1],
                    'date_time' => now()->subHours(5 - $i),
                    'net_amount' => 250.00 + ($i * 100),
                    'paid_status' => $i !== 4,
                    'user_id' => $users[$i - 1],
                ]
            );
        }
    }
}

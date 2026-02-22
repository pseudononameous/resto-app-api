<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerLoyaltyLog;
use Illuminate\Database\Seeder;

class CustomerLoyaltyLogSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::orderBy('id')->limit(5)->get();
        $types = ['earned', 'redeemed', 'adjusted', 'earned', 'earned'];
        $points = [50, -100, 25, 75, 30];

        foreach ($customers as $i => $customer) {
            CustomerLoyaltyLog::create([
                'customer_id' => $customer->id,
                'points' => $points[$i],
                'type' => $types[$i],
                'reference_order_id' => null,
            ]);
        }
    }
}

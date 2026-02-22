<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerLoyalty;
use Illuminate\Database\Seeder;

class CustomerLoyaltySeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::orderBy('id')->limit(5)->get();
        $tiers = ['Bronze', 'Silver', 'Gold', 'Silver', 'Bronze'];
        $points = [100, 500, 1200, 350, 80];

        foreach ($customers as $i => $customer) {
            CustomerLoyalty::firstOrCreate(
                ['customer_id' => $customer->id],
                [
                    'customer_id' => $customer->id,
                    'points_balance' => $points[$i],
                    'lifetime_points' => $points[$i] * 3,
                    'tier' => $tiers[$i],
                ]
            );
        }
    }
}

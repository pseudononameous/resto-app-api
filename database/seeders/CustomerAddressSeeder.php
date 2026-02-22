<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Database\Seeder;

class CustomerAddressSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::orderBy('id')->limit(5)->get();
        $addresses = [
            ['label' => 'Home', 'address_line' => '123 Main St', 'city' => 'Manila', 'province' => 'NCR', 'latitude' => 14.5995, 'longitude' => 120.9842, 'is_default' => true],
            ['label' => 'Office', 'address_line' => '456 Business Ave', 'city' => 'Makati', 'province' => 'NCR', 'latitude' => 14.5547, 'longitude' => 121.0244, 'is_default' => true],
            ['label' => 'Home', 'address_line' => '789 Oak Rd', 'city' => 'Quezon City', 'province' => 'NCR', 'latitude' => 14.6507, 'longitude' => 121.0500, 'is_default' => true],
            ['label' => 'Home', 'address_line' => '321 Pine St', 'city' => 'Cebu City', 'province' => 'Cebu', 'latitude' => 10.3157, 'longitude' => 123.8854, 'is_default' => true],
            ['label' => 'Condominium', 'address_line' => '555 Tower Blvd', 'city' => 'Taguig', 'province' => 'NCR', 'latitude' => 14.5176, 'longitude' => 121.0509, 'is_default' => true],
        ];

        foreach ($customers as $i => $customer) {
            if (!isset($addresses[$i])) break;
            CustomerAddress::firstOrCreate(
                [
                    'customer_id' => $customer->id,
                    'label' => $addresses[$i]['label'],
                ],
                array_merge($addresses[$i], ['customer_id' => $customer->id])
            );
        }
    }
}

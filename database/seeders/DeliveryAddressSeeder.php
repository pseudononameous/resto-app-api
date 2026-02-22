<?php

namespace Database\Seeders;

use App\Models\DeliveryAddress;
use Illuminate\Database\Seeder;

class DeliveryAddressSeeder extends Seeder
{
    public function run(): void
    {
        $addresses = [
            ['customer_name' => 'Maria Santos', 'phone' => '+639171001001', 'address_line' => '123 Main St', 'city' => 'Manila'],
            ['customer_name' => 'Juan Dela Cruz', 'phone' => '+639171001002', 'address_line' => '456 Business Ave', 'city' => 'Makati'],
            ['customer_name' => 'Ana Reyes', 'phone' => '+639171001003', 'address_line' => '789 Oak Rd', 'city' => 'Quezon City'],
            ['customer_name' => 'Pedro Garcia', 'phone' => '+639171001004', 'address_line' => '321 Pine St', 'city' => 'Cebu City'],
            ['customer_name' => 'Sofia Lopez', 'phone' => '+639171001005', 'address_line' => '555 Tower Blvd', 'city' => 'Taguig'],
        ];

        foreach ($addresses as $data) {
            DeliveryAddress::firstOrCreate(
                [
                    'customer_name' => $data['customer_name'],
                    'address_line' => $data['address_line'],
                ],
                $data
            );
        }
    }
}

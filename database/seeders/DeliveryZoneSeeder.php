<?php

namespace Database\Seeders;

use App\Models\DeliveryZone;
use Illuminate\Database\Seeder;

class DeliveryZoneSeeder extends Seeder
{
    public function run(): void
    {
        $zones = [
            ['zone_name' => 'Zone A - Downtown', 'delivery_fee' => 50.00],
            ['zone_name' => 'Zone B - Suburbs', 'delivery_fee' => 80.00],
            ['zone_name' => 'Zone C - Outskirts', 'delivery_fee' => 120.00],
            ['zone_name' => 'Zone D - Mall Area', 'delivery_fee' => 60.00],
            ['zone_name' => 'Zone E - Harbor', 'delivery_fee' => 100.00],
        ];

        foreach ($zones as $data) {
            DeliveryZone::firstOrCreate(
                ['zone_name' => $data['zone_name']],
                $data
            );
        }
    }
}

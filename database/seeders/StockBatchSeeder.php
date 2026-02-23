<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\StockBatch;
use Illuminate\Database\Seeder;

class StockBatchSeeder extends Seeder
{
    public function run(): void
    {
        $productIds = Product::pluck('id')->toArray();
        $userIds    = User::pluck('id')->toArray();
        if (empty($productIds) || empty($userIds)) {
            return;
        }

        $suppliers = ['Metro Coffee Supply', 'Fresh Dairy Co', 'Barista Direct', 'Cafe Wholesale', 'Local Roasters'];
        $locations = ['Cold-1', 'Dry-A', 'Freezer-2', 'Shelving-B'];

        foreach ($productIds as $productId) {
            $batchCount = rand(1, 3);
            for ($i = 0; $i < $batchCount; $i++) {
                $qty = rand(20, 80);
                $used = rand(0, (int) ($qty * 0.35));
                $prepared = now()->subDays(rand(1, 14));
                $expiry = (clone $prepared)->addDays(rand(14, 60));
                StockBatch::create([
                    'product_id'          => $productId,
                    'quantity'            => $qty,
                    'remaining_quantity'  => $qty - $used,
                    'supplier'            => $suppliers[array_rand($suppliers)],
                    'reference_no'        => 'INV-' . rand(10000, 99999),
                    'unit_cost'           => rand(15, 120),
                    'storage_location'    => $locations[array_rand($locations)],
                    'notes'               => rand(0, 3) === 0 ? 'Rush delivery' : null,
                    'prepared_date'       => $prepared,
                    'expiry_date'         => $expiry,
                    'prepared_by'         => $userIds[array_rand($userIds)],
                ]);
            }
        }
    }
}

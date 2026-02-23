<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StockBatch;
use App\Models\User;
use App\Models\WasteLog;
use Illuminate\Database\Seeder;

class WasteLogSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $userIds  = User::pluck('id')->toArray();
        if ($products->isEmpty() || empty($userIds)) {
            return;
        }

        $reasons = ['expired', 'spoiled', 'damaged', 'preparation_waste', 'other'];
        $totalLogs = min(50, (int) ($products->count() * 2));

        for ($i = 0; $i < $totalLogs; $i++) {
            $product = $products->random();
            $batches = StockBatch::where('product_id', $product->id)->pluck('id')->toArray();
            WasteLog::create([
                'product_id'   => $product->id,
                'batch_id'     => $batches ? $batches[array_rand($batches)] : null,
                'quantity'    => rand(1, 4),
                'reason'      => $reasons[array_rand($reasons)],
                'recorded_by' => $userIds[array_rand($userIds)],
                'date'        => now()->subDays(rand(0, 21)),
            ]);
        }
    }
}

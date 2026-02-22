<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\WasteLog;
use Illuminate\Database\Seeder;

class WasteLogSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::orderBy('id')->limit(5)->pluck('id')->toArray();
        $users = User::orderBy('id')->limit(5)->pluck('id')->toArray();
        $reasons = ['Expired', 'Damaged', 'Spillage', 'Quality issue', 'Overprepared'];

        for ($i = 0; $i < 5; $i++) {
            WasteLog::create([
                'product_id' => $products[$i],
                'quantity' => $i + 1,
                'reason' => $reasons[$i],
                'recorded_by' => $users[$i],
                'date' => now()->subDays($i),
            ]);
        }
    }
}

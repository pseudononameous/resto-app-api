<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $names = ['Downtown Cafe', 'Mall Branch', 'Airport Kiosk', 'Central Kitchen', 'Express Outlet'];
        foreach ($names as $name) {
            Store::firstOrCreate(['name' => $name], ['active' => true]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $names = ['Downtown Branch', 'Mall Branch', 'Airport Branch', 'Metro Branch', 'Harbor Branch'];

        foreach ($names as $name) {
            Store::firstOrCreate(['name' => $name], ['active' => true]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $names = ['Local Fresh', 'Chef Select', 'Premium Choice', 'House Brand', 'Import Deluxe'];

        foreach ($names as $name) {
            Brand::firstOrCreate(['name' => $name], ['active' => true]);
        }
    }
}

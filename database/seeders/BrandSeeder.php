<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $names = ['Lavazza', 'Monin', 'Barista Choice', 'Cafe House', 'Local Roasters'];
        foreach ($names as $name) {
            Brand::firstOrCreate(['name' => $name], ['active' => true]);
        }
    }
}

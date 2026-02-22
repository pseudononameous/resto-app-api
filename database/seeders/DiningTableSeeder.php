<?php

namespace Database\Seeders;

use App\Models\DiningTable;
use App\Models\TableSection;
use Illuminate\Database\Seeder;

class DiningTableSeeder extends Seeder
{
    public function run(): void
    {
        $sections = TableSection::orderBy('id')->limit(5)->pluck('id')->toArray();
        $statuses = ['available', 'occupied', 'reserved', 'available', 'cleaning'];

        for ($i = 1; $i <= 5; $i++) {
            DiningTable::firstOrCreate(
                ['table_number' => (string) $i],
                [
                    'table_number' => (string) $i,
                    'capacity' => 2 + $i,
                    'section_id' => $sections[$i - 1],
                    'status' => $statuses[$i - 1],
                ]
            );
        }
    }
}

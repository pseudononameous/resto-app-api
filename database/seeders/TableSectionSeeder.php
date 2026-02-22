<?php

namespace Database\Seeders;

use App\Models\TableSection;
use Illuminate\Database\Seeder;

class TableSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = ['Main Hall', 'Patio', 'Private Room A', 'Bar Area', 'Garden'];

        foreach ($sections as $name) {
            TableSection::firstOrCreate(['section_name' => $name]);
        }
    }
}

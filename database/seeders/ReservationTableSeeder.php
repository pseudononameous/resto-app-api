<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\DiningTable;
use App\Models\ReservationTable;
use Illuminate\Database\Seeder;

class ReservationTableSeeder extends Seeder
{
    public function run(): void
    {
        $reservations = Reservation::orderBy('id')->limit(5)->pluck('id')->toArray();
        $tables = DiningTable::orderBy('id')->limit(5)->pluck('id')->toArray();

        for ($i = 0; $i < 5; $i++) {
            ReservationTable::firstOrCreate(
                [
                    'reservation_id' => $reservations[$i],
                    'table_id' => $tables[$i],
                ]
            );
        }
    }
}

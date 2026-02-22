<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::orderBy('id')->limit(5)->pluck('id')->toArray();
        $users = User::orderBy('id')->limit(5)->pluck('id')->toArray();

        $names = ['Maria Santos', 'Juan Dela Cruz', 'Ana Reyes', 'Pedro Garcia', 'Sofia Lopez'];
        $phones = ['+639171001001', '+639171001002', '+639171001003', '+639171001004', '+639171001005'];
        $statuses = ['confirmed', 'pending', 'seated', 'completed', 'pending'];

        for ($i = 0; $i < 5; $i++) {
            Reservation::firstOrCreate(
                ['reservation_code' => 'RES-' . str_pad((string) ($i + 1), 3, '0')],
                [
                    'reservation_code' => 'RES-' . str_pad((string) ($i + 1), 3, '0'),
                    'customer_id' => $customers[$i],
                    'guest_name' => $names[$i],
                    'phone' => $phones[$i],
                    'party_size' => 2 + $i,
                    'reservation_date' => now()->addDays($i)->toDateString(),
                    'reservation_time' => sprintf('%02d:00:00', 18 + $i),
                    'status' => $statuses[$i],
                    'created_by' => $users[$i],
                ]
            );
        }
    }
}

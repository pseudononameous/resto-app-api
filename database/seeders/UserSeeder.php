<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Admin User', 'email' => 'admin@resto.app', 'password' => Hash::make('password')],
            ['name' => 'Manager One', 'email' => 'manager1@resto.app', 'password' => Hash::make('password')],
            ['name' => 'Manager Two', 'email' => 'manager2@resto.app', 'password' => Hash::make('password')],
            ['name' => 'Staff Jane', 'email' => 'staff1@resto.app', 'password' => Hash::make('password')],
            ['name' => 'Staff John', 'email' => 'staff2@resto.app', 'password' => Hash::make('password')],
            ['name' => 'Staff Mike', 'email' => 'staff3@resto.app', 'password' => Hash::make('password')],
            ['name' => 'Staff Lisa', 'email' => 'staff4@resto.app', 'password' => Hash::make('password')],
            ['name' => 'Receiving Alex', 'email' => 'receiving@resto.app', 'password' => Hash::make('password')],
            ['name' => 'Inventory Sam', 'email' => 'inventory@resto.app', 'password' => Hash::make('password')],
        ];
        foreach ($users as $data) {
            User::firstOrCreate(['email' => $data['email']], $data);
        }
    }
}

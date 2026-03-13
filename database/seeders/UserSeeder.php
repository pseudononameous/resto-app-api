<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure all core roles exist up front
        $roleSlugs = [
            'super-admin',
            'admin',
            'manager',
            'staff',
            'merchant',
            'kitchen',
            'partners',
            'standard',
        ];

        $roles = [];
        foreach ($roleSlugs as $slug) {
            $roles[$slug] = Role::firstOrCreate(
                ['slug' => $slug],
                ['name' => ucwords(str_replace('-', ' ', $slug)), 'description' => ucfirst($slug) . ' role']
            );
        }

        // Seed a few well-known accounts for convenience
        $seedUsers = [
            ['name' => 'Super Admin', 'email' => 'superadmin@resto.app', 'role' => 'super-admin'],
            ['name' => 'Admin User', 'email' => 'admin@resto.app', 'role' => 'admin'],
            ['name' => 'Manager One', 'email' => 'manager1@resto.app', 'role' => 'manager'],
            ['name' => 'Kitchen Staff', 'email' => 'kitchen@resto.app', 'role' => 'kitchen'],
            ['name' => 'Merchant Owner', 'email' => 'merchant@resto.app', 'role' => 'merchant'],
            ['name' => 'Partner Account', 'email' => 'partner@resto.app', 'role' => 'partners'],
            ['name' => 'Standard Customer', 'email' => 'customer@resto.app', 'role' => 'standard'],
        ];

        $allUsers = [];

        foreach ($seedUsers as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make('password'),
                ]
            );
            $allUsers[] = ['user' => $user, 'role' => $data['role']];
        }

        // Create additional users so that we reach a total of 50, cycling through roles
        $currentCount = count($allUsers);
        for ($i = $currentCount; $i < 50; $i++) {
            $roleSlug = $roleSlugs[$i % count($roleSlugs)];
            $name = ucwords(str_replace('-', ' ', $roleSlug)) . ' User ' . ($i + 1);
            $email = $roleSlug . ($i + 1) . '@resto.app';

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make('password'),
                ]
            );

            $allUsers[] = ['user' => $user, 'role' => $roleSlug];
        }

        // For every user, ensure a matching Customer and attach the appropriate role
        foreach ($allUsers as $entry) {
            /** @var \App\Models\User $user */
            $user = $entry['user'];
            $roleSlug = $entry['role'];

            Customer::firstOrCreate(
                ['email' => $user->email],
                [
                    'customer_code' => 'CUS-' . str_pad((string) $user->id, 4, '0', STR_PAD_LEFT),
                    'first_name' => $user->name,
                    'last_name' => '',
                    // Customers.phone is required & unique, so generate a synthetic unique phone per user
                    'phone' => '+1000000' . str_pad((string) $user->id, 4, '0', STR_PAD_LEFT),
                    'marketing_opt_in' => false,
                ]
            );

            $role = $roles[$roleSlug];
            $user->roles()->syncWithoutDetaching([$role->id]);
        }
    }
}

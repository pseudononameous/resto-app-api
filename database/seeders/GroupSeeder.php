<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            ['group_name' => 'Super Admin', 'permission' => '["all"]'],
            ['group_name' => 'Manager', 'permission' => '["orders","menu","reports"]'],
            ['group_name' => 'Staff', 'permission' => '["orders","tables"]'],
            ['group_name' => 'Cashier', 'permission' => '["orders","payments"]'],
            ['group_name' => 'Kitchen', 'permission' => '["orders","kitchen"]'],
        ];

        foreach ($groups as $data) {
            Group::firstOrCreate(
                ['group_name' => $data['group_name']],
                $data
            );
        }
    }
}

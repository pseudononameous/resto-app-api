<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserGroup;
use App\Models\Group;
use Illuminate\Database\Seeder;

class UserGroupSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::orderBy('id')->limit(5)->pluck('id')->toArray();
        $groups = Group::orderBy('id')->limit(5)->pluck('id')->toArray();

        for ($i = 0; $i < 5; $i++) {
            UserGroup::firstOrCreate(
                [
                    'user_id' => $users[$i],
                    'group_id' => $groups[$i],
                ]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use App\Models\Customer;
use App\Models\NotificationRecipient;
use Illuminate\Database\Seeder;

class NotificationRecipientSeeder extends Seeder
{
    public function run(): void
    {
        $notifications = Notification::orderBy('id')->limit(5)->pluck('id')->toArray();
        $users = User::orderBy('id')->limit(5)->pluck('id')->toArray();
        $customers = Customer::orderBy('id')->limit(5)->pluck('id')->toArray();

        for ($i = 0; $i < 5; $i++) {
            NotificationRecipient::firstOrCreate(
                [
                    'notification_id' => $notifications[$i],
                    'user_id' => $users[$i],
                ],
                [
                    'notification_id' => $notifications[$i],
                    'user_id' => $users[$i],
                    'customer_id' => $customers[$i],
                    'is_read' => (bool) ($i % 2),
                ]
            );
        }
    }
}

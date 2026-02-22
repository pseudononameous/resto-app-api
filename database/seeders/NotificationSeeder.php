<?php

namespace Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $notifications = [
            ['title' => 'Order Confirmed', 'message' => 'Your order #BILL001 has been confirmed.', 'notification_type' => 'order', 'reference_type' => 'order', 'reference_id' => 1],
            ['title' => 'Reservation Reminder', 'message' => 'Your table reservation is in 1 hour.', 'notification_type' => 'reservation', 'reference_type' => 'reservation', 'reference_id' => 2],
            ['title' => 'Delivery Update', 'message' => 'Your order is out for delivery.', 'notification_type' => 'delivery', 'reference_type' => 'delivery', 'reference_id' => 3],
            ['title' => 'Points Earned', 'message' => 'You earned 50 loyalty points.', 'notification_type' => 'loyalty', 'reference_type' => 'order', 'reference_id' => 4],
            ['title' => 'Promo Alert', 'message' => '20% off on all desserts today!', 'notification_type' => 'promo', 'reference_type' => null, 'reference_id' => null],
        ];

        foreach ($notifications as $data) {
            Notification::create($data);
        }
    }
}

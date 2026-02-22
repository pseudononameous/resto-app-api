<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            StoreSeeder::class,
            GroupSeeder::class,
            UserGroupSeeder::class,
            CustomerSeeder::class,
            CustomerAddressSeeder::class,
            CustomerLoyaltySeeder::class,
            CustomerLoyaltyLogSeeder::class,
            ProductSeeder::class,
            StockBatchSeeder::class,
            StockMovementSeeder::class,
            WasteLogSeeder::class,
            MenuCategorySeeder::class,
            MenuItemSeeder::class,
            MenuItemVariantSeeder::class,
            MenuItemAddonSeeder::class,
            ComboMealSeeder::class,
            ComboItemSeeder::class,
            CartSeeder::class,
            CartItemSeeder::class,
            DeliveryZoneSeeder::class,
            DeliveryAddressSeeder::class,
            TableSectionSeeder::class,
            DiningTableSeeder::class,
            ReservationSeeder::class,
            ReservationTableSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            DeliverySeeder::class,
            NotificationSeeder::class,
            NotificationRecipientSeeder::class,
        ]);
    }
}

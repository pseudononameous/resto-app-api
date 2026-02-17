<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('address_id')->constrained('delivery_addresses')->cascadeOnDelete();
            $table->foreignId('zone_id')->nullable()->constrained('delivery_zones')->nullOnDelete();
            $table->foreignId('rider_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['pending', 'out_for_delivery', 'delivered', 'cancelled'])->default('pending');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};

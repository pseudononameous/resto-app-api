<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name', 150)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('address_line', 255)->nullable();
            $table->string('city', 100)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_addresses');
    }
};

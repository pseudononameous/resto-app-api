<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->string('zone_name', 100)->nullable();
            $table->decimal('delivery_fee', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_zones');
    }
};

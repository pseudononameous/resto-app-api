<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_types', function (Blueprint $table) {
            $table->id();
            $table->string('type_name', 50);
        });

        DB::table('order_types')->insert([
            ['type_name' => 'dine-in'],
            ['type_name' => 'pickup'],
            ['type_name' => 'delivery'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('order_types');
    }
};

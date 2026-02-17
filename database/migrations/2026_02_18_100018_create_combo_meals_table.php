<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('combo_meals', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->boolean('active')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('combo_meals');
    }
};

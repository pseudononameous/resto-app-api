<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_item_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained('menu_items')->cascadeOnDelete();
            $table->string('variant_name', 50)->nullable();
            $table->decimal('price_adjustment', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_item_variants');
    }
};

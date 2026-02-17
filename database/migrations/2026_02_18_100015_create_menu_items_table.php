<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('menu_category_id')->nullable()->constrained('menu_categories')->nullOnDelete();
            $table->string('display_name', 150)->nullable();
            $table->decimal('base_price', 10, 2)->nullable();
            $table->boolean('is_available')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};

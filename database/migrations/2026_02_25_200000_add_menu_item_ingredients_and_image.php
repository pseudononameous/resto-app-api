<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->string('image_path', 500)->nullable()->after('base_price');
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
        });
        Schema::create('menu_item_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained('menu_items')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->decimal('quantity_per_serving', 12, 4)->default(1);
            $table->unique(['menu_item_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_item_ingredients');
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('image_path');
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
        });
    }
};

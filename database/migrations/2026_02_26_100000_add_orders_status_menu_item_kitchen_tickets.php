<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status', 30)->default('pending')->after('paid_status');
        });
        Schema::table('orders_item', function (Blueprint $table) {
            $table->foreignId('menu_item_id')->nullable()->after('order_id')->constrained('menu_items')->nullOnDelete();
        });
        Schema::table('orders_item', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE orders_item MODIFY product_id BIGINT UNSIGNED NULL');
        Schema::table('orders_item', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
        });
        Schema::create('kitchen_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('ticket_number', 50);
            $table->string('status', 30)->default('pending');
        });
    }

    public function down(): void
    {
        Schema::table('orders', fn (Blueprint $t) => $t->dropColumn('status'));
        Schema::dropIfExists('kitchen_tickets');
        Schema::table('orders_item', function (Blueprint $table) {
            $table->dropForeign(['menu_item_id']);
            $table->dropForeign(['product_id']);
        });
        Schema::table('orders_item', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
        });
    }
};

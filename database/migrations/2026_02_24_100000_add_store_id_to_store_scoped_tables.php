<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_categories', fn (Blueprint $t) => $t->foreignId('store_id')->nullable()->after('id')->constrained('stores')->nullOnDelete());
        Schema::table('menu_items', fn (Blueprint $t) => $t->foreignId('store_id')->nullable()->after('id')->constrained('stores')->nullOnDelete());
        Schema::table('orders', fn (Blueprint $t) => $t->foreignId('store_id')->nullable()->after('id')->constrained('stores')->nullOnDelete());
        Schema::table('carts', fn (Blueprint $t) => $t->foreignId('store_id')->nullable()->after('id')->constrained('stores')->nullOnDelete());
        Schema::table('reservations', fn (Blueprint $t) => $t->foreignId('store_id')->nullable()->after('id')->constrained('stores')->nullOnDelete());
    }

    public function down(): void
    {
        Schema::table('menu_categories', fn (Blueprint $t) => $t->dropConstrainedForeignId('store_id'));
        Schema::table('menu_items', fn (Blueprint $t) => $t->dropConstrainedForeignId('store_id'));
        Schema::table('orders', fn (Blueprint $t) => $t->dropConstrainedForeignId('store_id'));
        Schema::table('carts', fn (Blueprint $t) => $t->dropConstrainedForeignId('store_id'));
        Schema::table('reservations', fn (Blueprint $t) => $t->dropConstrainedForeignId('store_id'));
    }
};

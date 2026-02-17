<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('cart_code', 50)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('table_number', 20)->nullable();
            $table->enum('status', ['active', 'checked_out', 'cancelled'])->default('active');
            $table->decimal('total', 10, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};

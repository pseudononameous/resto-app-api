<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('bill_no', 50)->nullable();
            $table->foreignId('order_type_id')->constrained('order_types');
            $table->unsignedBigInteger('reservation_id')->nullable();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->dateTime('date_time')->nullable();
            $table->decimal('net_amount', 10, 2)->nullable();
            $table->boolean('paid_status')->default(false);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

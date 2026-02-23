<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_batches', function (Blueprint $table) {
            $table->integer('remaining_quantity')->nullable()->after('quantity');
            $table->string('supplier', 150)->nullable()->after('remaining_quantity');
            $table->string('reference_no', 100)->nullable()->after('supplier');
            $table->decimal('unit_cost', 12, 2)->nullable()->after('reference_no');
            $table->string('storage_location', 100)->nullable()->after('unit_cost');
            $table->string('notes', 500)->nullable()->after('storage_location');
        });
        \Illuminate\Support\Facades\DB::table('stock_batches')->update(['remaining_quantity' => \Illuminate\Support\Facades\DB::raw('COALESCE(remaining_quantity, quantity)')]);
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->foreignId('batch_id')->nullable()->after('product_id')->constrained('stock_batches')->nullOnDelete();
            $table->string('notes', 500)->nullable()->after('reference_id');
        });
        Schema::table('waste_logs', function (Blueprint $table) {
            $table->foreignId('batch_id')->nullable()->after('product_id')->constrained('stock_batches')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('waste_logs', function (Blueprint $table) {
            $table->dropForeign(['batch_id']);
        });
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropForeign(['batch_id']);
            $table->dropColumn(['batch_id', 'notes']);
        });
        Schema::table('stock_batches', function (Blueprint $table) {
            $table->dropColumn(['remaining_quantity', 'supplier', 'reference_no', 'unit_cost', 'storage_location', 'notes']);
        });
    }
};

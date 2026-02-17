<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('table_number', 20)->nullable();
            $table->integer('capacity')->default(2);
            $table->foreignId('section_id')->nullable()->constrained('table_sections')->nullOnDelete();
            $table->enum('status', ['available', 'occupied', 'reserved', 'cleaning'])->default('available');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};

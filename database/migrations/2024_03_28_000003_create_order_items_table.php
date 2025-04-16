<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('dish_id')->constrained('dishes')->onDelete('restrict');
            $table->integer('quantity');
            $table->boolean('has_cheese')->default(false);
            $table->decimal('price', 8, 2);
            $table->string('dish_name')->nullable(); // Store dish name at time of order
            $table->timestamps();
            $table->softDeletes(); // Handle soft deletes
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

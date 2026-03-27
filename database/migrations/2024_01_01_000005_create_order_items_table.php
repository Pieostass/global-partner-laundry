<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Mirrors Java entity: order_items table
        // (Java also had an 'order_details' table — we consolidate into one: order_items)
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->cascadeOnDelete();   // mirrors CascadeType.ALL + orphanRemoval
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->restrictOnDelete(); // keep product record; don't cascade
            $table->integer('quantity')->notNull();
            // Price snapshot at time of purchase — mirrors Java "price" field comment
            $table->decimal('price', 12, 2)->notNull();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

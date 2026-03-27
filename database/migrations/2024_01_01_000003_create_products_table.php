<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200)->notNull();
            $table->text('description')->nullable();
            // precision=12, scale=2 — mirrors Java @Column(precision=12, scale=2)
            $table->decimal('price', 12, 2)->notNull();
            $table->integer('stock_quantity')->default(0);
            $table->string('image_url')->nullable();
            $table->boolean('active')->default(true);
            // FK → categories (nullable: product can exist without category)
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('categories')
                  ->nullOnDelete();
            $table->timestamps();                           // created_at + updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

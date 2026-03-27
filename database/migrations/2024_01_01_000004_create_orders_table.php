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
            // FK → users — keep order history even if user is deleted (nullOnDelete)
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            // Snapshot fields — stored at order time, not pulled from user record
            $table->string('full_name', 150)->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->text('note')->nullable();
            // Maps to Java OrderStatus enum stored as string
            $table->enum('status', [
                'PENDING',
                'CONFIRMED',
                'PROCESSING',
                'DELIVERING',
                'DELIVERED',
                'DONE',
                'CANCELLED',
            ])->default('PENDING');
            // precision=12, scale=2
            $table->decimal('total_price', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

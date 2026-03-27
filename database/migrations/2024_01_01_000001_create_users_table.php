<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique()->notNull();
            $table->string('email', 150)->unique()->notNull();
            $table->string('password')->notNull();          // BCrypt hash
            $table->string('full_name', 150)->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            // Role: ROLE_USER | ROLE_STAFF | ROLE_ADMIN
            $table->enum('role', ['ROLE_USER', 'ROLE_STAFF', 'ROLE_ADMIN'])
                  ->default('ROLE_USER');
            $table->boolean('active')->default(true);       // maps to Java 'enabled'
            $table->rememberToken();
            $table->timestamps();                           // created_at + updated_at
        });

        // Laravel Breeze password reset support
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};

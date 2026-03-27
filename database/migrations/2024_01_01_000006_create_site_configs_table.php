<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Java entity used String configKey as @Id — Laravel maps this to a string PK
        Schema::create('site_configs', function (Blueprint $table) {
            $table->string('config_key', 100)->primary();
            $table->text('config_value')->nullable();
            $table->string('description', 200)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_configs');
    }
};

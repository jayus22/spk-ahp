<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laptops', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->string('processor');
            $table->string('ram');
            $table->string('storage');
            $table->string('gpu')->nullable();
            $table->decimal('display_size', 4, 1); // in inches
            $table->string('resolution')->nullable();
            $table->decimal('weight', 5, 2)->nullable(); // in kg
            $table->integer('battery_life')->nullable(); // in hours
            $table->decimal('price', 12, 2);
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laptops');
    }
};
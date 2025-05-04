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
        Schema::create('laptop_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laptop_id')->constrained()->onDelete('cascade');
            $table->foreignId('criteria_id')->constrained()->onDelete('cascade');
            $table->decimal('value', 10, 4); // The normalized score (0-1)
            $table->decimal('raw_value', 12, 2)->nullable(); // The original value before normalization
            $table->timestamps();
            
            // Each laptop should have only one score per criteria
            $table->unique(['laptop_id', 'criteria_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laptop_scores');
    }
};
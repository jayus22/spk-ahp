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
        Schema::create('criteria_weights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comparison_id')->constrained()->onDelete('cascade');
            $table->foreignId('criteria_id')->constrained()->onDelete('cascade');
            $table->float('weight', 8, 6)->default(0); // Normalized priority weight (0-1)
            $table->float('raw_weight', 8, 6)->nullable(); // Original weight value
            $table->json('comparison_data')->nullable(); // Store pairwise comparison data
            $table->timestamps();
            
            // Ensure unique combination of comparison_id and criteria_id
            $table->unique(['comparison_id', 'criteria_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criteria_weights');
    }
};
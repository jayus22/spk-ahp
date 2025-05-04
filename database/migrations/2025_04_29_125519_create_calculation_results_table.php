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
        Schema::create('calculation_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comparison_id')->unique()->constrained()->onDelete('cascade');
            $table->json('result_data'); // Stores the final scores for each laptop
            $table->float('consistency_ratio', 8, 6)->nullable();
            $table->boolean('is_consistent')->default(true);
            $table->foreignId('best_laptop_id')->nullable()->constrained('laptops')->nullOnDelete();
            $table->json('calculation_details')->nullable(); // Detailed calculation steps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calculation_results');
    }
};
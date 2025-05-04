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
        Schema::create('comparisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('criteria1_id')->constrained('criteria')->onDelete('cascade');
            $table->foreignId('criteria2_id')->constrained('criteria')->onDelete('cascade');
            $table->decimal('value', 5, 2); // The comparison value (1/9 to 9)
            $table->timestamps();
            
            // Each pair of criteria should only have one comparison per user
            $table->unique(['user_id', 'criteria1_id', 'criteria2_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comparisons');
    }
};
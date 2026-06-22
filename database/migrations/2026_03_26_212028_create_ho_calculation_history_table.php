<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ho_calculation_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable();
            $table->json('input_data');
            $table->json('results')->nullable();
            $table->json('optimization_results')->nullable();
            $table->json('optimized_input_data')->nullable();
            $table->json('experimental_data')->nullable();
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('is_favorite');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ho_calculation_history');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alternative_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alternative_id')->constrained('alternatives')->onDelete('cascade');
            $table->foreignId('criterion_id')->constrained('criteria')->onDelete('cascade');
            $table->double('value'); // numeric value for evaluation
            $table->timestamps();

            $table->unique(['alternative_id', 'criterion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alternative_criteria');
    }
};

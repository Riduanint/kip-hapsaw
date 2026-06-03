<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('criteria_comparisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criteria_id_1')->constrained('criteria')->onDelete('cascade');
            $table->foreignId('criteria_id_2')->constrained('criteria')->onDelete('cascade');
            $table->double('value');
            $table->timestamps();

            $table->unique(['criteria_id_1', 'criteria_id_2']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('criteria_comparisons');
    }
};

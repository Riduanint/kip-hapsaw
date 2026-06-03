<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alternatives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('name');
            $table->string('nisn')->unique();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('status')->default('pending'); // pending, selected, not_selected
            $table->double('final_score')->nullable();
            $table->integer('rank')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alternatives');
    }
};

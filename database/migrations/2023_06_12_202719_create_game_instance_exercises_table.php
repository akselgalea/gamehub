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
        Schema::create('game_instance_exercises', function (Blueprint $table) {
            $table->id();
            $table->integer('event');
            $table->integer('round')->nullable();
            $table->text('exercise')->nullable();
            $table->text('user_response')->nullable();
            $table->text('response')->nullable();
            $table->timestamp('time_start')->nullable();
            $table->timestamp('time_end')->nullable();
            $table->json('extra')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('game_instance_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_instance_excercises');
    }
};

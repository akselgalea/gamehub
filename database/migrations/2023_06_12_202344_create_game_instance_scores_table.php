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
        Schema::create('game_instance_scores', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('max_score')->unsigned()->default(0);
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
        Schema::dropIfExists('game_instance_scores');
    }
};

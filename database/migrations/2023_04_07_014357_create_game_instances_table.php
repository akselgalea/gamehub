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
        Schema::create('game_instances', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->foreignId('game_id')->nullable()->nullOnDelete();
            $table->foreignId('experiment_id')->onDelete('cascade');
            $table->boolean('enable_rewards')->default(false);
            $table->boolean('enable_badges')->default(false);
            $table->boolean('enable_performance_chart')->default(false);
            $table->boolean('enable_leaderboard')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_instances');
    }
};

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
        Schema::create('game_instance_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('value');
            $table->foreignId('parameter_id')->constrained()->onDelete('cascade');
            $table->foreignId('game_instance_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_instance_parameters');
    }
};

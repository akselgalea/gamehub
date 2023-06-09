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
        Schema::create('entry_points', function (Blueprint $table) {
            $table->id();
            $table->string('token');
            $table->string('name');
            $table->string('description');
            $table->boolean('obfuscated');
            $table->foreignId('experiment_id');
            //$table->foreignId('course_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entry_points');
    }
};
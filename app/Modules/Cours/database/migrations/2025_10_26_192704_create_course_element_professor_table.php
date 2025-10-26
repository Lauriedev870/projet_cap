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
        Schema::create('course_element_professor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_element_id')->constrained('course_elements')->onDelete('cascade');
            $table->foreignId('professor_id')->constrained('professors')->onDelete('cascade');
            $table->timestamps();

            // Contrainte unique pour éviter les doublons
            $table->unique(['course_element_id', 'professor_id']);

            // Index
            $table->index('course_element_id');
            $table->index('professor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_element_professor');
    }
};

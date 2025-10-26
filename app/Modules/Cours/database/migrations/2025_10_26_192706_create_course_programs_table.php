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
        Schema::create('course_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_group_id')->constrained('class_groups')->onDelete('cascade');
            $table->foreignId('course_element_professor_id')->constrained('course_element_professor')->onDelete('cascade');
            $table->json('weighting')->comment('Pondération des évaluations');
            $table->timestamps();

            // Index
            $table->index('class_group_id');
            $table->index('course_element_professor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_programs');
    }
};

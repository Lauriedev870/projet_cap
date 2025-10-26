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
        Schema::create('student_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_group_id')->constrained('class_groups')->onDelete('cascade');
            $table->foreignId('student_pending_student_id')->constrained('student_pending_students')->onDelete('cascade');
            $table->timestamps();

            // Contrainte unique pour éviter qu'un étudiant soit dans le même groupe plusieurs fois
            $table->unique(['class_group_id', 'student_pending_student_id'], 'unique_student_in_group');

            // Index
            $table->index('class_group_id');
            $table->index('student_pending_student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_groups');
    }
};

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
        if (!Schema::hasTable('academic_paths')) {
            Schema::create('academic_paths', function (Blueprint $table) {
                $table->id();
                $table->uuid()->unique();
                $table->foreignId('student_pending_student_id')->constrained()->onDelete('cascade');
                $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
                $table->string('study_level');
                $table->string('year_decision');
                $table->foreignId('role_id')->constrained()->onDelete('cascade');
                $table->string('financial_status')->default('full');
                $table->integer('cohort')->default(1);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_paths');
    }
};

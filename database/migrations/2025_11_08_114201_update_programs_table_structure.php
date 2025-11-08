<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Supprime et recrée la table programs avec la bonne structure
     * course_element_professor_id référence l'ID de la table pivot course_element_professor
     */
    public function up(): void
    {
        // Supprimer la table existante
        Schema::dropIfExists('programs');
        
        // Recréer la table avec la bonne structure
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            $table->foreignId('class_group_id')
                ->constrained('class_groups')
                ->onDelete('cascade');
                
            $table->foreignId('course_element_professor_id')
                ->constrained('course_element_professor')
                ->onDelete('cascade')
                ->comment('Référence à la table pivot course_element_professor');
                
            $table->json('weighting')
                ->nullable()
                ->comment('Pondération des évaluations (CC, TP, Examen, etc.)');
            
            $table->timestamps();
            
            // Indexes
            $table->index('class_group_id');
            $table->index('course_element_professor_id');
            $table->unique(['class_group_id', 'course_element_professor_id'], 'program_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer la nouvelle table
        Schema::dropIfExists('programs');
        
        // Recréer l'ancienne structure
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('code')->unique();
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->integer('semester');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('code');
            $table->index('department_id');
            $table->index('academic_year_id');
        });
    }
};

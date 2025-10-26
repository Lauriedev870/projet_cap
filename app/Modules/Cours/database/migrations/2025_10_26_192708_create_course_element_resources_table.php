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
        Schema::create('course_element_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_element_id')->constrained('course_elements')->onDelete('cascade');
            $table->foreignId('file_id')->constrained('files')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('resource_type', ['pdf', 'pptx', 'docx', 'video', 'audio', 'other'])->default('other');
            $table->boolean('is_public')->default(false);
            $table->timestamps();

            // Index
            $table->index('course_element_id');
            $table->index('file_id');
            $table->index('resource_type');
            $table->index('is_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_element_resources');
    }
};

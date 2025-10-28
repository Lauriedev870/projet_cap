<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_element_resources', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('course_element_id')->constrained('course_elements')->onDelete('cascade');
            $table->foreignId('file_id')->nullable()->constrained('files')->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('resource_type')->nullable(); // 'document', 'video', 'link', etc.
            $table->boolean('is_public')->default(false);
            $table->timestamps();
            
            $table->index('course_element_id');
            $table->index('file_id');
            $table->index('resource_type');
            $table->index('is_public');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_element_resources');
    }
};

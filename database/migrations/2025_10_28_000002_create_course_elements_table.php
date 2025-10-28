<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_elements', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('code')->unique();
            $table->integer('credits')->default(0);
            $table->foreignId('teaching_unit_id')->nullable()->constrained('teaching_units')->onDelete('set null');
            $table->timestamps();
            
            $table->index('code');
            $table->index('teaching_unit_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_elements');
    }
};

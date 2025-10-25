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
        if (!Schema::hasTable('student_pending_students')) {
            Schema::create('student_pending_students', function (Blueprint $table) {
                $table->id();
                $table->uuid()->unique();
                $table->foreignId('student_id')->constrained()->onDelete('cascade');
                $table->foreignId('pending_student_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_pending_students');
    }
};

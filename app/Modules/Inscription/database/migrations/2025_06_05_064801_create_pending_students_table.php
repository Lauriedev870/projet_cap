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
        if (!Schema::hasTable('pending_students')) {
            Schema::create('pending_students', function (Blueprint $table) {
                $table->id();
                $table->uuid()->unique();
                $table->foreignId('personal_information_id')->constrained('personal_information')->onDelete('cascade');
                $table->string('tracking_code');
                $table->string('cuca_opinion')->nullable();
                $table->text('cuca_comment')->nullable();
                $table->string('cuo_opinion')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->boolean('cuco_mail_sent')->default(false);
                $table->json('documents');
                $table->string('level');
                $table->foreignId('entry_diploma_id')->constrained()->onDelete('cascade');
                $table->string('photo');
                $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
                $table->foreignId('department_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_students');
    }
};

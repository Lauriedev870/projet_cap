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
        if (!Schema::hasTable('personal_information')) {
            Schema::create('personal_information', function (Blueprint $table) {
                $table->id();
                $table->uuid()->unique();
                $table->string('last_name');
                $table->string('first_names');
                $table->string('email')->unique();
                $table->date('birth_date');
                $table->string('birth_place');
                $table->string('birth_country');
                $table->string('gender');
                $table->boolean('estimated_birth')->default(false);
                $table->json('contacts');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_information');
    }
};

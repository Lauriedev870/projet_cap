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
        Schema::create('professors', function (Blueprint $table) {
            $table->id();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password');
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
            $table->string('rib_number')->nullable();
            $table->string('rib')->nullable()->comment('Fichier RIB');
            $table->string('ifu_number')->nullable();
            $table->string('ifu')->nullable()->comment('Fichier IFU');
            $table->string('bank')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->foreignId('grade_id')->nullable()->constrained('grades')->onDelete('set null');
            $table->timestamps();

            // Index
            $table->index('email');
            $table->index('status');
            $table->index('grade_id');
            $table->index(['last_name', 'first_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professors');
    }
};

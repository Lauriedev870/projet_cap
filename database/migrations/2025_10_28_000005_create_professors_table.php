<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('professors')) {
            Schema::create('professors', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->unique();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('specialization')->nullable();
                $table->string('grade')->nullable(); // Grade académique
                $table->timestamps();
                
                $table->index('user_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('professors');
    }
};

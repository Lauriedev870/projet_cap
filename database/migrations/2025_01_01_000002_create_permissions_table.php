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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Nom unique de la permission (ex: files.read)');
            $table->string('display_name')->comment('Nom affiché');
            $table->text('description')->nullable()->comment('Description de la permission');
            $table->string('module')->default('stockage')->comment('Module concerné');
            $table->string('resource')->nullable()->comment('Ressource concernée (ex: files, folders)');
            $table->string('action')->nullable()->comment('Action (ex: read, write, delete)');
            $table->timestamps();

            $table->index(['module', 'resource', 'action']);
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};

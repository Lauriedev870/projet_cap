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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            
            // Propriétaire du fichier
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Informations du fichier
            $table->string('name')->comment('Nom unique généré');
            $table->string('original_name')->comment('Nom original du fichier');
            $table->string('path')->comment('Chemin de stockage');
            $table->string('disk')->default('private_files')->comment('Disk de stockage');
            
            // Visibilité et permissions
            $table->enum('visibility', ['public', 'private'])->default('private')->comment('Visibilité du fichier');
            
            // Module propriétaire
            $table->string('module_name')->nullable()->comment('Nom du module propriétaire');
            $table->string('module_resource_type')->nullable()->comment('Type de ressource du module (ex: invoice, student)');
            $table->unsignedBigInteger('module_resource_id')->nullable()->comment('ID de la ressource du module');
            
            // Métadonnées du fichier
            $table->string('collection')->default('default')->comment('Collection/catégorie');
            $table->unsignedBigInteger('size')->comment('Taille en octets');
            $table->string('mime_type')->comment('Type MIME');
            $table->string('extension', 10)->comment('Extension du fichier');
            $table->string('file_hash', 64)->nullable()->comment('Hash SHA-256 pour intégrité');
            
            // Métadonnées additionnelles
            $table->json('metadata')->nullable()->comment('Métadonnées additionnelles');
            
            // Contrôle d'accès
            $table->boolean('is_locked')->default(false)->comment('Fichier verrouillé');
            $table->timestamp('locked_at')->nullable();
            $table->foreignId('locked_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Statistiques
            $table->unsignedInteger('download_count')->default(0)->comment('Nombre de téléchargements');
            $table->timestamp('last_accessed_at')->nullable()->comment('Dernier accès');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour performances
            $table->index(['user_id', 'visibility']);
            $table->index(['module_name', 'module_resource_type', 'module_resource_id'], 'idx_module_resource');
            $table->index('collection');
            $table->index('file_hash');
            $table->index('visibility');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};

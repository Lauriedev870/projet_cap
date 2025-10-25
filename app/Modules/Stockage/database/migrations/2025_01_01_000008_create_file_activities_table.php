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
        Schema::create('file_activities', function (Blueprint $table) {
            $table->id();
            
            // Fichier concerné
            $table->foreignId('file_id')->constrained('files')->onDelete('cascade');
            
            // Utilisateur qui a effectué l'action
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Type d'activité
            $table->enum('activity_type', [
                'uploaded',
                'downloaded',
                'viewed',
                'updated',
                'deleted',
                'shared',
                'permission_granted',
                'permission_revoked',
                'locked',
                'unlocked',
                'moved',
                'renamed'
            ])->comment('Type d\'activité');
            
            // Détails
            $table->text('description')->nullable()->comment('Description de l\'activité');
            $table->json('metadata')->nullable()->comment('Métadonnées additionnelles');
            
            // Informations de contexte
            $table->string('ip_address', 45)->nullable()->comment('Adresse IP');
            $table->string('user_agent')->nullable()->comment('User agent');
            
            $table->timestamp('created_at')->useCurrent();
            
            // Index pour performances
            $table->index(['file_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index('activity_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_activities');
    }
};

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
        Schema::create('file_shares', function (Blueprint $table) {
            $table->id();
            
            // Fichier partagé
            $table->foreignId('file_id')->constrained('files')->onDelete('cascade');
            
            // Partage
            $table->string('token', 64)->unique()->comment('Token unique de partage');
            $table->string('password_hash')->nullable()->comment('Mot de passe optionnel pour accès');
            
            // Permissions du lien
            $table->boolean('allow_download')->default(true);
            $table->boolean('allow_preview')->default(true);
            
            // Limites
            $table->unsignedInteger('max_downloads')->nullable()->comment('Nombre max de téléchargements');
            $table->unsignedInteger('download_count')->default(0)->comment('Nombre de téléchargements effectués');
            $table->timestamp('expires_at')->nullable()->comment('Date d\'expiration');
            
            // Créateur du partage
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            // Statut
            $table->boolean('is_active')->default(true)->comment('Lien actif');
            
            $table->timestamps();
            
            // Index
            $table->index('token');
            $table->index(['file_id', 'is_active']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_shares');
    }
};

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
        Schema::create('file_permissions', function (Blueprint $table) {
            $table->id();
            
            // Fichier concerné
            $table->foreignId('file_id')->constrained('files')->onDelete('cascade');
            
            // Bénéficiaire de la permission (utilisateur OU rôle, pas les deux)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')
                ->comment('Utilisateur avec accès (si permission individuelle)');
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('cascade')
                ->comment('Rôle avec accès (si permission par rôle)');
            
            // Type de permission
            $table->enum('permission_type', ['read', 'write', 'delete', 'share', 'admin'])
                ->default('read')
                ->comment('Type de permission accordée');
            
            // Métadonnées de la permission
            $table->foreignId('granted_by')->nullable()->constrained('users')->onDelete('set null')
                ->comment('Utilisateur qui a accordé la permission');
            $table->timestamp('granted_at')->useCurrent()->comment('Date d\'octroi');
            $table->timestamp('expires_at')->nullable()->comment('Date d\'expiration');
            
            $table->timestamps();

            // Contraintes: soit user_id, soit role_id, mais pas les deux
            // Note: CHECK constraint removed as not supported in Laravel Blueprint. Enforce in application logic.

            // Unicité: un utilisateur/rôle ne peut avoir la même permission qu'une fois
            $table->unique(['file_id', 'user_id', 'permission_type'], 'file_user_permission_unique');
            $table->unique(['file_id', 'role_id', 'permission_type'], 'file_role_permission_unique');
            
            // Index pour performances
            $table->index(['file_id', 'user_id']);
            $table->index(['file_id', 'role_id']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_permissions');
    }
};

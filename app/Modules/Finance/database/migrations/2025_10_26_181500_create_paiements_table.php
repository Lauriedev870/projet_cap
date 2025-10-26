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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->string('matricule', 11);
            $table->float('montant')->nullable();
            $table->string('reference', 255);
            $table->string('numero_compte', 255);
            $table->date('date_versement')->nullable();
            $table->string('quittance', 255)->nullable()->comment('ID du fichier dans la table files');
            $table->text('motif');
            $table->text('observation')->nullable();
            $table->string('email', 255)->nullable();
            $table->enum('statut', ['attente', 'rejete', 'accepte'])->default('attente');
            $table->string('contact', 255)->nullable();
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index('matricule');
            $table->index('statut');
            $table->index('reference');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};

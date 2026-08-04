<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tache 1 — Migration de la table pivot project_user
 *
 * Cette table pivot relie les utilisateurs aux projets avec un rôle spécifique
 * par projet (responsable, chercheur, etudiant_assistant).
 * Règle : un projet doit toujours avoir au moins un responsable.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_user', function (Blueprint $table) {
            $table->id();

            // Clé étrangère vers la table projects
            $table->foreignId('project_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Clé étrangère vers la table users
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Rôle de l'utilisateur dans ce projet précis
            // Valeurs possibles : responsable, chercheur, etudiant_assistant
            $table->enum('role', ['responsable', 'chercheur', 'etudiant_assistant'])
                  ->default('chercheur');

            $table->timestamps();

            // Un utilisateur ne peut appartenir qu'une seule fois à un projet
            $table->unique(['project_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_user');
    }
};

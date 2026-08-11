<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Le rôle d'un utilisateur est propre à chaque projet
     * et stocké dans la table pivot project_user (colonne role).
     * Cette migration ne fait rien volontairement.
     */
    public function up(): void
    {
        // Intentionnellement vide — le rôle est sur la table pivot project_user
    }

    public function down(): void
    {
        // Intentionnellement vide
    }
};
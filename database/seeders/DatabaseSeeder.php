<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * Crée 3 utilisateurs et 2 projets de test avec membres assignés.
     */
    public function run(): void
    {
        // ─── Utilisateurs ────────────────────────────────────────────
        $responsable = User::factory()->create([
            'name'     => 'Alice Responsable',
            'email'    => 'responsable@test.com',
            'password' => bcrypt('password'),
        ]);

        $chercheur = User::factory()->create([
            'name'     => 'Bob Chercheur',
            'email'    => 'chercheur@test.com',
            'password' => bcrypt('password'),
        ]);

        $etudiant = User::factory()->create([
            'name'     => 'Claire Etudiante',
            'email'    => 'etudiant@test.com',
            'password' => bcrypt('password'),
        ]);

        // ─── Projet 1 : en cours avec toute l'équipe ────────────────
        $projet1 = Project::create([
            'title'       => 'Analyse du génome des plantes résistantes',
            'description' => 'Étude comparative de génomes pour identifier les marqueurs de résistance aux pathogènes.',
            'status'      => 'encours',
            'avancement'  => 35,
        ]);

        $projet1->users()->attach($responsable->id, ['role' => 'responsable']);
        $projet1->users()->attach($chercheur->id,   ['role' => 'chercheur']);
        $projet1->users()->attach($etudiant->id,    ['role' => 'etudiant_assistant']);

        // ─── Projet 2 : clôturé, géré par la même responsable ───────
        $projet2 = Project::create([
            'title'       => 'Modélisation climatique du bassin méditerranéen',
            'description' => 'Développement d\'un modèle prédictif pour anticiper les événements climatiques extrêmes.',
            'status'      => 'cloture',
            'avancement'  => 100,
        ]);

        $projet2->users()->attach($responsable->id, ['role' => 'responsable']);
        $projet2->users()->attach($chercheur->id,   ['role' => 'chercheur']);

        // ─── Utilisateurs libres (pas encore dans un projet) ────────
        // Ils apparaîtront dans le select "Ajouter un membre"
        User::factory()->create([
            'name'     => 'Dalil Benali',
            'email'    => 'dalil@test.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name'     => 'Emma Rousseau',
            'email'    => 'emma@test.com',
            'password' => bcrypt('password'),
        ]);
    }
}

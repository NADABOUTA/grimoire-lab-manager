<?php

namespace App\Listeners;

use App\Events\ProjetCloture;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GenererRapportSynthese implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(ProjetCloture $event): void
    {
        $project = $event->project;

        // Génération du rapport de synthèse
        $rapport = "Rapport de synthèse du projet\n\n";
        $rapport .= "Titre : " . $project->title . "\n";
        $rapport .= "Description : " . $project->description . "\n";
        $rapport .= "Statut : " . $project->status . "\n";
        $rapport .= "Avancement : " . $project->avancement . "%\n";
        $rapport .= "Date de clôture : " . now()->format('d/m/Y H:i') . "\n";

        // Création du dossier des rapports s'il n'existe pas
        $dossier = storage_path('app/rapports');

        if (!is_dir($dossier)) {
            mkdir($dossier, 0755, true);
        }

        // Création du fichier rapport
        file_put_contents(
            $dossier . '/rapport-projet-' . $project->id . '.txt',
            $rapport
        );
    }
}
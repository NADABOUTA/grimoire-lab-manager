<?php

namespace App\Listeners;

use App\Events\ProjetCloture;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class GenererRapportProjet implements ShouldQueue
{
    public function handle(ProjetCloture $event): void
    {
        $project = $event->project;

        $rapport = "
Projet : {$project->title}

Description : {$project->description}

Statut : {$project->status}

Avancement : {$project->avancement}%
";

        Storage::disk('public')->put(
            "rapports/projet_{$project->id}.txt",
            $rapport
        );
    }
}
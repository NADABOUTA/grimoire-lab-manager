<?php

namespace App\Events;

use App\Models\Project;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Tache 8 — Event MembreAjouteAuProjet
 *
 * Cet événement est déclenché dès qu'un nouveau membre est ajouté à un projet.
 * Il transporte les données nécessaires au Listener :
 *   - $project : le projet concerné
 *   - $membre  : l'utilisateur qui vient d'être ajouté
 *   - $role    : le rôle attribué (responsable, chercheur, etudiant_assistant)
 *
 * Comment ça marche ?
 *   1. MembreController::store() appelle event(new MembreAjouteAuProjet(...))
 *   2. Laravel regarde dans EventServiceProvider (ou auto-discovery) quel Listener écoute cet Event
 *   3. Le Listener est mis en queue et traité de façon asynchrone
 */
class MembreAjouteAuProjet
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Constructeur — on stocke les données de l'événement
     * pour que le Listener puisse y accéder.
     */
    public function __construct(
        public readonly Project $project,
        public readonly User    $membre,
        public readonly string  $role,
    ) {}
}

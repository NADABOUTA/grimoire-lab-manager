<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Un utilisateur connecté peut consulter la liste
     * des projets auxquels il appartient.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Un utilisateur peut consulter un projet
     * s'il en est membre.
     */
    public function view(User $user, Project $project): bool
    {
        return $project->hasMember($user);
    }

    /**
     * Tout utilisateur connecté peut créer un projet.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Seul le responsable peut modifier les informations générales.
     */
    public function update(User $user, Project $project): bool
    {
        return $project->isResponsable($user);
    }

    /**
     * Seul le responsable peut archiver le projet.
     */
    public function delete(User $user, Project $project): bool
    {
        return $project->isResponsable($user);
    }

    /**
     * Seul le responsable peut restaurer un projet archivé.
     */
    public function restore(User $user, Project $project): bool
    {
        return $project->isResponsable($user);
    }

    /**
     * Suppression définitive.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return $project->isResponsable($user);
    }

    /**
     * Le chercheur peut uniquement modifier l'avancement.
     */
    public function updateAvancement(User $user, Project $project): bool
    {
        return $project->chercheurs()
            ->where('users.id', $user->id)
            ->exists();
    }

    /**
     * Seul le responsable peut consulter les projets archivés.
     */
    public function viewArchived(User $user): bool
    {
        return $user->projetsResponsable()->exists();
    }

    /**
     * Seul le responsable peut ajouter un membre.
     */
    public function addMembre(User $user, Project $project): bool
    {
        return $project->isResponsable($user);
    }

    /**
     * Seul le responsable peut retirer un membre.
     */
    public function removeMembre(User $user, Project $project): bool
    {
        return $project->isResponsable($user);
    }
}

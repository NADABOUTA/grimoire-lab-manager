<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Tout utilisateur connecté peut voir la liste des projets.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Un utilisateur peut consulter un projet s'il en est membre.
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
     * Seul le responsable du projet peut le modifier.
     */
    public function update(User $user, Project $project): bool
    {
        return $project->isResponsable($user);
    }

    /**
     * Seul le responsable du projet peut le supprimer (archiver).
     */
    public function delete(User $user, Project $project): bool
    {
        return $project->isResponsable($user);
    }

    public function restore(User $user, Project $project): bool
    {
        return $project->isResponsable($user);
    }

    public function forceDelete(User $user, Project $project): bool
    {
        return $project->isResponsable($user);
    }

    /**
     * Tache 4 — Seul le responsable peut ajouter un membre.
     */
    public function addMembre(User $user, Project $project): bool
    {
        return $project->isResponsable($user);
    }

    /**
     * Tache 5 — Seul le responsable peut retirer un membre.
     */
    public function removeMembre(User $user, Project $project): bool
    {
        return $project->isResponsable($user);
    }
}
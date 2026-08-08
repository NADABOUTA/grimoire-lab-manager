<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Project $project): bool
    {
        return $project->hasMember($user);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Project $project): bool
    {
        return $project->isResponsable($user);
    }

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

    public function updateAvancement(User $user, Project $project): bool
    {
        return $project->chercheurs()
            ->where('users.id', $user->id)
            ->exists();
    }

    public function viewArchived(User $user): bool
    {
        return $user->projetsResponsable()->exists();
    }

    public function cloturer(User $user, Project $project): bool
    {
        return $project->isResponsable($user)
            && $project->status !== 'cloture';
    }

    public function addMembre(User $user, Project $project): bool
    {
        return $project->isResponsable($user);
    }

    public function removeMembre(User $user, Project $project): bool
    {
        return $project->isResponsable($user);
    }
}
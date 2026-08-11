<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relation Many-to-Many avec les projets.
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class)
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Projets où l'utilisateur est responsable.
     */
    public function projetsResponsable()
    {
        return $this->projects()
                    ->wherePivot('role', 'responsable');
    }

    /**
     * Vérifier si l'utilisateur est responsable d'un projet.
     */
    public function isResponsableOf(Project $project): bool
    {
        return $this->projects()
                    ->where('projects.id', $project->id)
                    ->wherePivot('role', 'responsable')
                    ->exists();
    }

    /**
     * Récupérer le rôle de l'utilisateur dans un projet.
     */
    public function getRoleInProject(Project $project): ?string
    {
        $pivot = $this->projects()
                      ->where('projects.id', $project->id)
                      ->first()?->pivot;

        return $pivot?->role;
    }
}
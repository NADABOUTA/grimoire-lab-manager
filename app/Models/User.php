<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Tache 3 — Relation many-to-many avec Project.
     * withPivot('role') permet d'accéder au rôle depuis la relation.
     * Exemple : $user->projects->first()->pivot->role
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class)
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Récupérer les projets où l'utilisateur est responsable.
     */
    public function projetsResponsable()
    {
        return $this->projects()->wherePivot('role', 'responsable');
    }

    /**
     * Vérifier si l'utilisateur est responsable d'un projet donné.
     */
    public function isResponsableOf(Project $project): bool
    {
        return $this->projects()
                    ->where('projects.id', $project->id)
                    ->wherePivot('role', 'responsable')
                    ->exists();
    }

    /**
     * Récupérer le rôle de l'utilisateur dans un projet donné.
     * Retourne null si l'utilisateur n'est pas membre du projet.
     */
    public function getRoleInProject(Project $project): ?string
    {
        $pivot = $this->projects()
                      ->where('projects.id', $project->id)
                      ->first()?->pivot;

        return $pivot?->role;
    }
}

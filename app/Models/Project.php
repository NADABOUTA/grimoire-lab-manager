<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Tache 2 — Modèle Project avec SoftDeletes et fillable
 * Tache 3 — Relation belongsToMany vers User via la table pivot project_user
 */
class Project extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'avancement',
    ];

    /**
     * Relation many-to-many avec User.
     * withPivot('role') permet d'accéder au rôle depuis la relation.
     * Exemple : $project->users->first()->pivot->role
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Retourner uniquement les responsables du projet.
     */
    public function responsables()
    {
        return $this->users()->wherePivot('role', 'responsable');
    }

    /**
     * Retourner uniquement les chercheurs du projet.
     */
    public function chercheurs()
    {
        return $this->users()->wherePivot('role', 'chercheur');
    }

    /**
     * Retourner uniquement les étudiants assistants du projet.
     */
    public function etudiantsAssistants()
    {
        return $this->users()->wherePivot('role', 'etudiant_assistant');
    }

    /**
     * Vérifier si un utilisateur est responsable de ce projet.
     */
    public function isResponsable(User $user): bool
    {
        return $this->responsables()->where('users.id', $user->id)->exists();
    }

    /**
     * Vérifier si un utilisateur est membre de ce projet (quel que soit le rôle).
     */
    public function hasMember(User $user): bool
    {
        return $this->users()->where('users.id', $user->id)->exists();
    }
}
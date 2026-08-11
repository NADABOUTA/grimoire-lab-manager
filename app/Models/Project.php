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
     *
     * Utilise la relation users déjà chargée pour éviter
     * les requêtes SQL répétées.
     */
    public function isResponsable(User $user): bool
    {
        $this->loadMissing('users');

        return $this->users->contains(function ($membre) use ($user) {
            return $membre->id === $user->id
                && $membre->pivot->role === 'responsable';
        });
    }

    /**
     * Vérifier si un utilisateur est membre de ce projet.
     *
     * Utilise la relation users déjà chargée.
     */
    public function hasMember(User $user): bool
    {
        $this->loadMissing('users');

        return $this->users->contains('id', $user->id);
    }

    /**
     * Retourner le libellé lisible du statut.
     */
    public function statusLabel(): string
    {
        return match ($this->status) {
            'encours'  => 'En cours',
            'cloture'  => 'Clôturé',
            default    => ucfirst($this->status),
        };
    }

    /**
     * Vérifier si le projet est clôturé.
     */
    public function isCloture(): bool
    {
        return $this->status === 'cloture';
    }
}

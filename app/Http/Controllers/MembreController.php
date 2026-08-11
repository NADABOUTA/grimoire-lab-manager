<?php

namespace App\Http\Controllers;

use App\Events\MembreAjouteAuProjet;
use App\Http\Requests\StoreMembreRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Tache 4 — Ajouter un membre à un projet
 * Tache 5 — Retirer un membre d'un projet
 * Tache 7 — Règle métier : un projet doit toujours avoir au moins un responsable
 */
class MembreController extends Controller
{
    use AuthorizesRequests;

    /**
     * Afficher le formulaire d'ajout de membre.
     * Réservé au responsable du projet (via Policy).
     */
    public function create(Project $project)
    {
        $this->authorize('addMembre', $project);

        // Eager loading explicite — évite le N+1 dans la requête whereNotIn
        $project->load('users');

        // Récupérer les utilisateurs qui ne sont pas encore membres du projet
        $utilisateursDisponibles = User::whereNotIn('id', $project->users->pluck('id'))
                                       ->orderBy('name')
                                       ->get();

        return view('membres.create', compact('project', 'utilisateursDisponibles'));
    }

    /**
     * Tache 4 — Ajouter un membre au projet.
     * Tache 6 — Validation via StoreMembreRequest.
     * Tache 7 — Interdire d'ajouter un doublon.
     * Tache 8 — Déclencher l'événement MembreAjouteAuProjet (async).
     */
    public function store(StoreMembreRequest $request, Project $project)
    {
        $this->authorize('addMembre', $project);

        $validated = $request->validated();
        $userId    = $validated['user_id'];
        $role      = $validated['role'];

        // Vérifier que l'utilisateur n'est pas déjà membre
        $newUser = User::findOrFail($userId);
        if ($project->hasMember($newUser)) {
            return back()->withErrors(['user_id' => 'Cet utilisateur est déjà membre du projet.']);
        }

        // Attacher l'utilisateur au projet avec son rôle
        $project->users()->attach($userId, ['role' => $role]);

        // Tache 8 — Déclencher l'événement asynchrone pour notifier le nouveau membre
        event(new MembreAjouteAuProjet($project, $newUser, $role));

        return redirect()
            ->route('projects.show', $project)
            ->with('success', "Membre ajouté avec succès avec le rôle : {$role}.");
    }

    /**
     * Tache 5 — Retirer un membre du projet.
     * Tache 7 — Règle métier : impossible de retirer le dernier responsable.
     */
    public function destroy(Project $project, User $user)
    {
        $this->authorize('removeMembre', $project);

        // Tache 7 — Règle métier : vérifier qu'il restera au moins un responsable
        $membreARetirer = $project->users()->where('users.id', $user->id)->first();

        if ($membreARetirer && $membreARetirer->pivot->role === 'responsable') {
            $nombreResponsables = $project->responsables()->count();

            if ($nombreResponsables <= 1) {
                return back()->withErrors([
                    'membre' => "Impossible de retirer le dernier responsable. Attribuez d'abord ce rôle à un autre membre.",
                ]);
            }
        }

        // Détacher l'utilisateur du projet
        $project->users()->detach($user->id);

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Membre retiré du projet avec succès.');
    }
}

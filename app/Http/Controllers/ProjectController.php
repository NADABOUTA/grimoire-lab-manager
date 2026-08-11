<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Events\ProjetCloture;
use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

class ProjectController extends Controller
{
    use AuthorizesRequests;

    /**
     * Afficher uniquement les projets auxquels l'utilisateur appartient.
     */
    public function index()
    {
        $this->authorize('viewAny', Project::class);

        $projects = auth()->user()
            ->projects()
            ->with('users')
            ->latest()
            ->get();

        return view('projects.index', compact('projects'));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        $this->authorize('create', Project::class);

        return view('projects.create');
    }

    /**
     * Enregistrer un nouveau projet.
     */
    public function store(StoreProjectRequest $request)
    {
        $this->authorize('create', Project::class);

        $project = Project::create($request->validated());

        // Le créateur devient automatiquement responsable.
        $project->users()->attach(auth()->id(), [
            'role' => 'responsable'
        ]);

        return redirect()
            ->route('projects.index')
            ->with('success', 'Projet créé avec succès.');
    }

    /**
     * Afficher un projet.
     */
    public function show(Project $project)
{
    // Eager Loading des membres avant l'autorisation.
    $project->load('users');

    $this->authorize('view', $project);

    return view('projects.show', compact('project'));
}

    /**
     * Formulaire de modification.
     */
    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    /**
     * Modifier les informations générales du projet.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project);

        $project->update($request->validated());

        return redirect()
            ->route('projects.index')
            ->with('success', 'Projet mis à jour avec succès.');
    }

    /**
     * Mettre à jour uniquement l'avancement.
     * Réservé au chercheur.
     */
    public function updateAvancement(Request $request, Project $project)
    {
        $this->authorize('updateAvancement', $project);

        $request->validate([
            'avancement' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $project->update([
            'avancement' => $request->avancement,
        ]);

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Avancement mis à jour avec succès.');
    }


/**
 * Clôturer le projet.
 * Réservé au responsable.
 */
public function cloturer(Project $project)
{
    $this->authorize('cloturer', $project);

    $project->update([
        'status' => 'cloture',
    ]);

    // Déclencher la génération asynchrone du rapport
    event(new ProjetCloture($project));

    return redirect()
        ->route('projects.show', $project)
        ->with(
            'success',
            'Projet clôturé avec succès. Le rapport sera généré en arrière-plan.'
        );
}    

    /**
     * Archiver le projet avec SoftDeletes.
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('success', 'Projet archivé avec succès.');
    }

    /**
     * Afficher les projets archivés du responsable.
     */
    public function archived()
    {
        $this->authorize('viewArchived', Project::class);

        $projects = auth()->user()
            ->projetsResponsable()
            ->onlyTrashed()
            ->with('users')
            ->latest('deleted_at')
            ->get();

        return view('projects.archived', compact('projects'));
    }

    /**
     * Restaurer un projet archivé.
     */
    public function restore(Project $project)
    {
        $this->authorize('restore', $project);

        $project->restore();

        return redirect()
            ->route('projects.archived')
            ->with('success', 'Projet restauré avec succès.');
    }

    /**
     * Supprimer définitivement un projet archivé.
     */
    public function forceDelete(Project $project)
    {
        $this->authorize('forceDelete', $project);

        $project->forceDelete();

        return redirect()
            ->route('projects.archived')
            ->with('success', 'Projet supprimé définitivement.');
    }
}

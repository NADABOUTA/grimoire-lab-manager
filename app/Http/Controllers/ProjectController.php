<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Events\ProjetCloture;

class ProjectController extends Controller
{

 use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $this->authorize('viewAny', Project::class);

    $projects = Project::latest()->get();

    return view('projects.index', compact('projects'));
}

    /**
     * Show the form for creating a new resource.
     */
   public function create()
{
    $this->authorize('create', Project::class);

    return view('projects.create');
}

    /**
     * Store a newly created resource in storage.
     */
   public function store(StoreProjectRequest $request)
{
    $this->authorize('create', Project::class);

    $project = Project::create($request->validated());

    // Le créateur du projet devient automatiquement le responsable
    $project->users()->attach(auth()->id(), ['role' => 'responsable']);

    return redirect()
        ->route('projects.index')
        ->with('success', 'Projet créé avec succès.');
}

    /**
     * Display the specified resource.
     */
     public function show(Project $project)
{
    $this->authorize('view', $project);

    $project->load('users');

    return view('projects.show', compact('project'));
}
   
   
    /**
     * Show the form for editing the specified resource.
     */
   public function edit(Project $project)
{
    $this->authorize('update', $project);

    return view('projects.edit', compact('project'));
}

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
{
    $this->authorize('delete', $project);

    $project->delete();

    return redirect()
        ->route('projects.index')
        ->with('success', 'Projet supprimé avec succès.');
}
public function cloturer(Project $project)
{
  //
    $this->authorize('update', $project);

    //
    $project->update([
        'status' => 'cloture',
    ]);

    // إطلاق الـ Event
    event(new ProjetCloture($project));

    return redirect()
        ->route('projects.show', $project)
        ->with('success', 'Projet clôturé avec succès.');
}
public function updateAvancement(Request $request, Project $project)
{
    if (auth()->user()->getRoleInProject($project) !== 'chercheur') {
        abort(403);
    }

    $request->validate([
        'avancement' => 'required|integer|min:0|max:100',
    ]);

    $project->update([
        'avancement' => $request->avancement,
    ]);

    return back()->with('success', 'Avancement mis à jour.');
}
}
<?php

use App\Models\Project;
use App\Models\User;

test('un responsable peut afficher, restaurer et supprimer définitivement un projet archivé', function () {
    $responsable = User::factory()->create();
    $project = Project::factory()->create();
    $project->users()->attach($responsable->id, ['role' => 'responsable']);

    // Soft delete
    $project->delete();
    $this->assertSoftDeleted($project);

    // Consultation des archives
    $this->actingAs($responsable)
         ->get(route('projects.archived'))
         ->assertStatus(200)
         ->assertSee($project->title);

    // Restauration du projet
    $this->actingAs($responsable)
         ->patch(route('projects.restore', $project))
         ->assertRedirect(route('projects.archived'));

    $this->assertNotSoftDeleted($project->fresh());

    // Re-archiver puis supprimer définitivement
    $project->delete();
    $this->actingAs($responsable)
         ->delete(route('projects.forceDelete', $project))
         ->assertRedirect(route('projects.archived'));

    $this->assertDatabaseMissing('projects', ['id' => $project->id]);
});

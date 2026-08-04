<?php

use App\Models\Project;
use App\Models\User;
use App\Events\MembreAjouteAuProjet;
use App\Notifications\MembreAjouteNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Notification::fake();
});

test('seul le responsable peut ajouter un membre', function () {
    $responsable = User::factory()->create();
    $chercheur = User::factory()->create();
    $nouvelUtilisateur = User::factory()->create();

    $project = Project::factory()->create();
    $project->users()->attach($responsable->id, ['role' => 'responsable']);
    $project->users()->attach($chercheur->id, ['role' => 'chercheur']);

    // Chercheur essaie d'ajouter -> 403
    $this->actingAs($chercheur)
         ->post(route('membres.store', $project), [
             'user_id' => $nouvelUtilisateur->id,
             'role' => 'etudiant_assistant',
         ])
         ->assertForbidden();

    // Responsable essaie d'ajouter -> 302 (Redirect) et succès
    $this->actingAs($responsable)
         ->post(route('membres.store', $project), [
             'user_id' => $nouvelUtilisateur->id,
             'role' => 'etudiant_assistant',
         ])
         ->assertRedirect(route('projects.show', $project));

    $this->assertTrue($project->hasMember($nouvelUtilisateur));
});

test('la validation fonctionne lors de l\'ajout d\'un membre', function () {
    $responsable = User::factory()->create();
    $project = Project::factory()->create();
    $project->users()->attach($responsable->id, ['role' => 'responsable']);

    $this->actingAs($responsable)
         ->post(route('membres.store', $project), [
             'user_id' => 9999, // Inexistant
             'role' => 'role_invalide',
         ])
         ->assertSessionHasErrors(['user_id', 'role']);
});

test('l\'événement et la notification sont déclenchés lors de l\'ajout d\'un membre', function () {
    Event::fake([MembreAjouteAuProjet::class]);

    $responsable = User::factory()->create();
    $nouvelUtilisateur = User::factory()->create();
    
    $project = Project::factory()->create();
    $project->users()->attach($responsable->id, ['role' => 'responsable']);

    $this->actingAs($responsable)
         ->post(route('membres.store', $project), [
             'user_id' => $nouvelUtilisateur->id,
             'role' => 'chercheur',
         ]);

    Event::assertDispatched(MembreAjouteAuProjet::class, function ($event) use ($nouvelUtilisateur) {
        return $event->membre->id === $nouvelUtilisateur->id && $event->role === 'chercheur';
    });
});

test('un membre peut être retiré par le responsable', function () {
    $responsable = User::factory()->create();
    $chercheur = User::factory()->create();

    $project = Project::factory()->create();
    $project->users()->attach($responsable->id, ['role' => 'responsable']);
    $project->users()->attach($chercheur->id, ['role' => 'chercheur']);

    $this->actingAs($responsable)
         ->delete(route('membres.destroy', [$project, $chercheur]))
         ->assertRedirect(route('projects.show', $project));

    $this->assertFalse($project->fresh()->hasMember($chercheur));
});

test('le dernier responsable ne peut pas être retiré', function () {
    $responsable = User::factory()->create();

    $project = Project::factory()->create();
    $project->users()->attach($responsable->id, ['role' => 'responsable']);

    $this->actingAs($responsable)
         ->delete(route('membres.destroy', [$project, $responsable]))
         ->assertSessionHasErrors(['membre']);

    $this->assertTrue($project->fresh()->hasMember($responsable));
});

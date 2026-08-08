<?php

use App\Http\Controllers\MembreController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // =========================
    // PROJETS
    // =========================

    // Projets archivés
    Route::get(
        '/projects-archives',
        [ProjectController::class, 'archived']
    )->name('projects.archived');

    // Mise à jour de l'avancement par le chercheur
    Route::patch(
        '/projects/{project}/avancement',
        [ProjectController::class, 'updateAvancement']
    )->name('projects.avancement');


    // Clôturer un projet
Route::patch(
    '/projects/{project}/cloturer',
    [ProjectController::class, 'cloturer']
)->name('projects.cloturer');

    // CRUD des projets
    Route::resource('projects', ProjectController::class);


    // =========================
    // MEMBRES
    // =========================

    // Formulaire d'ajout d'un membre
    Route::get(
        '/projects/{project}/membres/create',
        [MembreController::class, 'create']
    )->name('membres.create');

    // Ajouter un membre
    Route::post(
        '/projects/{project}/membres',
        [MembreController::class, 'store']
    )->name('membres.store');

    // Retirer un membre
    Route::delete(
        '/projects/{project}/membres/{user}',
        [MembreController::class, 'destroy']
    )->name('membres.destroy');


    // =========================
    // PROFILE
    // =========================

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');
});

require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\MembreController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::resource('projects', ProjectController::class);

    // Routes membres : ajouter et retirer
    Route::get('/projects/{project}/membres/create', [MembreController::class, 'create'])->name('membres.create');
    Route::post('/projects/{project}/membres', [MembreController::class, 'store'])->name('membres.store');
    Route::delete('/projects/{project}/membres/{user}', [MembreController::class, 'destroy'])->name('membres.destroy');

    // Route pour marquer une notification comme lue
    Route::post('/notifications/{id}/read', function ($id) {
        auth()->user()->notifications()->findOrFail($id)->markAsRead();
        return back();
    })->name('notifications.read');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

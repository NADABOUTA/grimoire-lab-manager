<?php

namespace App\Providers;

use App\Events\MembreAjouteAuProjet;
use App\Listeners\NotifierMembreAjoute;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use App\Events\ProjetCloture;
use App\Listeners\GenererRapportProjet;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * Tache 9 — Enregistrement du Listener pour l'Event MembreAjouteAuProjet.
     * Quand l'event est déclenché, NotifierMembreAjoute::handle() sera appelé
     * de façon asynchrone (via la queue).
     */
    public function boot(): void
{
    Event::listen(
        MembreAjouteAuProjet::class,
        NotifierMembreAjoute::class,
    );

    Event::listen(
        ProjetCloture::class,
        GenererRapportProjet::class,
    );
}
}

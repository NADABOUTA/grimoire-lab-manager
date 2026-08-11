<?php

namespace App\Providers;

use App\Events\MembreAjouteAuProjet;
use App\Events\ProjetCloture;
use App\Listeners\NotifierMembreAjoute;
use App\Listeners\GenererRapportSynthese;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

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
     */
    public function boot(): void
    {
        Event::listen(
            MembreAjouteAuProjet::class,
            NotifierMembreAjoute::class,
        );

        Event::listen(
            ProjetCloture::class,
            GenererRapportSynthese::class,
        );
    }
}
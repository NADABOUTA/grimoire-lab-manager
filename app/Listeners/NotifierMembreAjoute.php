<?php

namespace App\Listeners;

use App\Events\MembreAjouteAuProjet;
use App\Notifications\MembreAjouteNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Tache 9 — Listener ShouldQueue : NotifierMembreAjoute
 *
 * Ce Listener écoute l'Event MembreAjouteAuProjet.
 * ShouldQueue = il est mis en file d'attente (queue) et exécuté
 * de façon ASYNCHRONE par `php artisan queue:work`.
 *
 * Cycle de vie :
 *   Event déclenché → Laravel met le Listener en queue (table "jobs")
 *   → queue:work prend le job → handle() est exécuté → notification envoyée
 */
class NotifierMembreAjoute implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Nombre de tentatives en cas d'échec.
     */
    public int $tries = 3;

    /**
     * Délai (en secondes) avant de réessayer après un échec.
     */
    public int $backoff = 10;

    /**
     * Handle — méthode appelée quand le job est traité par la queue.
     * C'est ici qu'on envoie la notification au nouveau membre.
     */
    public function handle(MembreAjouteAuProjet $event): void
    {
        // Envoyer la notification via le système Laravel Notifications
        // (email, database, etc. selon la configuration de MembreAjouteNotification)
        $event->membre->notify(
            new MembreAjouteNotification($event->project, $event->role)
        );
    }

    /**
     * En cas d'échec définitif (après $tries tentatives).
     */
    public function failed(MembreAjouteAuProjet $event, \Throwable $exception): void
    {
        \Log::error("Échec d'envoi de notification à {$event->membre->email} : {$exception->getMessage()}");
    }
}

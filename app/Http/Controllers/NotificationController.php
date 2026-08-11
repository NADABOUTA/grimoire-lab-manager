<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Affiche et gere les notifications de l'utilisateur connecte.
 * Les notifications sont stockees en base via le canal 'database'
 * de MembreAjouteNotification.
 */
class NotificationController extends Controller
{
    /**
     * Afficher toutes les notifications de l'utilisateur.
     * Les notifications non lues sont marquees comme lues au chargement.
     */
    public function index()
    {
        $user = auth()->user();

        // Charger toutes les notifications (lues + non lues)
        $notifications = $user->notifications()->latest()->paginate(15);

        // Marquer toutes les non-lues comme lues
        $user->unreadNotifications()->update(['read_at' => now()]);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Marquer une notification specifique comme lue
     * et rediriger vers le projet concerne si possible.
     */
    public function markRead(string $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        // Rediriger vers le projet si l'info est disponible
        $projectId = $notification->data['project_id'] ?? null;

        if ($projectId) {
            return redirect()->route('projects.show', $projectId);
        }

        return redirect()->route('notifications.index');
    }
}

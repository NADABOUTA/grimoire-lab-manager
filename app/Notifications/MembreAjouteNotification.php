<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Tache 10 — Notification MembreAjouteNotification
 *
 * Cette notification est envoyée au nouveau membre d'un projet.
 * Elle utilise deux canaux :
 *   - 'mail'     : envoi d'un email
 *   - 'database' : stockage en base pour affichage dans l'app
 *
 * Elle implémente ShouldQueue pour être traitée en arrière-plan.
 */
class MembreAjouteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Constructeur — on reçoit le projet et le rôle attribué.
     */
    public function __construct(
        public readonly Project $project,
        public readonly string  $role,
    ) {}

    /**
     * Canaux de notification utilisés.
     * 'mail' → email, 'database' → stocké en base (table notifications).
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Contenu de l'email de notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $roleFormate = match ($this->role) {
            'responsable'       => 'Responsable',
            'chercheur'         => 'Chercheur',
            'etudiant_assistant' => 'Étudiant Assistant',
            default              => $this->role,
        };

        return (new MailMessage)
            ->subject("Vous avez été ajouté au projet : {$this->project->title}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Vous avez été ajouté au projet **{$this->project->title}**.")
            ->line("Votre rôle : **{$roleFormate}**.")
            ->action('Consulter le projet', route('projects.show', $this->project))
            ->line('Bienvenue dans l\'équipe !');
    }

    /**
     * Données stockées en base (canal database).
     * Accessibles via $user->notifications.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'project_id'    => $this->project->id,
            'project_title' => $this->project->title,
            'role'          => $this->role,
            'message'       => "Vous avez été ajouté au projet {$this->project->title} en tant que {$this->role}.",
        ];
    }

    /**
     * Représentation générique (pour autres canaux éventuels).
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Tache 6 — Validation de l'ajout d'un membre à un projet
 *
 * Règles :
 * - user_id : obligatoire, doit exister dans la table users
 * - role : obligatoire, uniquement parmi les 3 rôles autorisés
 */
class StoreMembreRequest extends FormRequest
{
    /**
     * Seul le responsable du projet peut ajouter un membre.
     * L'autorisation fine est gérée dans le contrôleur via Policy.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation.
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'role'    => ['required', 'in:responsable,chercheur,etudiant_assistant'],
        ];
    }

    /**
     * Messages personnalisés en français.
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'Veuillez sélectionner un utilisateur.',
            'user_id.exists'   => "Cet utilisateur n'existe pas.",
            'role.required'    => 'Veuillez choisir un rôle.',
            'role.in'          => 'Le rôle doit être : responsable, chercheur ou étudiant assistant.',
        ];
    }
}

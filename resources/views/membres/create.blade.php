<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ajouter un membre au projet : {{ $project->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- Affichage des erreurs de validation (Tâche 6 & 7) -->
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 text-red-700 p-4 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('membres.store', $project) }}">
                    @csrf

                    <!-- Sélection de l'utilisateur -->
                    <div class="mb-4">
                        <label for="user_id" class="block text-sm font-medium text-gray-700">Utilisateur</label>
                        <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Sélectionner un utilisateur --</option>
                            @foreach($utilisateursDisponibles as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sélection du rôle -->
                    <div class="mb-4">
                        <label for="role" class="block text-sm font-medium text-gray-700">Rôle dans le projet</label>
                        <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="chercheur" {{ old('role') == 'chercheur' ? 'selected' : '' }}>Chercheur</option>
                            <option value="etudiant_assistant" {{ old('role') == 'etudiant_assistant' ? 'selected' : '' }}>Étudiant Assistant</option>
                            <option value="responsable" {{ old('role') == 'responsable' ? 'selected' : '' }}>Responsable</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('projects.show', $project) }}" class="text-gray-600 hover:text-gray-900 underline mr-4">Annuler</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Ajouter le membre
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

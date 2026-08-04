<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Liste des projets
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @can('create', App\Models\Project::class)
                <div class="flex justify-end mb-4">
                    <a href="{{ route('projects.create') }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        + Ajouter un projet
                    </a>
                </div>
            @endcan

            <div class="bg-white shadow rounded-lg overflow-hidden">

                <table class="min-w-full border-collapse">

                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2 text-left">Titre</th>
                            <th class="border px-4 py-2 text-left">Statut</th>
                            <th class="border px-4 py-2 text-left">Avancement</th>
                            <th class="border px-4 py-2 text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($projects as $project)

                            <tr>
                                <td class="border px-4 py-2">
                                    {{ $project->title }}
                                </td>

                                <td class="border px-4 py-2">
                                    {{ $project->status }}
                                </td>

                                <td class="border px-4 py-2">
                                    {{ $project->avancement }}%
                                </td>

                                <td class="border px-4 py-2 text-center">

                                    @can('view', $project)
                                        <a href="{{ route('projects.show', $project) }}"
                                           class="text-blue-600 hover:underline">
                                            Voir
                                        </a>
                                    @endcan

                                    @can('update', $project)
                                        <a href="{{ route('projects.edit', $project) }}"
                                           class="text-yellow-600 hover:underline ml-3">
                                            Modifier
                                        </a>
                                    @endcan

                                    @can('delete', $project)
                                        <form action="{{ route('projects.destroy', $project) }}"
                                              method="POST"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                onclick="return confirm('Supprimer ce projet ?')"
                                                class="text-red-600 hover:underline ml-3">
                                                Supprimer
                                            </button>
                                        </form>
                                    @endcan

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    Aucun projet trouvé.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>
</x-app-layout>
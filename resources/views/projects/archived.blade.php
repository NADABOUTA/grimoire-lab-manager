<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Projets Archivés
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-center mb-4">
                <a href="{{ route('projects.index') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    ← Retour aux projets actifs
                </a>
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden">

                <table class="min-w-full border-collapse">

                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2 text-left">Titre</th>
                            <th class="border px-4 py-2 text-left">Statut</th>
                            <th class="border px-4 py-2 text-left">Date d'archivage</th>
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
                                    @if($project->status === 'encours')
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">
                                            En cours
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-200 text-gray-700 text-xs rounded">
                                            Clôturé
                                        </span>
                                    @endif
                                </td>

                                <td class="border px-4 py-2 text-sm text-gray-600">
                                    {{ $project->deleted_at->format('d/m/Y H:i') }}
                                </td>

                                <td class="border px-4 py-2 text-center">

                                    @can('restore', $project)
                                        <form action="{{ route('projects.restore', $project) }}"
                                              method="POST"
                                              class="inline">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    onclick="return confirm('Restaurer ce projet ?')"
                                                    class="text-green-600 hover:underline">
                                                Restaurer
                                            </button>
                                        </form>
                                    @endcan

                                    @can('forceDelete', $project)
                                        <form action="{{ route('projects.forceDelete', $project) }}"
                                              method="POST"
                                              class="inline ml-3">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    onclick="return confirm('Supprimer définitivement ce projet ? Cette action est irréversible.')"
                                                    class="text-red-600 hover:underline">
                                                Supprimer définitivement
                                            </button>
                                        </form>
                                    @endcan

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="text-center py-4 text-gray-500">
                                    Aucun projet archivé.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>
</x-app-layout>

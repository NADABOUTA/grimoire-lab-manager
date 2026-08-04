<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Détails du projet
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg p-6">

                <div class="mb-4">
                    <h3 class="font-bold text-lg">Titre</h3>
                    <p>{{ $project->title }}</p>
                </div>

                <div class="mb-4">
                    <h3 class="font-bold text-lg">Description</h3>
                    <p>{{ $project->description }}</p>
                </div>

                <div class="mb-4">
                    <h3 class="font-bold text-lg">Statut</h3>
                    <p>{{ $project->status }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="font-bold text-lg">Avancement</h3>
                    <p>{{ $project->avancement }}%</p>
                </div>

                <div class="flex gap-3">

                    <a href="{{ route('projects.index') }}"
                       class="bg-gray-500 text-white px-4 py-2 rounded">
                        Retour
                    </a>

                    @can('update', $project)
                        <a href="{{ route('projects.edit', $project) }}"
                           class="bg-yellow-500 text-white px-4 py-2 rounded">
                            Modifier
                        </a>
                    @endcan

                    @can('delete', $project)
                        <form action="{{ route('projects.destroy', $project) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    onclick="return confirm('Supprimer ce projet ?')"
                                    class="bg-red-600 text-white px-4 py-2 rounded">
                                Supprimer
                            </button>
                        </form>
                    @endcan

                </div>

            </div>

        </div>
    </div>

</x-app-layout>ok 
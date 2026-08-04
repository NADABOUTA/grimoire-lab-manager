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

            <!-- Liste des membres (Tâches 4 et 5 affichées) -->
            <div class="bg-white shadow rounded-lg p-6 mt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg">Membres du projet</h3>
                    @can('addMembre', $project)
                        <a href="{{ route('membres.create', $project) }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                            + Ajouter un membre
                        </a>
                    @endcan
                </div>

                @if(session('success'))
                    <div class="mb-4 bg-green-100 text-green-700 p-4 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->has('membre'))
                    <div class="mb-4 bg-red-100 text-red-700 p-4 rounded">
                        {{ $errors->first('membre') }}
                    </div>
                @endif

                @if($project->users->isEmpty())
                    <p class="text-gray-500">Aucun membre pour le moment.</p>
                @else
                    <ul class="divide-y divide-gray-200">
                        @foreach($project->users as $membre)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $membre->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $membre->email }} — <span class="capitalize font-semibold">{{ str_replace('_', ' ', $membre->pivot->role) }}</span></p>
                                </div>
                                
                                @can('removeMembre', $project)
                                    <form action="{{ route('membres.destroy', [$project, $membre]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Retirer ce membre ?')" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                            Retirer
                                        </button>
                                    </form>
                                @endcan
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

        </div>
    </div>

</x-app-layout>ok 
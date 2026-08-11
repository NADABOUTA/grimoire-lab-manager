<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tableau de bord
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 bg-green-100 text-green-700 p-4 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">
                    Mes projets
                </h3>

                @can('create', App\Models\Project::class)
                    <a href="{{ route('projects.create') }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        + Nouveau projet
                    </a>
                @endcan
            </div>

            @if($projects->isEmpty())

                <div class="bg-white shadow rounded-lg p-6">
                    <p class="text-gray-500">
                        Vous n'avez aucun projet pour le moment.
                    </p>
                </div>

            @else

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    @foreach($projects as $project)

                        @php
                            $role = $project->pivot->role;
                        @endphp

                        <div class="bg-white shadow rounded-lg p-6">

                            <h4 class="text-xl font-bold text-gray-800 mb-2">
                                {{ $project->title }}
                            </h4>

                            <p class="text-gray-600 mb-4">
                                {{ $project->description }}
                            </p>

                            <div class="mb-2">
                                <span class="font-semibold">Statut :</span>
                                @if($project->status === 'encours')
                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full ml-1">
                                        En cours
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full ml-1">
                                        Clôturé
                                    </span>
                                @endif
                            </div>

                            <div class="mb-4">
                                <span class="font-semibold">
                                    Avancement :
                                </span>
                                {{ $project->avancement }}%
                            </div>

                            <div class="mb-4">
                                <span class="font-semibold">
                                    Mon rôle :
                                </span>

                                <span class="capitalize">
                                    {{ str_replace('_', ' ', $role) }}
                                </span>
                            </div>

                            <div class="flex gap-2 flex-wrap">

                                <a href="{{ route('projects.show', $project) }}"
                                   class="bg-gray-500 text-white px-3 py-2 rounded hover:bg-gray-600">
                                    Voir
                                </a>

                                @if($role === 'responsable')

                                    <a href="{{ route('projects.edit', $project) }}"
                                       class="bg-yellow-500 text-white px-3 py-2 rounded hover:bg-yellow-600">
                                        Modifier
                                    </a>

                                @elseif($role === 'chercheur')

                                    <a href="{{ route('projects.show', $project) }}"
                                       class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700">
                                        Avancement
                                    </a>

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            @endif

        </div>
    </div>

</x-app-layout>
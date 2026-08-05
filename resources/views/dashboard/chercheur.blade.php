<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Chercheur
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if($projects->count())

                @foreach($projects as $project)

                    <div class="bg-white shadow rounded-lg p-6 mb-4">

                        <h3 class="text-lg font-bold">
                            {{ $project->title }}
                        </h3>

                        <p class="mt-2">
                            {{ $project->description }}
                        </p>

                        <p class="mt-2">
                            <strong>Statut :</strong>
                            {{ $project->status }}
                        </p>

                        <p>
                            <strong>Avancement :</strong>
                            {{ $project->avancement }}%
                        </p>

                        <a href="{{ route('projects.show', $project) }}"
                           class="text-blue-600 hover:underline">
                            Voir le projet
                        </a>

                    </div>

                @endforeach

            @else

                <div class="bg-white shadow rounded-lg p-6">
                    Aucun projet trouvé.
                </div>

            @endif

        </div>
    </div>

</x-app-layout>
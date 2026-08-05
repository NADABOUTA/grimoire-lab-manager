<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Fiche Projet
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg p-6">

                <h2 class="text-2xl font-bold mb-4">
                    {{ $project->title }}
                </h2>

                <p class="mb-4">
                    {{ $project->description }}
                </p>

                <p class="mb-2">
                    <strong>Statut :</strong>
                    {{ $project->status }}
                </p>

                <p class="mb-2">
                    <strong>Avancement :</strong>
                    {{ $project->avancement }} %
                </p>
                @if(auth()->user()->getRoleInProject($project) === 'chercheur')
            @can('update', $project)

<form action="{{ route('projects.cloturer', $project) }}" method="POST" class="mb-4">
    @csrf
    @method('PATCH')

    <button
        type="submit"
        class="bg-red-600 text-white px-4 py-2 rounded">
        Clôturer le projet
    </button>
</form>

@endcan
    <hr class="my-5">

    <h3 class="text-lg font-semibold mb-3">
        Mettre à jour l'avancement
    </h3>

    <form action="{{ route('projects.avancement', $project) }}" method="POST">
        @csrf
        @method('PATCH')

        <input
            type="number"
            name="avancement"
            min="0"
            max="100"
            value="{{ $project->avancement }}"
            class="border rounded p-2 w-32"
        >

        <button
            type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded ml-2">
            Mettre à jour
        </button>
    </form>

@endif

                <hr class="my-5">

                <h3 class="text-lg font-semibold mb-3">
                    Membres du projet
                </h3>

                <ul>

                    @foreach($project->users as $user)

                        <li class="mb-2">

                            {{ $user->name }}

                            ({{ $user->pivot->role }})

                        </li>

                    @endforeach

                </ul>

            </div>

        </div>
    </div>

</x-app-layout>
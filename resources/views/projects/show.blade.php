<!-- ```blade -->
<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Détails du projet
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Informations du projet --}}
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

                    <p class="text-xl font-semibold">
                        {{ $project->avancement }}%
                    </p>
                </div>

                {{-- Actions générales --}}
                <div class="flex gap-3 flex-wrap">

                    <a href="{{ route('projects.index') }}"
                       class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Retour
                    </a>

                    {{-- Seul le responsable peut modifier --}}
                    @can('update', $project)
                        <a href="{{ route('projects.edit', $project) }}"
                           class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                            Modifier
                        </a>
                    @endcan

                    {{-- Seul le responsable peut clôturer --}}
                    @can('cloturer', $project)
                        <form action="{{ route('projects.cloturer', $project) }}"
                              method="POST">

                            @csrf
                            @method('PATCH')

                            <button type="submit"
                                    onclick="return confirm('Clôturer ce projet ?')"
                                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                Clôturer le projet
                            </button>
                        </form>
                    @endcan

                    {{-- Seul le responsable peut archiver --}}
                    @can('delete', $project)
                        <form action="{{ route('projects.destroy', $project) }}"
                              method="POST">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    onclick="return confirm('Archiver ce projet ?')"
                                    class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                Archiver
                            </button>
                        </form>
                    @endcan

                </div>

            </div>

            {{-- Mise à jour de l'avancement : chercheur uniquement --}}
            @can('updateAvancement', $project)

                <div class="bg-white shadow rounded-lg p-6 mt-6">

                    <h3 class="font-bold text-lg mb-4">
                        Mise à jour de l'avancement
                    </h3>

                    <form action="{{ route('projects.avancement', $project) }}"
                          method="POST">

                        @csrf
                        @method('PATCH')

                        <div class="mb-4">

                            <label for="avancement"
                                   class="block font-medium text-gray-700 mb-2">
                                Avancement (%)
                            </label>

                            <input
                                type="number"
                                id="avancement"
                                name="avancement"
                                min="0"
                                max="100"
                                value="{{ old('avancement', $project->avancement) }}"
                                class="w-full border-gray-300 rounded-md shadow-sm"
                                required
                            >

                            @error('avancement')
                                <p class="text-red-600 text-sm mt-1">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Mettre à jour
                        </button>

                    </form>

                </div>

            @endcan

            {{-- Membres du projet --}}
            <div class="bg-white shadow rounded-lg p-6 mt-6">

                <div class="flex justify-between items-center mb-4">

                    <h3 class="font-bold text-lg">
                        Membres du projet
                    </h3>

                    {{-- Seul le responsable peut ajouter --}}
                    @can('addMembre', $project)
                        <a href="{{ route('membres.create', $project) }}"
                           class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                            + Ajouter un membre
                        </a>
                    @endcan

                </div>

                {{-- Messages --}}
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

                {{-- Calculer une seule fois l'autorisation --}}
                @php
                    $canRemoveMembre = auth()->user()->can('removeMembre', $project);
                @endphp

                {{-- Liste des membres --}}
                @if($project->users->isEmpty())

                    <p class="text-gray-500">
                        Aucun membre pour le moment.
                    </p>

                @else

                    <ul class="divide-y divide-gray-200">

                        @foreach($project->users as $membre)

                            <li class="py-3 flex justify-between items-center">

                                <div>

                                    <p class="font-medium text-gray-900">
                                        {{ $membre->name }}
                                    </p>

                                    <p class="text-sm text-gray-500">
                                        {{ $membre->email }}
                                        —
                                        <span class="capitalize font-semibold">
                                            {{ str_replace('_', ' ', $membre->pivot->role) }}
                                        </span>
                                    </p>

                                </div>

                                {{-- Seul le responsable peut retirer --}}
                                @if($canRemoveMembre)

                                    <form action="{{ route('membres.destroy', [$project, $membre]) }}"
                                          method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                onclick="return confirm('Retirer ce membre ?')"
                                                class="text-red-600 hover:text-red-900 text-sm font-medium">
                                            Retirer
                                        </button>

                                    </form>

                                @endif

                            </li>

                        @endforeach

                    </ul>

                @endif

            </div>

        </div>
    </div>

</x-app-layout>


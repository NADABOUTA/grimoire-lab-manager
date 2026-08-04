<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ajouter un projet
        </h2>
    </x-slot>

    <div class="py-8">

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg p-6">

                <form action="{{ route('projects.store') }}" method="POST">

                    @csrf

                    <div class="mb-4">

                        <label class="block font-medium mb-2">
                            Titre
                        </label>

                        <input
                            type="text"
                            name="title"
                            value="{{ old('title') }}"
                            class="w-full border rounded px-3 py-2"
                        >

                        @error('title')
                            <p class="text-red-500 text-sm">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    <div class="mb-4">

                        <label class="block font-medium mb-2">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            class="w-full border rounded px-3 py-2"
                        >{{ old('description') }}</textarea>

                        @error('description')
                            <p class="text-red-500 text-sm">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    <div class="mb-4">

                        <label class="block font-medium mb-2">
                            Statut
                        </label>

                        <select
                            name="status"
                            class="w-full border rounded px-3 py-2"
                        >
                            <option value="En attente">En attente</option>
                            <option value="En cours">En cours</option>
                            <option value="Terminé">Terminé</option>
                        </select>

                        @error('status')
                            <p class="text-red-500 text-sm">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    <div class="mb-4">

                        <label class="block font-medium mb-2">
                            Avancement (%)
                        </label>

                        <input
                            type="number"
                            name="avancement"
                            min="0"
                            max="100"
                            value="{{ old('avancement', 0) }}"
                            class="w-full border rounded px-3 py-2"
                        >

                        @error('avancement')
                            <p class="text-red-500 text-sm">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    <div class="flex justify-end gap-3">

                        <a href="{{ route('projects.index') }}"
                           class="bg-gray-500 text-white px-4 py-2 rounded">
                            Annuler
                        </a>

                        <button
                            type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded">
                            Enregistrer
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>
<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Modifier le projet
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg p-6">

                <form action="{{ route('projects.update', $project) }}" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium mb-2">
                            Titre
                        </label>

                        <input
                            type="text"
                            name="title"
                            value="{{ old('title', $project->title) }}"
                            class="w-full border rounded px-3 py-2">

                        @error('title')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            class="w-full border rounded px-3 py-2">{{ old('description', $project->description) }}</textarea>

                        @error('description')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">
                            Statut
                        </label>

                        <select
                            name="status"
                            class="w-full border rounded px-3 py-2">

                            <option value="encours" @selected(old('status', $project->status) == 'encours')>
                                En cours
                            </option>

                            <option value="cloture" @selected(old('status', $project->status) == 'cloture')>
                                Clôturé
                            </option>

                        </select>

                        @error('status')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
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
                            value="{{ old('avancement', $project->avancement) }}"
                            class="w-full border rounded px-3 py-2">

                        @error('avancement')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
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
                            Modifier
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>
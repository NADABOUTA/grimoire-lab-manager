<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mes notifications
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Lien retour --}}
            <div class="mb-4">
                <a href="{{ route('dashboard') }}"
                   class="text-gray-600 hover:text-gray-800 text-sm">&larr; Retour au tableau de bord</a>
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden">

                @if($notifications->isEmpty())

                    <div class="p-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <p>Aucune notification pour le moment.</p>
                    </div>

                @else

                    <ul class="divide-y divide-gray-100">

                        @foreach($notifications as $notification)

                            @php
                                $data     = $notification->data;
                                $isUnread = is_null($notification->read_at);
                            @endphp

                            <li class="p-4 flex items-start gap-4 {{ $isUnread ? 'bg-blue-50' : '' }} hover:bg-gray-50 transition">

                                {{-- Icone --}}
                                <div class="shrink-0 mt-0.5">
                                    @if($isUnread)
                                        <span class="inline-block w-2.5 h-2.5 bg-blue-500 rounded-full mt-1"></span>
                                    @else
                                        <span class="inline-block w-2.5 h-2.5 bg-gray-300 rounded-full mt-1"></span>
                                    @endif
                                </div>

                                {{-- Contenu --}}
                                <div class="flex-1">
                                    <p class="text-gray-800 font-medium">
                                        {{ $data['message'] ?? 'Notification' }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>

                                {{-- Lien vers le projet --}}
                                @if(isset($data['project_id']))
                                    <a href="{{ route('projects.show', $data['project_id']) }}"
                                       class="shrink-0 text-sm text-blue-600 hover:underline font-medium">
                                        Voir le projet &rarr;
                                    </a>
                                @endif

                            </li>

                        @endforeach

                    </ul>

                    {{-- Pagination --}}
                    <div class="p-4 border-t">
                        {{ $notifications->links() }}
                    </div>

                @endif

            </div>

        </div>
    </div>

</x-app-layout>

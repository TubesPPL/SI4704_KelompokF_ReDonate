<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                    {{ __('Kampanye Donasi Bersama') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Mari berkontribusi dalam event donasi yang diselenggarakan komunitas.</p>
            </div>
            
            <!-- Only Admin/Authorized should see this button ideally -->
            @auth
            <a href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-700 focus:bg-teal-700 active:bg-teal-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Event Baru
            </a>
            @endauth
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50" x-data="{ tab: 'active' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="bg-teal-50 border-l-4 border-teal-500 p-4 mb-6 rounded shadow-sm">
                    <p class="text-sm text-teal-700 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Tabs Navigation -->
            <div class="border-b border-gray-200 mb-8">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button @click="tab = 'active'" :class="tab === 'active' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm focus:outline-none transition">
                        Berlangsung ({{ $activeEvents->count() }})
                    </button>
                    <button @click="tab = 'upcoming'" :class="tab === 'upcoming' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm focus:outline-none transition">
                        Akan Datang ({{ $upcomingEvents->count() }})
                    </button>
                    <button @click="tab = 'completed'" :class="tab === 'completed' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm focus:outline-none transition">
                        Selesai ({{ $completedEvents->count() }})
                    </button>
                </nav>
            </div>

            <!-- Tab Content: Active -->
            <div x-show="tab === 'active'" x-transition.opacity>
                @if($activeEvents->count() > 0)
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        @foreach($activeEvents as $event)
                            <x-event-card :event="$event" />
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-xl p-12 text-center border border-gray-200">
                        <div class="mx-auto h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Belum Ada Event Berlangsung</h3>
                        <p class="mt-1 text-gray-500">Saat ini belum ada kampanye donasi yang aktif.</p>
                    </div>
                @endif
            </div>

            <!-- Tab Content: Upcoming -->
            <div x-show="tab === 'upcoming'" x-transition.opacity style="display: none;">
                @if($upcomingEvents->count() > 0)
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        @foreach($upcomingEvents as $event)
                            <x-event-card :event="$event" />
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-xl p-12 text-center border border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Belum Ada Event Akan Datang</h3>
                        <p class="mt-1 text-gray-500">Belum ada jadwal kampanye baru yang diumumkan.</p>
                    </div>
                @endif
            </div>

            <!-- Tab Content: Completed -->
            <div x-show="tab === 'completed'" x-transition.opacity style="display: none;">
                @if($completedEvents->count() > 0)
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        @foreach($completedEvents as $event)
                            <x-event-card :event="$event" />
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-xl p-12 text-center border border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Belum Ada Riwayat Event</h3>
                        <p class="mt-1 text-gray-500">Platform belum menyelesaikan kampanye apapun.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Profil Publik: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Profil Info Card -->
            <div class="p-6 sm:p-8 bg-white shadow sm:rounded-lg flex flex-col md:flex-row items-center md:items-start gap-6">
                <!-- Avatar -->
                <div class="shrink-0 relative">
                    <img class="h-32 w-32 object-cover rounded-full shadow-lg border-4 border-teal-50" 
                         src="{{ $user->avatar ? Storage::url($user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=0D9488&background=CCFBF1' }}" 
                         alt="{{ $user->name }}" />
                    
                    @if($completedDonations >= 3)
                        <!-- Badge Donatur Aktif -->
                        <div class="absolute -bottom-2 -right-2 bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-full shadow-md flex items-center gap-1 border-2 border-white" title="Telah menyelesaikan 3+ donasi">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                            </svg>
                            Donatur Aktif
                        </div>
                    @endif
                </div>

                <!-- Info -->
                <div class="flex-1 text-center md:text-left space-y-2">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                    
                    @if($user->bio)
                        <p class="text-gray-600 text-sm max-w-2xl">{{ $user->bio }}</p>
                    @endif

                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 mt-4 text-sm text-gray-500">
                        <div class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            Bergabung sejak {{ $user->created_at->translatedFormat('F Y') }}
                        </div>
                        
                        <div class="flex items-center gap-1 text-teal-600 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            {{ $completedDonations }} Donasi Berhasil
                        </div>

                        <div class="flex items-center gap-1 text-yellow-500 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            {{ number_format($averageRating, 1) }} / 5.0 Rating
                        </div>
                    </div>

                    <!-- Tombol Laporkan Pengguna -->
                    @auth
                        @if(Auth::id() !== $user->id)
                            <div class="mt-6 flex justify-center md:justify-start">
                                <a href="{{ route('report.create', ['type' => 'user', 'id' => $user->id]) }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-red-500 hover:text-red-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    Laporkan pengguna ini
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Active Items Grid -->
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Donasi Aktif dari {{ $user->name }}</h3>
                
                @if($activeItems->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($activeItems as $item)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                                <div class="aspect-w-16 aspect-h-9 bg-gray-100 relative">
                                    @if($item->images && count($item->images) > 0)
                                        <img src="{{ Storage::url($item->images[0]) }}" class="object-cover w-full h-48" alt="{{ $item->title }}">
                                    @else
                                        <div class="w-full h-48 flex items-center justify-center text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="absolute top-2 right-2 bg-teal-100 text-teal-800 text-xs px-2 py-1 rounded font-medium">
                                        {{ $item->condition }}
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h4 class="font-bold text-lg text-gray-900 truncate">{{ $item->title }}</h4>
                                    <p class="text-gray-500 text-sm mt-1 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $item->location }}
                                    </p>
                                    <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                                        <span class="text-xs text-gray-500">{{ $item->created_at->diffForHumans() }}</span>
                                        <a href="#" class="text-teal-600 font-semibold text-sm hover:text-teal-700">Lihat Detail &rarr;</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 rounded-lg p-8 text-center text-gray-500 border border-gray-200">
                        <p>Belum ada barang donasi aktif saat ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Barang yang Dibutuhkan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">
                
                <!-- Sidebar Filter -->
                <div class="w-full lg:w-1/4">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 sticky top-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Filter Kategori</h3>
                            @if(request('category'))
                                <a href="{{ route('wishlist-requests.index') }}" class="text-xs text-red-500 hover:text-red-700 font-medium">Reset</a>
                            @endif
                        </div>
                        
                        <form action="{{ route('wishlist-requests.index') }}" method="GET" x-data="{ submitForm() { this.$el.submit() } }">
                            <div class="space-y-3 max-h-[60vh] overflow-y-auto pr-2">
                                @foreach($categories as $category)
                                    <label class="flex items-center group cursor-pointer">
                                        <input type="checkbox" name="category[]" value="{{ $category->id }}" @change="submitForm"
                                               class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500"
                                               {{ in_array($category->id, (array)request('category', [])) ? 'checked' : '' }}>
                                        <span class="ml-3 text-sm text-gray-600 group-hover:text-gray-900 transition flex-1">{{ $category->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </form>
                        
                        @auth
                            <div class="mt-8 pt-6 border-t border-gray-100">
                                <a href="{{ route('wishlist-requests.create') }}" class="w-full flex justify-center items-center gap-2 py-2.5 bg-gray-900 text-white text-sm font-bold rounded-lg shadow-sm hover:bg-gray-800 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Buat Permintaan Baru
                                </a>
                            </div>
                        @else
                            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                                <p class="text-xs text-gray-500 mb-3">Login untuk membuat permintaan barang yang Anda butuhkan.</p>
                                <a href="{{ route('login') }}" class="text-sm font-bold text-teal-600 hover:underline">Login sekarang</a>
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- Grid Permintaan -->
                <div class="w-full lg:w-3/4">
                    <div class="mb-6 bg-blue-50 border border-blue-100 rounded-xl p-5 flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                        <div class="bg-blue-100 text-blue-600 p-3 rounded-full flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-blue-900 font-bold text-base mb-1">Punya Barang Tidak Terpakai?</h3>
                            <p class="text-blue-800 text-sm">Lihat daftar permintaan di bawah ini. Mungkin ada barang di rumah Anda yang sedang sangat dibutuhkan oleh orang lain.</p>
                        </div>
                    </div>

                    @if($wishlistRequests->isEmpty())
                        <div class="bg-white p-12 rounded-xl border border-gray-100 text-center">
                            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Permintaan</h3>
                            <p class="text-gray-500">Saat ini tidak ada permintaan barang yang sedang aktif.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($wishlistRequests as $request)
                                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition group flex flex-col h-full">
                                    <div class="p-5 flex-1 flex flex-col">
                                        <div class="flex justify-between items-start mb-3">
                                            <span class="inline-block px-2.5 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold rounded uppercase tracking-wide">{{ $request->category->name }}</span>
                                            
                                            @if($request->expires_at)
                                                @php
                                                    $daysLeft = now()->startOfDay()->diffInDays($request->expires_at, false);
                                                @endphp
                                                @if($daysLeft <= 3)
                                                    <span class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-1 rounded flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        Sisa {{ $daysLeft }} Hari
                                                    </span>
                                                @else
                                                    <span class="text-[10px] font-bold text-gray-500 bg-gray-50 px-2 py-1 rounded flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        Sisa {{ $daysLeft }} Hari
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                        
                                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-teal-600 transition">
                                            <a href="{{ route('wishlist-requests.show', $request) }}">{{ $request->title }}</a>
                                        </h3>
                                        
                                        <p class="text-sm text-gray-600 mb-4 line-clamp-3 flex-1">{{ $request->description }}</p>
                                        
                                        @if($request->condition_needed)
                                            <div class="mb-4 text-xs font-medium text-gray-500 flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Kondisi Diharapkan: <span class="text-gray-700">{{ $request->condition_needed }}</span>
                                            </div>
                                        @endif
                                        
                                        <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <img src="{{ $request->user->avatar ? Storage::url($request->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($request->user->name).'&color=0D9488&background=CCFBF1' }}" alt="{{ $request->user->name }}" class="w-6 h-6 rounded-full object-cover">
                                                <span class="text-xs font-bold text-gray-700 truncate w-24">{{ explode(' ', $request->user->name)[0] }}</span>
                                            </div>
                                            
                                            <div class="flex gap-2">
                                                <a href="{{ route('wishlist-requests.show', $request) }}" class="text-xs font-bold text-teal-600 hover:text-teal-800 bg-teal-50 hover:bg-teal-100 px-3 py-1.5 rounded transition">Detail</a>
                                                
                                                @if(Auth::check() && Auth::id() !== $request->user_id)
                                                    <a href="{{ route('wishlist-requests.show', $request) }}#respond" class="text-xs font-bold text-white bg-teal-600 hover:bg-teal-700 px-3 py-1.5 rounded shadow-sm transition">Saya Punya Ini</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($wishlistRequests->hasPages())
                            <div class="mt-8">
                                {{ $wishlistRequests->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

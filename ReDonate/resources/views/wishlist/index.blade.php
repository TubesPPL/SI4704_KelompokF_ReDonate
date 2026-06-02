<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Wishlist Saya') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="bg-teal-50 border-l-4 border-teal-500 p-4 mb-6 rounded shadow-sm">
                    <p class="text-sm text-teal-700 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Kolom Kiri: Barang yang di-wishlist (2/3) -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ tab: 'available' }">
                        <div class="border-b border-gray-100 bg-white px-6 py-4 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-900">Barang Tersimpan</h3>
                            <div class="flex space-x-2">
                                <button @click="tab = 'available'" :class="tab === 'available' ? 'bg-teal-50 text-teal-700 font-semibold' : 'text-gray-500 hover:bg-gray-50'" class="px-3 py-1.5 text-sm rounded-lg transition">Tersedia ({{ $availableItems->count() }})</button>
                                <button @click="tab = 'unavailable'" :class="tab === 'unavailable' ? 'bg-gray-100 text-gray-700 font-semibold' : 'text-gray-500 hover:bg-gray-50'" class="px-3 py-1.5 text-sm rounded-lg transition">Tidak Tersedia ({{ $unavailableItems->count() }})</button>
                            </div>
                        </div>

                        <!-- Tab Tersedia -->
                        <div x-show="tab === 'available'" class="p-6">
                            @if($availableItems->isEmpty())
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                    <p class="text-sm text-gray-500">Belum ada barang tersedia di wishlist Anda.</p>
                                    <a href="{{ route('catalog.index') }}" class="mt-3 inline-block text-sm text-teal-600 font-medium hover:underline">Jelajahi Katalog</a>
                                </div>
                            @else
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($availableItems as $item)
                                        <div class="border border-gray-100 rounded-lg overflow-hidden flex flex-col hover:shadow-md transition bg-white relative group">
                                            <div class="h-40 bg-gray-100 relative">
                                                @if($item->images && count($item->images) > 0)
                                                    <img src="{{ Storage::url($item->images[0]) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                @endif
                                                <form action="{{ route('wishlist.destroy', $item) }}" method="POST" class="absolute top-2 right-2">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="p-1.5 bg-white/80 backdrop-blur text-red-500 rounded-full hover:bg-red-50 transition" title="Hapus dari wishlist"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg></button>
                                                </form>
                                            </div>
                                            <div class="p-4 flex-1 flex flex-col">
                                                <a href="{{ route('items.show', $item->slug) }}" class="text-sm font-bold text-gray-900 hover:text-teal-600 line-clamp-1">{{ $item->title }}</a>
                                                <p class="text-xs text-gray-500 mt-1 mb-3">{{ $item->category->name }}</p>
                                                <div class="mt-auto">
                                                    <form action="{{ route('claims.store', $item->slug) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="message" value="Saya tertarik dan membutuhkan barang ini.">
                                                        <button type="submit" class="w-full py-2 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded shadow-sm transition">Ajukan Klaim</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Tab Tidak Tersedia -->
                        <div x-show="tab === 'unavailable'" class="p-6" style="display: none;">
                            @if($unavailableItems->isEmpty())
                                <div class="text-center py-8">
                                    <p class="text-sm text-gray-500">Tidak ada barang yang tidak tersedia.</p>
                                </div>
                            @else
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($unavailableItems as $item)
                                        <div class="border border-gray-100 rounded-lg overflow-hidden flex flex-col bg-gray-50 opacity-75 relative">
                                            <div class="h-32 bg-gray-200 relative grayscale">
                                                @if($item->images && count($item->images) > 0)
                                                    <img src="{{ Storage::url($item->images[0]) }}" alt="{{ $item->title }}" class="w-full h-full object-cover mix-blend-multiply">
                                                @endif
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <span class="bg-gray-900/70 text-white text-xs font-bold px-3 py-1 rounded uppercase tracking-wide">
                                                        {{ $item->status === 'claimed' ? 'Sedang Diklaim' : ($item->status === 'completed' ? 'Sudah Didonasikan' : 'Dibatalkan') }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="p-4 flex-1 flex flex-col">
                                                <p class="text-sm font-bold text-gray-700 line-clamp-1">{{ $item->title }}</p>
                                                <div class="mt-4 flex justify-between items-center">
                                                    <form action="{{ route('wishlist.destroy', $item) }}" method="POST">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="text-xs text-red-600 hover:underline font-medium">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Permintaan Publik (1/3) -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                        <div class="border-b border-gray-100 bg-white px-6 py-4 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-900">Permintaan Publik</h3>
                        </div>
                        <div class="p-6">
                            <p class="text-xs text-gray-500 mb-4 leading-relaxed">
                                Tidak menemukan barang yang Anda cari di katalog? Buat permintaan publik agar donatur yang memilikinya bisa langsung mendonasikannya kepada Anda.
                            </p>
                            
                            <a href="{{ route('wishlist-requests.create') }}" class="w-full flex items-center justify-center gap-2 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-lg shadow-sm transition mb-6">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Buat Permintaan Baru
                            </a>

                            <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-3">Permintaan Aktif Anda</h4>
                            
                            @if($myRequests->isEmpty())
                                <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 text-center">
                                    <p class="text-xs text-gray-500">Anda belum membuat permintaan apapun.</p>
                                </div>
                            @else
                                <div class="space-y-3 max-h-[400px] overflow-y-auto pr-1">
                                    @foreach($myRequests as $req)
                                        <div class="border {{ $req->is_fulfilled ? 'border-green-200 bg-green-50' : ($req->expires_at && $req->expires_at < now()->startOfDay() ? 'border-red-200 bg-red-50' : 'border-gray-200 bg-white') }} rounded-lg p-3 hover:shadow-sm transition">
                                            <div class="flex justify-between items-start mb-1">
                                                <a href="{{ route('wishlist-requests.show', $req) }}" class="text-sm font-bold text-gray-900 hover:text-teal-600 line-clamp-1">{{ $req->title }}</a>
                                                @if($req->is_fulfilled)
                                                    <span class="text-[10px] bg-green-200 text-green-800 font-bold px-1.5 py-0.5 rounded uppercase">Terpenuhi</span>
                                                @elseif($req->expires_at && $req->expires_at < now()->startOfDay())
                                                    <span class="text-[10px] bg-red-200 text-red-800 font-bold px-1.5 py-0.5 rounded uppercase">Expired</span>
                                                @else
                                                    <span class="text-[10px] bg-blue-100 text-blue-800 font-bold px-1.5 py-0.5 rounded uppercase">Aktif</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500 mb-2">{{ $req->category->name }}</p>
                                            
                                            <div class="flex justify-between items-center mt-2">
                                                <span class="text-[10px] text-gray-400">{{ $req->created_at->diffForHumans() }}</span>
                                                <form action="{{ route('wishlist-requests.destroy', $req) }}" method="POST" onsubmit="return confirm('Hapus permintaan ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>

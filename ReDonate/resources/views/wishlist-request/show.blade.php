<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Permintaan') }}
            </h2>
            <a href="{{ route('wishlist-requests.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">&larr; Kembali ke Daftar</a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="bg-teal-50 border-l-4 border-teal-500 p-4 mb-6 rounded shadow-sm">
                    <p class="text-sm text-teal-700 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded shadow-sm">
                    <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Kolom Kiri: Detail Permintaan -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
                        <div class="flex justify-between items-start mb-6">
                            <span class="inline-block px-3 py-1 bg-gray-100 text-gray-700 text-xs font-bold rounded-full uppercase tracking-wide">{{ $wishlistRequest->category->name }}</span>
                            
                            @if($wishlistRequest->is_fulfilled)
                                <span class="text-xs font-bold text-green-700 bg-green-100 px-3 py-1 rounded-full flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Terpenuhi
                                </span>
                            @elseif($wishlistRequest->expires_at && $wishlistRequest->expires_at < now()->startOfDay())
                                <span class="text-xs font-bold text-red-700 bg-red-100 px-3 py-1 rounded-full flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Kedaluwarsa
                                </span>
                            @else
                                <span class="text-xs font-bold text-blue-700 bg-blue-100 px-3 py-1 rounded-full flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Aktif
                                </span>
                            @endif
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-4 leading-tight">{{ $wishlistRequest->title }}</h1>
                        
                        <div class="prose prose-sm sm:prose max-w-none text-gray-600 mb-8 whitespace-pre-line">
                            {{ $wishlistRequest->description }}
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-gray-100 pt-6">
                            @if($wishlistRequest->condition_needed)
                                <div>
                                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Kondisi yang Diharapkan</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $wishlistRequest->condition_needed }}</p>
                                </div>
                            @endif
                            
                            <div>
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Tanggal Kedaluwarsa</p>
                                <p class="text-sm font-bold text-gray-900">{{ $wishlistRequest->expires_at ? $wishlistRequest->expires_at->format('d M Y') : 'Tidak ada batas waktu' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Info Peminta & Aksi -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Info Peminta -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wider">Informasi Peminta</h3>
                        <div class="flex items-center gap-4 mb-4">
                            <img src="{{ $wishlistRequest->user->avatar ? Storage::url($wishlistRequest->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($wishlistRequest->user->name).'&color=0D9488&background=CCFBF1' }}" alt="{{ $wishlistRequest->user->name }}" class="w-16 h-16 rounded-full object-cover shadow-sm ring-2 ring-gray-50">
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">{{ $wishlistRequest->user->name }}</h4>
                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $wishlistRequest->user->address ?? 'Lokasi tidak disebutkan' }}
                                </p>
                            </div>
                        </div>
                        @if($wishlistRequest->user->bio)
                            <p class="text-sm text-gray-600 italic bg-gray-50 p-3 rounded-lg">"{{ $wishlistRequest->user->bio }}"</p>
                        @endif
                    </div>

                    <!-- Aksi / Form Fulfill -->
                    <div id="respond" class="bg-white rounded-xl shadow-sm border border-teal-200 overflow-hidden relative">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-teal-400 to-teal-600"></div>
                        <div class="p-6">
                            @if($wishlistRequest->is_fulfilled)
                                <div class="text-center py-4">
                                    <div class="mx-auto w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">Permintaan Terpenuhi</h3>
                                    <p class="text-sm text-gray-600 mb-4">Seseorang telah mendonasikan barang untuk permintaan ini.</p>
                                    @if($wishlistRequest->fulfilledByItem)
                                        <a href="{{ route('items.show', $wishlistRequest->fulfilledByItem->slug) }}" class="inline-block text-sm text-teal-600 font-bold hover:underline">Lihat Barang yang Didonasikan &rarr;</a>
                                    @endif
                                </div>
                            @elseif($wishlistRequest->expires_at && $wishlistRequest->expires_at < now()->startOfDay())
                                <div class="text-center py-4">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">Kedaluwarsa</h3>
                                    <p class="text-sm text-gray-600">Permintaan ini sudah melewati batas waktu dan tidak dapat direspons lagi.</p>
                                </div>
                            @else
                                @auth
                                    @if(Auth::id() === $wishlistRequest->user_id)
                                        <div class="text-center">
                                            <p class="text-sm text-gray-600 mb-4">Ini adalah permintaan yang Anda buat sendiri. Bagikan link halaman ini ke orang lain agar permintaan Anda cepat terpenuhi!</p>
                                            <button type="button" onclick="navigator.clipboard.writeText(window.location.href); alert('Link berhasil disalin!')" class="w-full flex justify-center items-center gap-2 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-bold rounded-lg shadow-sm hover:bg-gray-50 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                                                Salin Link
                                            </button>
                                        </div>
                                    @else
                                        <h3 class="text-lg font-bold text-gray-900 mb-2">Bantu Penuhi Permintaan</h3>
                                        <p class="text-sm text-gray-600 mb-6">Apakah Anda memiliki barang kategori <strong>{{ $wishlistRequest->category->name }}</strong> yang sesuai dengan deskripsi ini?</p>
                                        
                                        @if($myActiveItems->isEmpty())
                                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-4">
                                                <p class="text-sm text-amber-800">Anda belum memiliki barang aktif di kategori <strong>{{ $wishlistRequest->category->name }}</strong> untuk didonasikan.</p>
                                            </div>
                                            <a href="{{ route('items.create') }}" class="w-full flex justify-center items-center gap-2 py-2.5 bg-teal-600 text-white text-sm font-bold rounded-lg shadow-sm hover:bg-teal-700 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                Upload Barang Baru
                                            </a>
                                        @else
                                            <form action="{{ route('wishlist-requests.fulfill', $wishlistRequest) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menautkan barang ini? Permintaan ini akan ditandai terpenuhi.')">
                                                @csrf
                                                <div class="mb-4">
                                                    <label for="item_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Barang Anda</label>
                                                    <select name="item_id" id="item_id" class="w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm" required>
                                                        <option value="">-- Pilih barang --</option>
                                                        @foreach($myActiveItems as $item)
                                                            <option value="{{ $item->id }}">{{ $item->title }} ({{ $item->condition }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <button type="submit" class="w-full flex justify-center items-center gap-2 py-2.5 bg-teal-600 text-white text-sm font-bold rounded-lg shadow-sm hover:bg-teal-700 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                                    Tautkan & Donasikan
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                @else
                                    <div class="text-center">
                                        <p class="text-sm text-gray-600 mb-4">Punya barang yang dicari? Silakan login terlebih dahulu untuk merespons permintaan ini.</p>
                                        <a href="{{ route('login') }}" class="w-full flex justify-center items-center py-2 bg-gray-900 text-white text-sm font-bold rounded-lg shadow-sm hover:bg-gray-800 transition">
                                            Login Sekarang
                                        </a>
                                    </div>
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

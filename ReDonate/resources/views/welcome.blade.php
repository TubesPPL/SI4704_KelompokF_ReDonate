<x-app-layout>
    <!-- Hero Section -->
    <div class="relative bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32 pt-20">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">Barang Bekasmu,</span>
                            <span class="block text-teal-600 xl:inline">Berkah untuk Mereka</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            ReDonate menghubungkan niat baik Anda dengan orang-orang yang membutuhkan. Donasikan pakaian, buku, elektronik, dan barang layak pakai lainnya dengan mudah, aman, dan transparan.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="{{ route('items.create') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 md:py-4 md:text-lg transition">
                                    Mulai Donasi
                                </a>
                            </div>
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                                <a href="{{ route('catalog.index') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-teal-700 bg-teal-100 hover:bg-teal-200 md:py-4 md:text-lg transition">
                                    Cari Barang
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-teal-50 flex items-center justify-center">
            <!-- Simple Illustration placeholder -->
            <svg class="h-64 w-64 text-teal-300" fill="currentColor" viewBox="0 0 24 24">
                <path d="M21 4H3a1 1 0 00-1 1v14a1 1 0 001 1h18a1 1 0 001-1V5a1 1 0 00-1-1zm-1 14H4V6h16v12z"/>
                <path d="M12 8l-4 4h3v4h2v-4h3l-4-4z"/>
            </svg>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="bg-teal-600 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-teal-500">
                <div class="p-4">
                    <p class="text-4xl font-extrabold text-white">{{ number_format($totalItems) }}</p>
                    <p class="mt-2 text-lg font-medium text-teal-100">Barang Didonasikan</p>
                </div>
                <div class="p-4">
                    <p class="text-4xl font-extrabold text-white">{{ number_format($totalDonors) }}</p>
                    <p class="mt-2 text-lg font-medium text-teal-100">Donatur Aktif</p>
                </div>
                <div class="p-4">
                    <p class="text-4xl font-extrabold text-white">{{ number_format($totalReceivers) }}</p>
                    <p class="mt-2 text-lg font-medium text-teal-100">Penerima Terbantu</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Banner (Jika Ada) -->
    @if($activeEvent)
    <div class="bg-amber-50 border-b border-amber-100">
        <div class="max-w-7xl mx-auto py-3 px-3 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between flex-wrap">
                <div class="w-0 flex-1 flex items-center">
                    <span class="flex p-2 rounded-lg bg-amber-500">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </span>
                    <p class="ml-3 font-medium text-amber-900 truncate">
                        <span class="md:hidden">Event Baru: {{ $activeEvent->title }}</span>
                        <span class="hidden md:inline">
                            <strong>Event Donasi Aktif!</strong> {{ $activeEvent->title }} - {{ $activeEvent->description }}
                        </span>
                    </p>
                </div>
                <div class="order-3 mt-2 flex-shrink-0 w-full sm:order-2 sm:mt-0 sm:w-auto">
                    <a href="#" class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-amber-600 bg-white hover:bg-amber-50 transition">
                        Ikut Berpartisipasi
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Cara Kerja -->
    <div id="how-it-works" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Cara Kerja ReDonate</h2>
                <p class="mt-4 text-lg text-gray-500">Hanya butuh 3 langkah mudah untuk mulai berbagi.</p>
            </div>

            <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-3">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-teal-100 text-teal-600">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <h3 class="mt-6 text-xl font-bold text-gray-900">1. Daftar & Upload</h3>
                    <p class="mt-2 text-gray-500">Buat akun gratis dan unggah foto beserta deskripsi barang yang ingin didonasikan.</p>
                </div>
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-teal-100 text-teal-600">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="mt-6 text-xl font-bold text-gray-900">2. Tunggu Klaim</h3>
                    <p class="mt-2 text-gray-500">Penerima yang membutuhkan akan mengajukan klaim untuk barang Anda.</p>
                </div>
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-teal-100 text-teal-600">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="mt-6 text-xl font-bold text-gray-900">3. Serah Terima</h3>
                    <p class="mt-2 text-gray-500">Setujui klaim dan lakukan serah terima barang (diantar atau diambil).</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Kategori Populer -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-extrabold text-gray-900 mb-8">Jelajahi Kategori</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($categories as $category)
                <a href="#" class="group relative bg-gray-50 rounded-xl p-6 hover:bg-teal-50 transition border border-transparent hover:border-teal-100 flex flex-col items-center text-center">
                    <div class="h-12 w-12 bg-white rounded-full flex items-center justify-center shadow-sm group-hover:scale-110 transition text-teal-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-semibold text-gray-900">{{ $category->name }}</h3>
                    <p class="text-xs text-gray-500 mt-1">{{ $category->items_count }} Barang</p>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Barang Terbaru -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-8">
                <h2 class="text-2xl font-extrabold text-gray-900">Donasi Terbaru</h2>
                <a href="#" class="text-teal-600 hover:text-teal-700 font-medium text-sm hidden sm:block">Lihat Semua Katalog &rarr;</a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($recentItems as $item)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition flex flex-col h-full">
                    <div class="aspect-w-16 aspect-h-9 relative bg-gray-200">
                        @if($item->images && count($item->images) > 0)
                            <img src="{{ Storage::url($item->images[0]) }}" class="object-cover w-full h-56" alt="{{ $item->title }}">
                        @else
                            <div class="w-full h-56 flex items-center justify-center text-gray-400">
                                <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        <div class="absolute top-3 left-3 bg-white/90 backdrop-blur text-gray-800 text-xs px-2 py-1 rounded font-semibold shadow-sm">
                            {{ $item->category->name }}
                        </div>
                        <div class="absolute top-3 right-3 bg-amber-100 text-amber-800 text-xs px-2 py-1 rounded font-semibold shadow-sm border border-amber-200">
                            {{ ucfirst(str_replace('_', ' ', $item->condition)) }}
                        </div>
                    </div>
                    
                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="font-bold text-xl text-gray-900 line-clamp-1 mb-1">{{ $item->title }}</h3>
                        <p class="text-gray-500 text-sm flex items-center gap-1 mb-4">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $item->location }}
                        </p>
                        
                        <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <img class="h-8 w-8 rounded-full object-cover" src="{{ $item->user->avatar ? Storage::url($item->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($item->user->name).'&color=0D9488&background=CCFBF1' }}" alt="{{ $item->user->name }}">
                                <div class="text-xs">
                                    <p class="text-gray-900 font-medium">{{ explode(' ', $item->user->name)[0] }}</p>
                                    <p class="text-gray-500">{{ $item->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <a href="#" class="px-4 py-2 bg-teal-50 text-teal-700 hover:bg-teal-600 hover:text-white rounded-lg text-sm font-semibold transition">
                                Klaim
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full bg-white rounded-lg p-12 text-center border border-gray-200">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada donasi</h3>
                    <p class="mt-1 text-sm text-gray-500">Jadilah yang pertama mendonasikan barang Anda.</p>
                </div>
                @endforelse
            </div>
            <div class="mt-8 text-center sm:hidden">
                <a href="#" class="text-teal-600 hover:text-teal-700 font-medium text-sm">Lihat Semua Katalog &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Barang Dibutuhkan (Wishlist Requests) -->
    <div class="py-16 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="text-2xl font-extrabold text-gray-900">Barang yang Dibutuhkan</h2>
                    <p class="text-gray-500 mt-2">Bantu penuhi permintaan barang dari mereka yang membutuhkan.</p>
                </div>
                <a href="{{ route('wishlist-requests.index') }}" class="text-teal-600 hover:text-teal-700 font-medium text-sm hidden sm:block">Lihat Semua Permintaan &rarr;</a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($wishlistRequests ?? [] as $request)
                    <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition flex flex-col p-5">
                        <div class="flex justify-between items-start mb-3">
                            <span class="inline-block px-2.5 py-1 bg-white border border-gray-200 text-gray-600 text-[10px] font-bold rounded uppercase">{{ $request->category->name }}</span>
                            @if($request->expires_at)
                                @php $daysLeft = now()->startOfDay()->diffInDays($request->expires_at, false); @endphp
                                <span class="text-[10px] font-bold text-gray-500 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Sisa {{ $daysLeft }} Hari
                                </span>
                            @endif
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2"><a href="{{ route('wishlist-requests.show', $request) }}" class="hover:text-teal-600">{{ $request->title }}</a></h3>
                        <p class="text-sm text-gray-600 mb-4 line-clamp-2 flex-1">{{ $request->description }}</p>
                        <div class="mt-auto pt-4 border-t border-gray-200 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <img src="{{ $request->user->avatar ? Storage::url($request->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($request->user->name).'&color=0D9488&background=CCFBF1' }}" class="w-6 h-6 rounded-full object-cover">
                                <span class="text-xs font-bold text-gray-700">{{ explode(' ', $request->user->name)[0] }}</span>
                            </div>
                            <a href="{{ route('wishlist-requests.show', $request) }}" class="text-xs font-bold text-teal-600 hover:text-teal-800 bg-teal-100 hover:bg-teal-200 px-3 py-1.5 rounded transition">Respons</a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-gray-50 rounded-lg p-8 text-center border border-gray-200">
                        <p class="text-sm text-gray-500">Saat ini tidak ada permintaan barang yang sedang aktif.</p>
                    </div>
                @endforelse
            </div>
            <div class="mt-6 text-center sm:hidden">
                <a href="{{ route('wishlist-requests.index') }}" class="text-teal-600 hover:text-teal-700 font-medium text-sm">Lihat Semua Permintaan &rarr;</a>
            </div>
        </div>
    </div>

    <!-- CTA Bawah -->
    <div class="bg-teal-700">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
            <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                <span class="block">Siap untuk berbagi?</span>
                <span class="block text-teal-200">Daftar sekarang, gratis.</span>
            </h2>
            <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                @guest
                <div class="inline-flex rounded-md shadow">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-teal-600 bg-white hover:bg-gray-50 transition">
                        Daftar Akun
                    </a>
                </div>
                @endguest
                <div class="ml-3 inline-flex rounded-md shadow">
                    <a href="#how-it-works" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-teal-600 hover:bg-teal-500 transition border-white">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

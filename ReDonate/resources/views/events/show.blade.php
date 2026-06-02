<x-app-layout>
    <div class="bg-white">
        <!-- Hero Banner -->
        <div class="relative bg-gray-900 h-[400px] md:h-[500px]">
            @if($event->banner)
                <img src="{{ Storage::url($event->banner) }}" alt="{{ $event->title }}" class="w-full h-full object-cover opacity-60">
            @else
                <div class="w-full h-full flex items-center justify-center opacity-40">
                    <svg class="h-32 w-32 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            @endif
            
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent"></div>
            
            <div class="absolute bottom-0 left-0 right-0 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
                <div class="flex items-center gap-3 mb-4">
                    @if($event->status === 'active')
                        <span class="px-3 py-1 bg-teal-500 text-white text-sm font-bold rounded-full shadow-sm">Sedang Berlangsung</span>
                    @elseif($event->status === 'upcoming')
                        <span class="px-3 py-1 bg-amber-500 text-white text-sm font-bold rounded-full shadow-sm">Segera Hadir</span>
                    @else
                        <span class="px-3 py-1 bg-gray-500 text-white text-sm font-bold rounded-full shadow-sm">Telah Selesai</span>
                    @endif
                    <span class="text-gray-300 text-sm font-medium flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ $event->start_date->format('d M') }} - {{ $event->end_date->format('d M Y') }}
                    </span>
                </div>
                
                <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6 leading-tight">{{ $event->title }}</h1>
                
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <!-- Progress Card Dark -->
                    <div class="bg-gray-800/80 backdrop-blur border border-gray-700 rounded-xl p-5 w-full md:w-[400px]">
                        <p class="text-gray-400 text-sm font-semibold mb-2 uppercase tracking-wide">Progress Donasi</p>
                        <div class="flex items-end gap-2 mb-3">
                            <span class="text-3xl font-extrabold text-teal-400">{{ $event->items_count }}</span>
                            <span class="text-gray-400 text-lg mb-1">/ {{ $event->target_items }} Barang</span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-3 overflow-hidden relative">
                            @php
                                $percentage = $event->target_items > 0 ? min(100, round(($event->items_count / $event->target_items) * 100)) : 0;
                            @endphp
                            <div class="bg-teal-500 h-3 rounded-full relative overflow-hidden" style="width: {{ $percentage }}%">
                                <div class="absolute top-0 bottom-0 left-0 right-0 bg-white/20" style="animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    @if($event->status === 'active')
                        <a href="{{ route('items.create', ['event_id' => $event->id]) }}" class="inline-flex justify-center items-center px-8 py-4 bg-teal-500 hover:bg-teal-400 text-gray-900 text-lg font-bold rounded-xl shadow-lg hover:shadow-teal-500/30 transition duration-300">
                            Donasikan ke Event Ini
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-12">
            
            <!-- Left Content -->
            <div class="flex-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Tentang Event Ini</h2>
                    <div class="prose prose-teal max-w-none text-gray-600 leading-relaxed">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                </div>

                <!-- Grid Items -->
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Barang Terkumpul ({{ $event->items_count }})</h2>
                @if($items->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($items as $item)
                            <!-- Menggunakan grid partial yang sama dari katalog untuk konsistensi, atau buat markup serupa -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition flex flex-col h-full group">
                                <div class="aspect-w-4 aspect-h-3 relative bg-gray-100 overflow-hidden">
                                    @if($item->images && count($item->images) > 0)
                                        <img src="{{ Storage::url($item->images[0]) }}" class="object-cover w-full h-full group-hover:scale-105 transition duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                    <div class="absolute top-2 left-2 bg-white/90 backdrop-blur-sm text-gray-800 text-xs px-2 py-1 rounded font-semibold shadow-sm">
                                        {{ $item->category->name }}
                                    </div>
                                </div>
                                <div class="p-4 flex-1 flex flex-col">
                                    <h3 class="font-bold text-md text-gray-900 line-clamp-2 mb-1 group-hover:text-teal-600 transition">
                                        <a href="{{ route('items.show', $item->slug) }}">{{ $item->title }}</a>
                                    </h3>
                                    <div class="mt-auto pt-3 border-t border-gray-100 flex items-center gap-2">
                                        <img class="h-6 w-6 rounded-full object-cover" src="{{ $item->user->avatar ? Storage::url($item->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($item->user->name).'&color=0D9488&background=CCFBF1' }}" alt="{{ $item->user->name }}">
                                        <div class="text-[10px] text-gray-500 font-medium">Dari: {{ explode(' ', $item->user->name)[0] }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-8">
                        {{ $items->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-xl p-12 text-center border border-gray-200">
                        <div class="mx-auto h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Belum Ada Barang</h3>
                        <p class="mt-1 text-gray-500">Jadilah orang pertama yang mendonasikan barang untuk event ini!</p>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="w-full lg:w-80 flex-shrink-0 space-y-6">
                <!-- Penyelenggara -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Penyelenggara</h3>
                    <div class="flex items-center gap-3">
                        <img class="h-12 w-12 rounded-full object-cover shadow-sm" src="{{ $event->creator->avatar ? Storage::url($event->creator->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($event->creator->name).'&color=0D9488&background=CCFBF1' }}">
                        <div>
                            <p class="font-bold text-gray-900">{{ $event->creator->name }}</p>
                            <p class="text-xs text-gray-500 flex items-center gap-1"><svg class="w-3 h-3 text-teal-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Verified Organizer</p>
                        </div>
                    </div>
                </div>

                <!-- Kategori Dibutuhkan -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Kategori yang Dibutuhkan</h3>
                    <div class="flex flex-wrap gap-2">
                        @forelse($event->categories as $cat)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                {{ $cat->name }}
                            </span>
                        @empty
                            <span class="text-sm text-gray-500">Semua kategori diterima</span>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

@if($items->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($items as $item)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition flex flex-col h-full group">
                <div class="aspect-w-4 aspect-h-3 relative bg-gray-100 overflow-hidden">
                    @if($item->images && count($item->images) > 0)
                        <img src="{{ Storage::url($item->images[0]) }}" class="object-cover w-full h-full group-hover:scale-105 transition duration-300" alt="{{ $item->title }}">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                    <div class="absolute top-2 left-2 bg-white/90 backdrop-blur-sm text-gray-800 text-xs px-2 py-1 rounded font-semibold shadow-sm">
                        {{ $item->category->name }}
                    </div>
                    <div class="absolute top-2 right-2 bg-amber-100 text-amber-800 text-xs px-2 py-1 rounded font-semibold shadow-sm border border-amber-200">
                        {{ ucfirst(str_replace('_', ' ', $item->condition)) }}
                    </div>
                    
                    <!-- Wishlist Button -->
                    <div class="absolute bottom-2 right-2" x-data="{ 
                        wishlisted: {{ Auth::check() && Auth::user()->wishlistedItems->contains($item->id) ? 'true' : 'false' }},
                        count: {{ $item->wishlistedByUsers()->count() }},
                        loading: false,
                        toggle() {
                            @if(Auth::check())
                                if ({{ Auth::id() }} === {{ $item->user_id }}) return alert('Anda tidak bisa mewishlist barang sendiri.');
                                this.loading = true;
                                fetch('{{ route('wishlist.toggle', $item->id) }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    this.loading = false;
                                    if(data.success) {
                                        this.wishlisted = data.wishlisted;
                                        this.count = data.count;
                                    } else if(data.error) {
                                        alert(data.error);
                                    }
                                }).catch(() => this.loading = false);
                            @else
                                window.location.href = '{{ route('login') }}';
                            @endif
                        }
                    }">
                        <button @click.prevent="toggle()" class="flex items-center gap-1.5 px-2 py-1.5 bg-white/90 backdrop-blur rounded-full shadow-sm hover:scale-105 transition">
                            <svg class="w-4 h-4 transition-colors duration-300" :class="wishlisted ? 'fill-red-500 text-red-500' : 'fill-none text-gray-500'" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span class="text-[10px] font-bold text-gray-700" x-text="count > 0 ? count : ''"></span>
                        </button>
                    </div>
                </div>
                
                <div class="p-4 flex-1 flex flex-col">
                    <h3 class="font-bold text-lg text-gray-900 line-clamp-2 mb-1 group-hover:text-teal-600 transition">{{ $item->title }}</h3>
                    <p class="text-gray-500 text-xs flex items-center gap-1 mb-3">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $item->location }}
                    </p>
                    
                    <div class="mt-auto pt-3 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <img class="h-6 w-6 rounded-full object-cover" src="{{ $item->user->avatar ? Storage::url($item->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($item->user->name).'&color=0D9488&background=CCFBF1' }}" alt="{{ $item->user->name }}">
                            <div class="text-[10px] text-gray-500">
                                <span class="font-medium text-gray-900">{{ explode(' ', $item->user->name)[0] }}</span> • {{ $item->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <a href="{{ route('items.show', $item->slug) }}" class="px-3 py-1.5 bg-teal-50 text-teal-700 hover:bg-teal-600 hover:text-white rounded text-xs font-semibold transition">
                            Lihat
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="mt-8 pagination-container">
        {{ $items->links() }}
    </div>
@else
    <div class="bg-white rounded-xl p-12 text-center border border-gray-200">
        <div class="mx-auto h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
            <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900">Barang tidak ditemukan</h3>
        <p class="mt-1 text-gray-500">Coba sesuaikan filter pencarian Anda untuk melihat lebih banyak hasil.</p>
        <button type="button" @click="resetFilters()" class="mt-4 text-teal-600 hover:text-teal-800 font-medium text-sm">
            Hapus Semua Filter
        </button>
    </div>
@endif

<x-app-layout>
    <div class="py-12 bg-gray-50" x-data="{ showLightbox: false, showClaimModal: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row">
                
                <!-- Gambar Gallery -->
                <div class="w-full md:w-1/2 bg-gray-100 p-6 flex flex-col gap-4">
                    @if($item->images && count($item->images) > 0)
                        <!-- Main Image -->
                        <div class="aspect-w-4 aspect-h-3 rounded-xl overflow-hidden shadow-sm bg-white relative" x-data="{ mainImage: '{{ Storage::url($item->images[0]) }}' }">
                            <img :src="mainImage" alt="{{ $item->title }}" @click="showLightbox = true" class="object-cover w-full h-full cursor-pointer hover:opacity-95 transition">
                            
                            <!-- Thumbnails -->
                            <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 px-4 overflow-x-auto">
                                @foreach($item->images as $img)
                                    <button @click="mainImage = '{{ Storage::url($img) }}'" class="h-16 w-16 flex-shrink-0 rounded-lg overflow-hidden border-2 transition focus:outline-none" :class="mainImage === '{{ Storage::url($img) }}' ? 'border-teal-500 shadow-md' : 'border-white opacity-70 hover:opacity-100'">
                                        <img src="{{ Storage::url($img) }}" class="object-cover w-full h-full">
                                    </button>
                                @endforeach
                            </div>

                            <!-- Lightbox -->
                            <div x-show="showLightbox" class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-90 backdrop-blur-sm" x-transition.opacity style="display: none;" @keydown.escape.window="showLightbox = false">
                                <button @click="showLightbox = false" class="absolute top-6 right-6 text-white hover:text-gray-300 focus:outline-none p-2 bg-white/10 rounded-full hover:bg-white/20 transition">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                                <img :src="mainImage" class="max-h-[90vh] max-w-[90vw] object-contain shadow-2xl rounded-lg" @click.stop>
                            </div>
                        </div>
                    @else
                        <div class="aspect-w-4 aspect-h-3 rounded-xl overflow-hidden shadow-sm bg-gray-200 flex items-center justify-center text-gray-400">
                            <svg class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                </div>

                <!-- Info Barang -->
                <div class="w-full md:w-1/2 p-8 flex flex-col">
                    <!-- ... info sections ... -->
                    @if (session('success'))
                        <div class="bg-teal-50 border-l-4 border-teal-500 p-4 mb-4 rounded shadow-sm">
                            <p class="text-sm text-teal-700 font-medium">{{ session('success') }}</p>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded shadow-sm">
                            <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                        </div>
                    @endif

                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-800 mb-2">
                                {{ $item->category->name }}
                            </span>
                            <h1 class="text-3xl font-extrabold text-gray-900 leading-tight">{{ $item->title }}</h1>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="flex items-center justify-end gap-3 mb-2">
                                <p class="text-sm text-gray-500 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    {{ number_format($item->views) }}
                                </p>
                                
                                <div x-data="{ 
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
                                    <button @click="toggle" class="flex items-center gap-1.5 px-3 py-1 bg-gray-50 border border-gray-200 rounded-full hover:bg-gray-100 transition shadow-sm" :class="{'opacity-50 cursor-not-allowed': loading}" :disabled="loading">
                                        <svg class="w-4 h-4 transition-colors duration-300" :class="wishlisted ? 'fill-red-500 text-red-500' : 'fill-none text-gray-500'" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        <span class="text-xs font-bold text-gray-700" x-text="count"></span>
                                    </button>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">{{ $item->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 py-6 border-y border-gray-100 my-4">
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Kondisi</p>
                            <p class="text-base font-bold text-gray-900">{{ ucfirst(str_replace('_', ' ', $item->condition)) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Jumlah</p>
                            <p class="text-base font-bold text-gray-900">{{ $item->quantity }} Pcs</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Metode Penyerahan</p>
                            <p class="text-base font-bold text-gray-900">
                                @if($item->delivery_method === 'pickup') Diambil Penerima
                                @elseif($item->delivery_method === 'delivery') Diantar Donatur
                                @else Bisa Keduanya @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Lokasi</p>
                            <p class="text-base font-bold text-gray-900 flex items-center gap-1">
                                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $item->location }}
                            </p>
                        </div>
                    </div>

                    <div class="mb-6 flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Deskripsi Barang</h3>
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line text-sm">{{ $item->description }}</p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 mt-auto flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <img class="h-12 w-12 rounded-full object-cover shadow-sm border border-white" src="{{ $item->user->avatar ? Storage::url($item->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($item->user->name).'&color=0D9488&background=CCFBF1' }}" alt="{{ $item->user->name }}">
                            <div>
                                <p class="text-sm text-gray-500">Didonasikan oleh</p>
                                <a href="{{ route('profile.show', $item->user->id) }}" class="font-bold text-gray-900 hover:text-teal-600 transition">{{ $item->user->name }}</a>
                            </div>
                        </div>
                        <a href="{{ route('profile.show', $item->user->id) }}" class="text-sm font-medium text-teal-600 hover:text-teal-800 bg-teal-50 px-3 py-1.5 rounded-lg transition">
                            Lihat Profil
                        </a>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mt-4">
                        @if($item->status === 'active')
                            @auth
                                @if(Auth::id() === $item->user_id)
                                    <a href="{{ route('items.edit', $item->slug) }}" class="w-full flex justify-center py-3 px-4 border border-teal-600 text-teal-600 rounded-lg shadow-sm font-bold text-lg hover:bg-teal-50 focus:outline-none transition">
                                        Edit Donasi Anda
                                    </a>
                                @elseif($userClaim)
                                    <div class="w-full flex flex-col items-center justify-center py-4 px-4 border border-teal-200 rounded-lg shadow-sm bg-teal-50">
                                        <p class="font-bold text-teal-800 mb-1">Anda sudah mengajukan klaim!</p>
                                        <p class="text-sm text-teal-600">Status saat ini: <span class="font-bold uppercase">{{ $userClaim->status }}</span></p>
                                        <a href="{{ route('recipient.dashboard') }}" class="text-xs text-teal-700 underline mt-2 hover:text-teal-900">Lihat Dashboard Klaim</a>
                                    </div>
                                @else
                                    <button type="button" @click="showClaimModal = true" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-lg shadow-sm text-lg font-bold text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition">
                                        Ajukan Klaim Barang
                                    </button>
                                    <p class="text-center text-xs text-gray-500 mt-2">Dengan mengajukan klaim, Anda setuju dengan Syarat & Ketentuan ReDonate.</p>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-lg shadow-sm text-lg font-bold text-white bg-teal-600 hover:bg-teal-700 focus:outline-none transition">
                                    Login untuk Mengklaim
                                </a>
                            @endauth
                        @else
                            <div class="w-full flex justify-center py-4 px-4 border border-transparent rounded-lg shadow-sm text-lg font-bold text-gray-700 bg-gray-200 cursor-not-allowed">
                                @if($item->status === 'claimed') Barang Sedang Diklaim
                                @elseif($item->status === 'completed') Donasi Telah Selesai
                                @else Tidak Tersedia @endif
                            </div>
                        @endif
                    </div>

                    <!-- Laporkan Barang -->
                    @auth
                        @if(Auth::id() !== $item->user_id)
                            <div class="mt-6 text-center">
                                <a href="{{ route('report.create', ['type' => 'item', 'id' => $item->id]) }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-red-500 hover:text-red-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    Laporkan barang ini
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        <!-- Modal Form Klaim -->
        @auth
        @if($item->status === 'active' && Auth::id() !== $item->user_id && !$userClaim)
        <div x-show="showClaimModal" class="fixed inset-0 z-50 flex items-center justify-center px-4" x-transition.opacity style="display: none;">
            <div class="absolute inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm" @click="showClaimModal = false"></div>
            
            <div class="bg-white rounded-2xl shadow-xl z-10 w-full max-w-lg overflow-hidden flex flex-col" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900">Pengajuan Klaim Barang</h3>
                    <button @click="showClaimModal = false" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form action="{{ route('claims.store', $item->id) }}" method="POST" class="p-6">
                    @csrf
                    
                    <!-- Ringkasan Barang -->
                    <div class="flex items-center gap-4 mb-6 bg-gray-50 p-3 rounded-lg border border-gray-100">
                        @if($item->images && count($item->images) > 0)
                            <img src="{{ Storage::url($item->images[0]) }}" class="h-16 w-16 rounded-md object-cover border border-gray-200">
                        @else
                            <div class="h-16 w-16 bg-gray-200 rounded-md flex items-center justify-center"><svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                        @endif
                        <div>
                            <p class="font-bold text-gray-900 line-clamp-1">{{ $item->title }}</p>
                            <p class="text-xs text-gray-500">Milik: {{ $item->user->name }}</p>
                        </div>
                    </div>

                    <!-- Warning Data Profil -->
                    @if(empty(Auth::user()->phone) || empty(Auth::user()->address))
                        <div class="bg-amber-50 border-l-4 border-amber-500 p-3 mb-5 rounded shadow-sm">
                            <p class="text-xs text-amber-800">
                                <span class="font-bold">Perhatian:</span> Data profil kamu (No. HP/Alamat) belum lengkap. Pastikan donatur dapat menghubungimu. 
                                <a href="{{ route('profile.edit') }}" class="underline font-bold text-amber-900">Lengkapi Profil</a>
                            </p>
                        </div>
                    @else
                        <div class="mb-4">
                            <p class="text-xs text-gray-500 mb-1">Data Kontak Anda yang dikirim ke Donatur:</p>
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }} ({{ Auth::user()->phone }})</p>
                            <p class="text-sm text-gray-700">{{ Auth::user()->address }}</p>
                        </div>
                    @endif

                    <!-- Form Input -->
                    <div class="mb-5" x-data="{ messageLength: 0 }">
                        <label for="message" class="block text-sm font-semibold text-gray-900 mb-2">Kenapa kamu membutuhkan barang ini? <span class="text-red-500">*</span></label>
                        <textarea id="message" name="message" rows="4" x-on:input="messageLength = $event.target.value.length" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-teal-500 focus:ring-teal-500" placeholder="Ceritakan secara jujur mengapa kamu sangat terbantu dengan barang ini..." required minlength="20" maxlength="500"></textarea>
                        <div class="flex justify-between mt-1">
                            <p class="text-xs text-gray-500">Minimal 20 karakter.</p>
                            <p class="text-xs font-medium" :class="messageLength < 20 ? 'text-red-500' : 'text-green-600'" x-text="messageLength + '/500'"></p>
                        </div>
                    </div>

                    @if($item->delivery_method === 'pickup' || $item->delivery_method === 'both')
                    <div class="mb-6">
                        <label for="pickup_date" class="block text-sm font-semibold text-gray-900 mb-2">Kapan bisa diambil/diterima? (Opsional)</label>
                        <input type="date" id="pickup_date" name="pickup_date" min="{{ date('Y-m-d') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm">
                    </div>
                    @endif

                    <div class="mt-6 pt-4 border-t border-gray-100 flex gap-3 justify-end">
                        <button type="button" @click="showClaimModal = false" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Batal</button>
                        <button type="submit" class="px-6 py-2 bg-teal-600 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition">Kirim Pengajuan Klaim</button>
                    </div>
                </form>
            </div>
        </div>
        @endif
        @endauth
    </div>
</x-app-layout>

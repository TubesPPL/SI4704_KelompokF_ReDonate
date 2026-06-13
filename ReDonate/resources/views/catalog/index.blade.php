<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Katalog Donasi') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50" x-data="catalogFilter()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-8">
                
                <!-- Sidebar Filter -->
                <div class="w-full md:w-64 flex-shrink-0">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sticky top-20">
                        <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-100">
                            <h3 class="font-bold text-gray-900 text-lg">Filter</h3>
                            <button @click="resetFilters()" class="text-xs text-teal-600 font-medium hover:text-teal-800">Reset</button>
                        </div>

                        <!-- Kategori -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-sm text-gray-900 mb-3">Kategori</h4>
                            <div class="space-y-2 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                                @foreach($categories as $cat)
                                <label class="flex items-center">
                                    <input type="checkbox" value="{{ $cat->id }}" x-model="filters.categories" @change="fetchItems()" class="rounded border-gray-300 text-teal-600 shadow-sm focus:border-teal-300 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">{{ $cat->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Kondisi -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-sm text-gray-900 mb-3">Kondisi</h4>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" value="new" x-model="filters.conditions" @change="fetchItems()" class="rounded border-gray-300 text-teal-600 shadow-sm focus:border-teal-300 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">Baru (New)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="like_new" x-model="filters.conditions" @change="fetchItems()" class="rounded border-gray-300 text-teal-600 shadow-sm focus:border-teal-300 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">Seperti Baru</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="good" x-model="filters.conditions" @change="fetchItems()" class="rounded border-gray-300 text-teal-600 shadow-sm focus:border-teal-300 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">Baik (Good)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" value="fair" x-model="filters.conditions" @change="fetchItems()" class="rounded border-gray-300 text-teal-600 shadow-sm focus:border-teal-300 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">Layak Pakai</span>
                                </label>
                            </div>
                        </div>

                        <!-- Pengiriman -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-sm text-gray-900 mb-3">Metode Penyerahan</h4>
                            <select x-model="filters.delivery" @change="fetchItems()" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500">
                                <option value="all">Semua Metode</option>
                                <option value="pickup">Bisa Diambil (Pickup)</option>
                                <option value="delivery">Bisa Diantar (Delivery)</option>
                            </select>
                        </div>

                        <!-- Lokasi -->
                        <div>
                            <h4 class="font-semibold text-sm text-gray-900 mb-3">Lokasi (Kota/Kec)</h4>
                            <div class="relative">
                                <input type="text" x-model="filters.location" @input.debounce.500ms="fetchItems()" placeholder="Cari lokasi..." class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 pl-8">
                                <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="flex-1">
                    <!-- Top Bar -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="relative w-full sm:w-96">
                            <input type="text" x-model="filters.q" @input.debounce.500ms="fetchItems()" placeholder="Cari nama barang..." class="w-full border-gray-300 rounded-lg shadow-sm focus:border-teal-500 focus:ring-teal-500 pl-10">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <span class="text-sm text-gray-500 whitespace-nowrap">Urutkan:</span>
                            <select x-model="filters.sort" @change="fetchItems()" class="text-sm border-gray-300 rounded-lg shadow-sm focus:border-teal-500 focus:ring-teal-500 border-none bg-gray-50 font-medium">
                                <option value="newest">Terbaru</option>
                                <option value="oldest">Terlama</option>
                                <option value="popular">Paling Banyak Dilihat</option>
                            </select>
                        </div>
                    </div>

                    <!-- Active Filters Tags -->
                    <div class="flex flex-wrap gap-2 mb-6" x-show="hasActiveFilters()">
                        <template x-for="(catId, index) in filters.categories" :key="'cat-'+index">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                Kategori <button type="button" @click="filters.categories.splice(index, 1); fetchItems();" class="ml-1.5 inline-flex text-teal-500 hover:text-teal-700 focus:outline-none"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></button>
                            </span>
                        </template>
                        <template x-for="(cond, index) in filters.conditions" :key="'cond-'+index">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                Kondisi <button type="button" @click="filters.conditions.splice(index, 1); fetchItems();" class="ml-1.5 inline-flex text-amber-500 hover:text-amber-700 focus:outline-none"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></button>
                            </span>
                        </template>
                        <template x-if="filters.delivery !== 'all'">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Penyerahan <button type="button" @click="filters.delivery = 'all'; fetchItems();" class="ml-1.5 inline-flex text-blue-500 hover:text-blue-700 focus:outline-none"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></button>
                            </span>
                        </template>
                        <template x-if="filters.location !== ''">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                Lokasi <button type="button" @click="filters.location = ''; fetchItems();" class="ml-1.5 inline-flex text-purple-500 hover:text-purple-700 focus:outline-none"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></button>
                            </span>
                        </template>
                    </div>

                    <!-- Loader -->
                    <div x-show="loading" class="flex justify-center items-center py-20" style="display: none;">
                        <svg class="animate-spin h-10 w-10 text-teal-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <!-- Grid Container (diisi oleh AJAX) -->
                    <div id="catalog-grid" x-show="!loading">
                        @include('catalog.partials.grid')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>

    <script>
        function catalogFilter() {
            return {
                loading: false,
                filters: {
                    categories: [],
                    conditions: [],
                    delivery: 'all',
                    location: '',
                    q: '',
                    sort: 'newest'
                },
                init() {
                    this.bindPaginationLinks();
                },
                bindPaginationLinks() {
                    this.$nextTick(() => {
                        const links = document.querySelectorAll('.pagination-container a');
                        links.forEach(link => {
                            link.addEventListener('click', (e) => {
                                e.preventDefault();
                                this.fetchUrl(link.href);
                                window.scrollTo({ top: 0, behavior: 'smooth' });
                            });
                        });
                    });
                },
                hasActiveFilters() {
                    return this.filters.categories.length > 0 || 
                           this.filters.conditions.length > 0 || 
                           this.filters.delivery !== 'all' || 
                           this.filters.location !== '';
                },
                resetFilters() {
                    this.filters.categories = [];
                    this.filters.conditions = [];
                    this.filters.delivery = 'all';
                    this.filters.location = '';
                    this.filters.q = '';
                    this.fetchItems();
                },
                fetchItems() {
                    // Build query string
                    let params = new URLSearchParams();
                    if(this.filters.categories.length) params.append('categories', this.filters.categories.join(','));
                    if(this.filters.conditions.length) params.append('conditions', this.filters.conditions.join(','));
                    if(this.filters.delivery !== 'all') params.append('delivery', this.filters.delivery);
                    if(this.filters.location) params.append('location', this.filters.location);
                    if(this.filters.q) params.append('q', this.filters.q);
                    if(this.filters.sort !== 'newest') params.append('sort', this.filters.sort);
                    
                    let url = '{{ route("catalog.index") }}?' + params.toString();
                    this.fetchUrl(url);
                },
                fetchUrl(url) {
                    this.loading = true;
                    // Ubah URL di address bar tanpa reload
                    window.history.pushState({}, '', url);

                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('catalog-grid').innerHTML = html;
                        this.bindPaginationLinks();
                        this.loading = false;
                    })
                    .catch(error => {
                        console.error('Error fetching catalog:', error);
                        this.loading = false;
                    });
                }
            }
        }
    </script>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Event Kampanye Donasi Baru') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50" x-data="eventForm()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col lg:flex-row gap-8">
                @csrf
                
                <!-- Main Form Form -->
                <div class="flex-1 space-y-6">
                    <div class="bg-white p-6 shadow-sm sm:rounded-2xl border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Informasi Event</h3>
                        
                        <!-- Judul -->
                        <div class="mb-4">
                            <x-input-label for="title" value="Judul Event *" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" x-model="title" required placeholder="Misal: Kampanye Pakaian Layak Pakai Ramadhan" />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <x-input-label for="description" value="Deskripsi Event *" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm" required placeholder="Jelaskan tujuan event, siapa penerimanya, dan alasan kenapa orang harus berdonasi..."></textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <!-- Banner Upload -->
                        <div class="mb-4">
                            <x-input-label value="Banner Event *" class="mb-2" />
                            <div class="relative h-48 rounded-xl border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden group hover:border-teal-500 transition cursor-pointer" @click="$refs.bannerInput.click()">
                                <template x-if="bannerUrl">
                                    <img :src="bannerUrl" class="absolute inset-0 w-full h-full object-cover">
                                </template>
                                <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center text-white opacity-0 group-hover:opacity-100 transition" :class="!bannerUrl ? 'opacity-100 bg-transparent text-gray-500' : ''">
                                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    <span class="text-sm font-medium">Klik untuk Upload Banner</span>
                                </div>
                                <input type="file" name="banner" x-ref="bannerInput" @change="handleBannerSelect" class="hidden" accept="image/*" required>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('banner')" />
                        </div>
                    </div>

                    <div class="bg-white p-6 shadow-sm sm:rounded-2xl border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Target & Periode</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <x-input-label for="start_date" value="Tanggal Mulai *" />
                                <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full text-sm" x-model="startDate" min="{{ date('Y-m-d') }}" required />
                                <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                            </div>
                            <div>
                                <x-input-label for="end_date" value="Tanggal Selesai *" />
                                <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full text-sm" x-model="endDate" :min="startDate || '{{ date('Y-m-d') }}'" required />
                                <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="target_items" value="Target Jumlah Barang Terkumpul *" />
                            <x-text-input id="target_items" name="target_items" type="number" min="1" class="mt-1 block w-full" x-model="target" required />
                            <x-input-error class="mt-2" :messages="$errors->get('target_items')" />
                        </div>
                    </div>

                    <div class="bg-white p-6 shadow-sm sm:rounded-2xl border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Kategori yang Dibutuhkan</h3>
                        <p class="text-sm text-gray-500 mb-3">Pilih satu atau beberapa kategori barang yang ditargetkan pada event ini.</p>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($categories as $cat)
                                <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-teal-50 transition" :class="categories.includes('{{ $cat->id }}') ? 'border-teal-500 bg-teal-50 ring-1 ring-teal-500' : ''">
                                    <input type="checkbox" name="categories[]" value="{{ $cat->id }}" x-model="categories" class="rounded border-gray-300 text-teal-600 shadow-sm focus:border-teal-300 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm font-medium text-gray-700">{{ $cat->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('categories')" />
                    </div>
                </div>

                <!-- Sidebar Preview -->
                <div class="w-full lg:w-1/3">
                    <div class="sticky top-20">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Preview Tampilan Card</h3>
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                            <div class="aspect-w-4 aspect-h-3 relative bg-gray-200 overflow-hidden">
                                <template x-if="bannerUrl">
                                    <img :src="bannerUrl" class="object-cover w-full h-full">
                                </template>
                                <template x-if="!bannerUrl">
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">Banner</div>
                                </template>
                                <div class="absolute top-3 left-3">
                                    <span class="px-2.5 py-1 bg-amber-500 text-white text-xs font-bold rounded-full shadow-sm">Preview Event</span>
                                </div>
                            </div>
                            
                            <div class="p-5 flex flex-col">
                                <h3 class="font-bold text-xl text-gray-900 line-clamp-2 mb-2" x-text="title || 'Judul Event'"></h3>
                                
                                <p class="text-sm text-gray-500 flex items-center gap-1.5 mb-4">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span x-text="startDate ? new Date(startDate).toLocaleDateString('id-ID', {day: 'numeric', month: 'short'}) : 'Mulai'"></span> - 
                                    <span x-text="endDate ? new Date(endDate).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year:'numeric'}) : 'Selesai'"></span>
                                </p>

                                <div class="mt-auto mb-5">
                                    <div class="flex justify-between text-xs font-bold mb-1">
                                        <span class="text-teal-600">0 Terkumpul</span>
                                        <span class="text-gray-500">Target: <span x-text="target || 0"></span></span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-teal-500 h-2.5 rounded-full w-0"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-6">
                            <button type="submit" class="w-full bg-teal-600 text-white font-bold py-3 px-4 rounded-xl shadow-lg hover:bg-teal-700 hover:shadow-teal-500/30 transition duration-300">
                                Buat Event Sekarang
                            </button>
                            <a href="{{ route('events.index') }}" class="block text-center mt-3 text-sm text-gray-500 hover:text-gray-700">Batal</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function eventForm() {
            return {
                title: '',
                startDate: '',
                endDate: '',
                target: '',
                categories: [],
                bannerUrl: null,
                
                handleBannerSelect(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.bannerUrl = URL.createObjectURL(file);
                    }
                }
            }
        }
    </script>
</x-app-layout>

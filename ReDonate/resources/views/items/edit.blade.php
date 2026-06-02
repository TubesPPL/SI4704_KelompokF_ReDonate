<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Barang: ') . $item->title }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="itemFormEdit()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('items.update', $item->slug) }}" method="POST" enctype="multipart/form-data" id="itemForm" class="flex flex-col lg:flex-row gap-8">
                @csrf
                @method('PUT')
                
                <!-- Main Form Form -->
                <div class="flex-1 space-y-6">
                    <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Informasi Barang</h3>
                        
                        <!-- Judul -->
                        <div class="mb-4">
                            <x-input-label for="title" value="Judul Barang *" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" x-model="title" required />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <!-- Kategori & Kondisi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="category_id" value="Kategori *" />
                                <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm" x-model="category" required>
                                    <option value="" disabled>Pilih Kategori</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                            </div>
                            
                            <div>
                                <x-input-label for="quantity" value="Jumlah Barang *" />
                                <x-text-input id="quantity" name="quantity" type="number" min="1" class="mt-1 block w-full" value="{{ old('quantity', $item->quantity) }}" required />
                                <x-input-error class="mt-2" :messages="$errors->get('quantity')" />
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <x-input-label for="description" value="Deskripsi Lengkap *" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm" required>{{ old('description', $item->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <!-- Kondisi -->
                        <div class="mb-4">
                            <x-input-label value="Kondisi Barang *" class="mb-2" />
                            <div class="grid grid-cols-2 gap-3" x-model="condition">
                                <label class="border rounded-lg p-3 cursor-pointer hover:bg-teal-50 transition flex items-start gap-2" :class="condition === 'new' ? 'border-teal-500 bg-teal-50 ring-1 ring-teal-500' : 'border-gray-200'">
                                    <input type="radio" name="condition" value="new" class="mt-1 text-teal-600 focus:ring-teal-500" @change="condition = 'new'" required>
                                    <div>
                                        <span class="block text-sm font-semibold text-gray-900">Baru (New)</span>
                                    </div>
                                </label>
                                <label class="border rounded-lg p-3 cursor-pointer hover:bg-teal-50 transition flex items-start gap-2" :class="condition === 'like_new' ? 'border-teal-500 bg-teal-50 ring-1 ring-teal-500' : 'border-gray-200'">
                                    <input type="radio" name="condition" value="like_new" class="mt-1 text-teal-600 focus:ring-teal-500" @change="condition = 'like_new'">
                                    <div>
                                        <span class="block text-sm font-semibold text-gray-900">Seperti Baru</span>
                                    </div>
                                </label>
                                <label class="border rounded-lg p-3 cursor-pointer hover:bg-teal-50 transition flex items-start gap-2" :class="condition === 'good' ? 'border-teal-500 bg-teal-50 ring-1 ring-teal-500' : 'border-gray-200'">
                                    <input type="radio" name="condition" value="good" class="mt-1 text-teal-600 focus:ring-teal-500" @change="condition = 'good'">
                                    <div>
                                        <span class="block text-sm font-semibold text-gray-900">Baik (Good)</span>
                                    </div>
                                </label>
                                <label class="border rounded-lg p-3 cursor-pointer hover:bg-teal-50 transition flex items-start gap-2" :class="condition === 'fair' ? 'border-teal-500 bg-teal-50 ring-1 ring-teal-500' : 'border-gray-200'">
                                    <input type="radio" name="condition" value="fair" class="mt-1 text-teal-600 focus:ring-teal-500" @change="condition = 'fair'">
                                    <div>
                                        <span class="block text-sm font-semibold text-gray-900">Layak Pakai</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Foto Barang</h3>
                        
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">Foto Saat Ini</p>
                            <div class="grid grid-cols-5 gap-3">
                                <template x-for="(img, index) in existingImages" :key="'ex-'+index">
                                    <div class="relative aspect-w-1 aspect-h-1 rounded-md overflow-hidden border border-gray-200">
                                        <img :src="'/storage/' + img" class="object-cover w-full h-24">
                                        <input type="hidden" name="existing_images[]" :value="img">
                                        <button type="button" @click.stop="removeExistingImage(index)" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 z-10 focus:outline-none shadow">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Drag and Drop Upload -->
                        <p class="text-sm font-medium text-gray-700 mb-2 mt-6">Tambah Foto Baru</p>
                        <div 
                            class="border-2 border-dashed rounded-lg p-8 text-center transition-colors relative"
                            :class="isDragging ? 'border-teal-500 bg-teal-50' : 'border-gray-300 hover:bg-gray-50'"
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            @drop.prevent="handleDrop($event)"
                        >
                            <input type="file" name="images[]" id="images" multiple accept="image/jpeg,image/png,image/jpg,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="handleFileSelect($event)" :disabled="(images.length + existingImages.length) >= 5">
                            
                            <div class="flex flex-col items-center pointer-events-none">
                                <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-sm text-gray-600 font-medium">Tarik & Lepas foto ke sini, atau <span class="text-teal-600">Klik untuk memilih</span></p>
                                <p class="text-sm font-bold mt-3 text-teal-600" x-text="(images.length + existingImages.length) + '/5 Foto Terpilih'"></p>
                            </div>
                        </div>

                        <!-- Previews Baru -->
                        <div class="grid grid-cols-5 gap-3 mt-4" x-show="images.length > 0">
                            <template x-for="(image, index) in images" :key="index">
                                <div class="relative aspect-w-1 aspect-h-1 rounded-md overflow-hidden border border-gray-200">
                                    <img :src="image.url" class="object-cover w-full h-24">
                                    <button type="button" @click.stop="removeImage(index)" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 z-10 focus:outline-none shadow">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Pengiriman & Lokasi</h3>
                        
                        <div class="mb-4">
                            <x-input-label for="location" value="Lokasi Barang (Kota/Kecamatan) *" />
                            <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" x-model="location" required />
                            <x-input-error class="mt-2" :messages="$errors->get('location')" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="delivery_method" value="Metode Penyerahan *" />
                            <select id="delivery_method" name="delivery_method" class="mt-1 block w-full border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm" required>
                                <option value="pickup" {{ old('delivery_method', $item->delivery_method) === 'pickup' ? 'selected' : '' }}>Penerima Ambil Sendiri (Pickup)</option>
                                <option value="delivery" {{ old('delivery_method', $item->delivery_method) === 'delivery' ? 'selected' : '' }}>Saya Akan Mengantar (Delivery)</option>
                                <option value="both" {{ old('delivery_method', $item->delivery_method) === 'both' ? 'selected' : '' }}>Bisa Keduanya</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Preview -->
                <div class="w-full lg:w-1/3">
                    <div class="sticky top-20">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Preview Tampilan</h3>
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="aspect-w-16 aspect-h-9 relative bg-gray-200">
                                <template x-if="existingImages.length > 0">
                                    <img :src="'/storage/' + existingImages[0]" class="object-cover w-full h-56">
                                </template>
                                <template x-if="existingImages.length === 0 && images.length > 0">
                                    <img :src="images[0].url" class="object-cover w-full h-56">
                                </template>
                                <template x-if="existingImages.length === 0 && images.length === 0">
                                    <div class="w-full h-56 flex items-center justify-center text-gray-400">
                                        <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                </template>
                                <div class="absolute top-3 right-3 bg-amber-100 text-amber-800 text-xs px-2 py-1 rounded font-semibold shadow-sm border border-amber-200" x-text="conditionLabel() || 'Kondisi'"></div>
                            </div>
                            
                            <div class="p-5">
                                <h3 class="font-bold text-xl text-gray-900 line-clamp-1 mb-1" x-text="title || 'Judul Barang'"></h3>
                                <p class="text-gray-500 text-sm flex items-center gap-1 mb-4">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span x-text="location || 'Lokasi Barang'"></span>
                                </p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-6 flex flex-col gap-3">
                            <button type="submit" name="action" value="publish" class="w-full bg-teal-600 text-white font-bold py-3 px-4 rounded-lg shadow hover:bg-teal-700 transition" @click="syncFileInput">
                                Update & Publikasikan
                            </button>
                            <button type="submit" name="action" value="draft" class="w-full bg-white text-gray-700 border border-gray-300 font-bold py-3 px-4 rounded-lg shadow-sm hover:bg-gray-50 transition" @click="syncFileInput">
                                Update sebagai Draft
                            </button>
                            <a href="{{ route('dashboard') }}" class="w-full text-center text-gray-600 hover:text-gray-900 py-2">
                                Batal
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function itemFormEdit() {
            return {
                title: @json(old('title', $item->title)),
                category: @json(old('category_id', $item->category_id)),
                condition: @json(old('condition', $item->condition)),
                location: @json(old('location', $item->location)),
                isDragging: false,
                images: [], 
                existingImages: @json($item->images ?? []),
                
                conditionLabel() {
                    const labels = {
                        'new': 'Baru',
                        'like_new': 'Seperti Baru',
                        'good': 'Baik',
                        'fair': 'Layak Pakai'
                    };
                    return labels[this.condition];
                },

                handleDrop(e) {
                    this.isDragging = false;
                    this.processFiles(e.dataTransfer.files);
                },

                handleFileSelect(e) {
                    this.processFiles(e.target.files);
                },

                processFiles(files) {
                    let remainingSlots = 5 - (this.images.length + this.existingImages.length);
                    if(remainingSlots <= 0) return;

                    let fileArray = Array.from(files).slice(0, remainingSlots);
                    
                    fileArray.forEach(file => {
                        if (file.type.startsWith('image/') && file.size <= 2 * 1024 * 1024) {
                            this.images.push({
                                file: file,
                                url: URL.createObjectURL(file)
                            });
                        }
                    });
                },

                removeImage(index) {
                    URL.revokeObjectURL(this.images[index].url);
                    this.images.splice(index, 1);
                },

                removeExistingImage(index) {
                    this.existingImages.splice(index, 1);
                },

                syncFileInput() {
                    const dt = new DataTransfer();
                    this.images.forEach(img => dt.items.add(img.file));
                    document.getElementById('images').files = dt.files;
                }
            }
        }
    </script>
</x-app-layout>

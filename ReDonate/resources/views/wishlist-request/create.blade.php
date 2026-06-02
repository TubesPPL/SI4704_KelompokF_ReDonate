<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Permintaan Barang') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-8">
                    <div class="mb-8 border-b border-gray-100 pb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Beri Tahu Kami Apa yang Anda Butuhkan</h3>
                        <p class="text-sm text-gray-600">Jelaskan barang yang sedang Anda cari secara spesifik. Donatur yang memiliki barang tersebut akan melihat permintaan Anda dan mungkin akan mendonasikannya untuk Anda.</p>
                    </div>

                    <form action="{{ route('wishlist-requests.store') }}" method="POST">
                        @csrf

                        <div class="space-y-6">
                            <!-- Kategori -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori Barang *</label>
                                <select id="category_id" name="category_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm" required>
                                    <option value="">Pilih kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                            </div>

                            <!-- Judul Permintaan -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Permintaan *</label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" 
                                       placeholder="Contoh: Butuh sepatu sekolah ukuran 38 warna hitam" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm" required minlength="5" maxlength="100">
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Alasan Membutuhkan & Deskripsi Spesifik *</label>
                                <textarea id="description" name="description" rows="4" 
                                          placeholder="Jelaskan secara singkat mengapa Anda membutuhkan barang ini dan spesifikasi detail (ukuran, warna, bahan, dll) agar donatur bisa mencocokkannya dengan barang yang mereka miliki." 
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm" required minlength="20" maxlength="500">{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                <p class="text-[10px] text-gray-400 mt-1">Minimal 20 karakter, maksimal 500 karakter.</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Kondisi -->
                                <div>
                                    <label for="condition_needed" class="block text-sm font-medium text-gray-700 mb-1">Kondisi yang Diharapkan <span class="text-gray-400 font-normal">(opsional)</span></label>
                                    <select id="condition_needed" name="condition_needed" class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm">
                                        <option value="">Semua Kondisi</option>
                                        <option value="Baru" {{ old('condition_needed') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                        <option value="Seperti Baru" {{ old('condition_needed') == 'Seperti Baru' ? 'selected' : '' }}>Seperti Baru</option>
                                        <option value="Bekas Layak Pakai" {{ old('condition_needed') == 'Bekas Layak Pakai' ? 'selected' : '' }}>Bekas Layak Pakai</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('condition_needed')" class="mt-2" />
                                </div>

                                <!-- Expired At -->
                                <div>
                                    <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-1">Batas Waktu Permintaan <span class="text-gray-400 font-normal">(opsional)</span></label>
                                    <input type="date" id="expires_at" name="expires_at" value="{{ old('expires_at') }}" 
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" max="{{ date('Y-m-d', strtotime('+3 months')) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm">
                                    <x-input-error :messages="$errors->get('expires_at')" class="mt-2" />
                                    <p class="text-[10px] text-gray-400 mt-1">Jika dikosongkan, permintaan akan selalu aktif.</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-4">
                            <a href="{{ route('wishlist-requests.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Batal</a>
                            <button type="submit" class="px-6 py-2 bg-teal-600 text-white text-sm font-bold rounded-lg shadow-sm hover:bg-teal-700 transition">
                                Publikasikan Permintaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

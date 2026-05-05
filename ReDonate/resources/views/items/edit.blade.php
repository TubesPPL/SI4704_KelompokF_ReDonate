<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReDonate - Edit Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #fcfcfc; }
        .form-shadow { box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.05); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body x-data="editForm({{ $id }})">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-20">
            <div class="flex items-center gap-2">
                <div class="text-green-600 font-bold text-2xl flex items-center">
                    <span class="mr-1">🌱</span> ReDonate
                </div>
            </div>
            <div class="hidden md:flex space-x-8 text-gray-600 font-medium">
                <a href="/dashboard" class="hover:text-green-600 transition">Home</a>
                <a href="/dashboard" class="hover:text-green-600 transition">Barang</a>
                <a href="#" class="hover:text-green-600 transition">Event</a>
                <a href="#" class="hover:text-green-600 transition">Edukasi</a>
                <a href="#" class="hover:text-green-600 transition">Panduan</a>
            </div>
            <div class="flex items-center gap-4">
                <a :href="'/items/' + id" class="px-6 py-2 text-gray-700 font-semibold border border-gray-300 rounded-xl hover:bg-gray-50 transition">Batal Edit</a>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 py-12" x-cloak>
        <template x-if="isLoading">
            <div class="flex justify-center items-center h-64">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600"></div>
            </div>
        </template>

        <template x-if="!isLoading">
            <div>
                <!-- Header -->
                <div class="mb-10 text-left">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Edit Informasi Barang</h1>
                    <p class="text-gray-500 text-lg">Perbarui detail barang donasi Anda</p>
                </div>

                <form @submit.prevent="submitForm" class="space-y-8">
                    
                    <!-- Section: Upload Foto -->
                    <div class="space-y-4">
                        <label class="block font-bold text-gray-800 text-lg">Foto Barang (Biarkan kosong jika tidak ingin mengubah foto)</label>
                        
                        <div class="flex flex-wrap gap-4">
                            <label class="w-40 h-40 border-2 border-dashed border-gray-300 rounded-2xl flex flex-col items-center justify-center cursor-pointer hover:border-green-500 hover:bg-green-50 transition group">
                                <input type="file" class="hidden" @change="handleFile" accept="image/*">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 group-hover:text-green-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                <span class="mt-2 text-gray-700 font-semibold text-sm text-center">Ganti Foto</span>
                            </label>

                            <!-- Image Preview -->
                            <template x-if="preview">
                                <div class="relative w-40 h-40 rounded-2xl overflow-hidden group">
                                    <img :src="preview" class="w-full h-full object-cover">
                                    <button @click="removeImage()" type="button" class="absolute top-2 right-2 p-1 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Section: Detail Barang -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="block font-bold text-gray-800 mb-2">Nama Barang *</label>
                            <input type="text" x-model="form.item_name" required placeholder="Contoh: Sofa 3 Dudukan" class="w-full p-4 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-green-500 transition">
                        </div>
                        <div class="col-span-2">
                            <label class="block font-bold text-gray-800 mb-2">Deskripsi *</label>
                            <textarea x-model="form.description" required rows="3" placeholder="Jelaskan kondisi dan detail barang Anda" class="w-full p-4 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-green-500 transition"></textarea>
                        </div>
                        <div>
                            <label class="block font-bold text-gray-800 mb-2">Kategori *</label>
                            <select x-model="form.category_id" required class="w-full p-4 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-green-500 transition appearance-none">
                                <option value="">Pilih kategori</option>
                                <template x-for="cat in categories" :key="cat.id">
                                    <option :value="cat.id" x-text="cat.name"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-gray-800 mb-2">Kondisi *</label>
                            <select x-model="form.condition" required class="w-full p-4 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-green-500 transition appearance-none">
                                <option value="">Pilih kondisi</option>
                                <option value="baru">Baru</option>
                                <option value="bekas_baik">Bekas (Baik)</option>
                                <option value="bekas_layak">Bekas (Layak Pakai)</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block font-bold text-gray-800 mb-2">Lokasi *</label>
                            <input type="text" x-model="form.location" required placeholder="Contoh: Jakarta Selatan" class="w-full p-4 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-green-500 transition">
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-col md:flex-row gap-4 pt-6">
                        <a :href="'/items/' + id" class="flex-1 text-center py-4 border border-gray-300 rounded-2xl font-bold text-gray-700 hover:bg-gray-50 transition">Batal</a>
                        <button type="submit" 
                                :disabled="!isReady"
                                :class="isReady ? 'bg-green-600 hover:bg-green-700 transform hover:scale-[1.02]' : 'bg-gray-300 cursor-not-allowed'"
                                class="flex-1 py-4 text-white rounded-2xl font-bold transition-all shadow-lg shadow-green-200">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </template>
    </main>

    <script>
        function editForm(id) {
            return {
                id: id,
                categories: [],
                preview: null,
                file: null,
                isLoading: true,
                form: {
                    item_name: '',
                    description: '',
                    category_id: '',
                    condition: '',
                    location: ''
                },

                async init() {
                    try {
                        // Ambil kategori
                        const catRes = await fetch('/api/v1/categories');
                        const catData = await catRes.json();
                        this.categories = catData.data || catData;

                        // Ambil data barang
                        const itemRes = await fetch(`/api/v1/items/${this.id}`);
                        const itemData = await itemRes.json();
                        
                        if (itemRes.ok && itemData.data) {
                            const item = itemData.data;
                            this.form.item_name = item.item_name;
                            this.form.description = item.description;
                            this.form.category_id = item.category_id;
                            this.form.condition = item.condition;
                            this.form.location = item.location;
                            
                            if (item.image_url) {
                                this.preview = '/storage/' + item.image_url;
                            }
                        }
                    } catch (error) {
                        console.error('Gagal memuat data:', error);
                        alert('Gagal memuat data barang.');
                    } finally {
                        this.isLoading = false;
                    }
                },

                handleFile(e) {
                    const file = e.target.files[0];
                    if (file) {
                        this.file = file;
                        const reader = new FileReader();
                        reader.onload = (e) => this.preview = e.target.result;
                        reader.readAsDataURL(file);
                    }
                },

                removeImage() {
                    this.preview = null;
                    this.file = null;
                },

                get isReady() {
                    return this.form.item_name !== '' && 
                           this.form.description !== '' && 
                           this.form.category_id !== '' && 
                           this.form.condition !== '' && 
                           this.form.location !== '';
                },

                async submitForm() {
                    const formData = new FormData();
                    
                    // Method Spoofing untuk Laravel PUT dengan multipart/form-data
                    formData.append('_method', 'PUT');

                    Object.keys(this.form).forEach(key => {
                        formData.append(key, this.form[key]);
                    });

                    if (this.file) {
                        formData.append('image', this.file);
                    }

                    try {
                        const response = await fetch(`/api/v1/items/${this.id}`, {
                            method: 'POST', // Gunakan POST dengan _method=PUT
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });

                        if (response.ok) {
                            alert('Informasi barang berhasil diperbarui!');
                            window.location.href = `/items/${this.id}`;
                        } else {
                            const errorData = await response.json();
                            console.error(errorData);
                            alert('Terjadi kesalahan saat menyimpan data. Pastikan semua field terisi dengan benar.');
                        }
                    } catch (error) {
                        console.error('Submit error:', error);
                        alert('Gagal terhubung ke server.');
                    }
                }
            }
        }
    </script>
</body>
</html>

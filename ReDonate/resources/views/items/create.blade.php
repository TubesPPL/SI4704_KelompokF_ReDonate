<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReDonate - Donasikan Barang</title>
    
    <!-- Scripts & Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #fcfcfc; }
        [x-cloak] { display: none !important; }
        .form-shadow { box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.05); }
    </style>
</head>
<body x-data="donationForm()">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-20">
            <a href="{{ route('dashboard') }}" class="text-green-600 font-bold text-2xl flex items-center">
                <span class="mr-1">🌱</span> ReDonate
            </a>
            
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 px-3 py-1 bg-gray-50 rounded-full border border-gray-100">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=16a34a&color=fff&bold=true" 
                         class="w-8 h-8 rounded-full">
                    <span class="text-gray-700 text-sm font-semibold">{{ Auth::user()->name }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-red-500 text-sm font-bold hover:underline">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 py-12">
        <div class="mb-10">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Donasikan Barang</h1>
            <p class="text-gray-500 text-lg">Berbagi kebaikan dengan mendonasikan barang layak pakai Anda</p>
        </div>

        <form @submit.prevent="submitForm" class="space-y-8 bg-white p-8 rounded-3xl border border-gray-100 form-shadow">
            @csrf

            <!-- Section 1: Metode Donasi -->
            <div class="space-y-4">
                <label class="block font-bold text-gray-800 text-lg">Metode Donasi *</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="relative flex items-start p-6 border-2 rounded-2xl cursor-pointer transition-all hover:bg-gray-50"
                           :class="form.method === 'p2p' ? 'border-green-500 bg-green-50/30' : 'border-gray-200'">
                        <input type="radio" name="method" value="p2p" x-model="form.method" class="mt-1.5 h-4 w-4 text-green-600">
                        <div class="ml-4">
                            <span class="font-bold text-gray-900">Donasi Langsung (P2P)</span>
                            <p class="text-gray-500 text-sm">Koordinasi langsung dengan penerima via chat.</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Section 2: Upload Foto -->
            <div class="space-y-4">
                <label class="block font-bold text-gray-800 text-lg">Foto Barang * (Minimal 1 Foto)</label>
                <div class="flex flex-wrap gap-4">
                    <label class="w-40 h-40 border-2 border-dashed border-gray-300 rounded-2xl flex flex-col items-center justify-center cursor-pointer hover:border-green-500 hover:bg-green-50 transition group">
                        <input type="file" name="items[]" multiple class="hidden" @change="handleFiles" accept="image/*">
                        <i class="fa-solid fa-camera text-gray-400 group-hover:text-green-500 text-2xl mb-2"></i>
                        <span class="text-gray-500 font-semibold text-xs">Upload Foto</span>
                    </label>

                    <template x-for="(preview, index) in previews" :key="index">
                        <div class="relative w-40 h-40 rounded-2xl overflow-hidden shadow-sm group">
                            <img :src="preview" class="w-full h-full object-cover">
                            <button @click="removeImage(index)" type="button" class="absolute top-2 right-2 w-7 h-7 bg-red-500 text-white rounded-full flex items-center justify-center shadow-md hover:bg-red-600 transition">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Section 3: Detail Barang (PBI #5) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                <div class="col-span-2">
                    <label class="block font-bold text-gray-800 mb-2">Nama Barang *</label>
                    <input type="text" x-model="form.name" required placeholder="Contoh: Laptop Acer Swift 3" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:bg-white outline-none transition-all">
                </div>
                <div class="col-span-2">
                    <label class="block font-bold text-gray-800 mb-2">Deskripsi</label>
                    <textarea x-model="form.description" rows="3" placeholder="Jelaskan kondisi detail barang, lama pemakaian, dll..." class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:bg-white outline-none transition-all"></textarea>
                </div>
                <div>
                    <label class="block font-bold text-gray-800 mb-2">Kategori *</label>
                    <select x-model="form.category" required class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none">
                        <option value="">Pilih kategori</option>
                        <template x-for="cat in categories" :key="cat.id">
                            <option :value="cat.id" x-text="cat.name"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-gray-800 mb-2">Kondisi *</label>
                    <select x-model="form.condition" required class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none">
                        <option value="">Pilih kondisi</option>
                        <option value="baru">Baru</option>
                        <option value="bekas_baik">Bekas (Baik)</option>
                        <option value="bekas_layak">Bekas (Layak Pakai)</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block font-bold text-gray-800 mb-2">Lokasi *</label>
                    <input type="text" x-model="form.location" required placeholder="Contoh: Bojongsoang, Bandung" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none">
                </div>
            </div>

            <!-- Section 4: Checklist Kelayakan -->
            <div class="space-y-4 pt-4">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-green-600"></i> Checklist Kelayakan Barang *
                </h2>
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-6 space-y-4">
                    <template x-for="(item, index) in checklistItems" :key="index">
                        <label class="flex items-start gap-4 cursor-pointer p-2 rounded-lg hover:bg-white transition shadow-sm">
                            <input type="checkbox" x-model="checklist[index]" class="mt-1 h-5 w-5 rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <div>
                                <span class="block font-bold text-gray-900" x-text="item.title"></span>
                                <span class="text-xs text-gray-500" x-text="item.desc"></span>
                            </div>
                        </label>
                    </template>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-8">
                <a href="{{ route('dashboard') }}" class="flex-1 py-4 text-center border border-gray-300 rounded-2xl font-bold text-gray-600 hover:bg-gray-50 transition">Batal</a>
                <button type="submit" 
                        :disabled="!isReady"
                        :class="isReady ? 'bg-green-600 hover:bg-green-700 shadow-lg shadow-green-200' : 'bg-gray-300 cursor-not-allowed'"
                        class="flex-1 py-4 text-white rounded-2xl font-bold transition-all transform active:scale-95">
                    Donasikan Barang
                </button>
            </div>
        </form>
    </main>

    <script>
        function donationForm() {
            return {
                // PBI #10: Load data kategori dari database
                categories: @json(\App\Models\Category::all()->map(function($c){
                    return ['id' => $c->id, 'name' => $c->category_name];
                })),
                previews: [],
                files: [],
                checklist: [false, false, false, false, false],
                form: {
                    method: 'p2p',
                    name: '',
                    description: '',
                    category: '',
                    condition: '',
                    location: '',
                },
                checklistItems: [
                    { title: 'Barang masih berfungsi', desc: 'Elektronik menyala, furniture kokoh, baju tidak sobek' },
                    { title: 'Barang sudah bersih', desc: 'Sudah dicuci atau dibersihkan dari noda' },
                    { title: 'Aksesoris lengkap', desc: 'Komponen penting tidak ada yang hilang' },
                    { title: 'Deskripsi jujur', desc: 'Data yang diinput sesuai dengan fisik barang' },
                    { title: 'Tanggung jawab penuh', desc: 'Saya siap bertanggung jawab atas kondisi barang ini' }
                ],

                handleFiles(e) {
                    const files = Array.from(e.target.files);
                    files.forEach(file => {
                        this.files.push(file);
                        const reader = new FileReader();
                        reader.onload = (e) => this.previews.push(e.target.result);
                        reader.readAsDataURL(file);
                    });
                },

                removeImage(index) {
                    this.previews.splice(index, 1);
                    this.files.splice(index, 1);
                },

                get isChecklistComplete() {
                    return this.checklist.every(val => val === true);
                },

                get isReady() {
                    return this.isChecklistComplete && this.files.length >= 1 && this.form.name !== '' && this.form.category !== '';
                },

                async submitForm() {
                    const formData = new FormData();
                    
                    // SINKRONISASI: Menyesuaikan KEY dengan Controller@store
                    formData.append('item_name', this.form.name); 
                    formData.append('description', this.form.description);
                    formData.append('category_id', this.form.category);
                    formData.append('condition', this.form.condition);
                    formData.append('location', this.form.location);
                    
                    // SINKRONISASI: File dikirim sebagai array 'items[]'
                    this.files.forEach(file => formData.append('items[]', file)); 

                    try {
                        const response = await fetch("{{ route('items.store') }}", {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });

                        const result = await response.json();

                        if (response.ok && result.success) {
                            alert('Berhasil! ' + result.message);
                            window.location.href = result.redirect;
                        } else {
                            // Menampilkan error validasi jika ada
                            let errorMsg = result.message || 'Cek kembali isian Anda.';
                            if (result.errors) {
                                errorMsg = Object.values(result.errors).flat().join('\n');
                            }
                            alert('Gagal: ' + errorMsg);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan koneksi server.');
                    }
                }
            }
        }
    </script>
</body>
</html>
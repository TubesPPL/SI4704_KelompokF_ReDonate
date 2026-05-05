<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReDonate - Donasikan Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #fcfcfc; }
        .form-shadow { box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.05); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body x-data="donationForm()">

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
                <button class="px-6 py-2 text-gray-700 font-semibold border border-gray-300 rounded-xl hover:bg-gray-50 transition">Login</button>
                <button class="px-6 py-2 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transform hover:scale-105 transition-all">Register</button>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 py-12">
        <!-- Header -->
        <div class="mb-10 text-left">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Donasikan Barang</h1>
            <p class="text-gray-500 text-lg">Berbagi kebaikan dengan mendonasikan barang layak pakai Anda</p>
        </div>

        <form @submit.prevent="submitForm" class="space-y-8">
            @csrf

            <!-- Section 1: Panduan -->
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 flex gap-4">
                <div class="text-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-blue-900 mb-1">Panduan Donasi Layak</h3>
                    <p class="text-blue-700 mb-2">Pastikan barang Anda memenuhi standar kelayakan untuk penerima.</p>
                    <a href="#" class="text-blue-600 font-semibold hover:underline flex items-center">Baca Panduan Lengkap <span class="ml-1">→</span></a>
                </div>
            </div>

            <!-- Section 2: Metode Donasi -->
            <div class="space-y-4">
                <label class="block font-bold text-gray-800 text-lg">Metode Donasi *</label>
                
                <!-- Option 1: P2P -->
                <label class="relative flex items-start p-6 border-2 rounded-2xl cursor-pointer transition-all hover:bg-gray-50"
                       :class="form.method === 'p2p' ? 'border-green-500 bg-green-50/30' : 'border-gray-200'">
                    <input type="radio" name="method" value="p2p" x-model="form.method" class="mt-1.5 h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500">
                    <div class="ml-4">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xl">🤝</span>
                            <span class="font-bold text-gray-900">Donasi Langsung (P2P)</span>
                        </div>
                        <p class="text-gray-500 text-sm mb-3">Koordinasi langsung dengan penerima melalui chat. Anda tentukan lokasi dan waktu penjemputan/pengiriman.</p>
                        <div class="flex gap-2">
                            <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs rounded-lg font-medium">Gratis</span>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-lg font-medium flex items-center">⚠️ Belum Diverifikasi</span>
                        </div>
                    </div>
                </label>

                <!-- Option 2: Drop Point -->
                <label class="relative flex items-start p-6 border-2 rounded-2xl cursor-pointer transition-all hover:bg-gray-50"
                       :class="form.method === 'drop_point' ? 'border-green-500 bg-green-50/30' : 'border-gray-200'">
                    <input type="radio" name="method" value="drop_point" x-model="form.method" class="mt-1.5 h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500">
                    <div class="ml-4">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xl">🏢</span>
                            <span class="font-bold text-gray-900">Via Drop Point ReDonate</span>
                        </div>
                        <p class="text-gray-500 text-sm mb-3">Setor barang ke kantor ReDonate. Tim kami akan cek fisik dan verifikasi kelayakan sebelum dipublikasi.</p>
                        <div class="flex gap-2">
                            <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs rounded-lg font-medium">Gratis</span>
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs rounded-lg font-medium flex items-center">✅ Verified</span>
                        </div>
                    </div>
                </label>
            </div>

            <!-- Section 3: Upload Foto -->
            <div class="space-y-4">
                <label class="block font-bold text-gray-800 text-lg">Foto Barang * (Minimal 4 Foto)</label>
                <p class="text-gray-500 text-sm">Upload foto dari berbagai sudut: keseluruhan, detail, label/merk, dan sudut lain</p>
                
                <div class="flex flex-wrap gap-4">
                    <label class="w-40 h-40 border-2 border-dashed border-gray-300 rounded-2xl flex flex-col items-center justify-center cursor-pointer hover:border-green-500 hover:bg-green-50 transition group">
                        <input type="file" multiple class="hidden" @change="handleFiles" accept="image/*">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 group-hover:text-green-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        <span class="mt-2 text-gray-700 font-semibold">Upload Foto</span>
                        <span class="text-gray-400 text-xs">JPG, PNG</span>
                    </label>

                    <!-- Image Previews -->
                    <template x-for="(preview, index) in previews" :key="index">
                        <div class="relative w-40 h-40 rounded-2xl overflow-hidden group">
                            <img :src="preview" class="w-full h-full object-cover">
                            <button @click="removeImage(index)" type="button" class="absolute top-2 right-2 p-1 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
                
                <div x-show="previews.length < 4" class="p-4 bg-orange-50 border border-orange-100 rounded-xl flex items-center gap-3 text-orange-700">
                    <span class="text-lg">⚠️</span>
                    <span class="text-sm font-medium">Minimal 4 foto diperlukan untuk meningkatkan kepercayaan penerima</span>
                </div>
            </div>

            <!-- Section 4: Detail Barang -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block font-bold text-gray-800 mb-2">Nama Barang *</label>
                    <input type="text" x-model="form.name" placeholder="Contoh: Sofa 3 Dudukan" class="w-full p-4 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-green-500 transition">
                </div>
                <div class="col-span-2">
                    <label class="block font-bold text-gray-800 mb-2">Deskripsi</label>
                    <textarea x-model="form.description" rows="3" placeholder="Jelaskan kondisi dan detail barang Anda" class="w-full p-4 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-green-500 transition"></textarea>
                </div>
                <div>
                    <label class="block font-bold text-gray-800 mb-2">Kategori *</label>
                    <select x-model="form.category" class="w-full p-4 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-green-500 transition appearance-none">
                        <option value="">Pilih kategori</option>
                        <template x-for="cat in categories" :key="cat.id">
                            <option :value="cat.id" x-text="cat.name"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-gray-800 mb-2">Kondisi *</label>
                    <select x-model="form.condition" class="w-full p-4 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-green-500 transition appearance-none">
                        <option value="">Pilih kondisi</option>
                        <option value="baru">Baru</option>
                        <option value="bekas_baik">Bekas (Baik)</option>
                        <option value="bekas_layak">Bekas (Layak Pakai)</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block font-bold text-gray-800 mb-2">Lokasi *</label>
                    <input type="text" x-model="form.location" placeholder="Contoh: Jakarta Selatan" class="w-full p-4 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-green-500 transition">
                </div>
            </div>

            <hr class="border-gray-100">

            <!-- Section 5: Informasi Kontak -->
            <div class="space-y-6">
                <h2 class="text-2xl font-bold text-gray-900">Informasi Kontak</h2>
                <div>
                    <label class="block font-bold text-gray-800 mb-2">Nama</label>
                    <input type="text" x-model="form.contact_name" placeholder="Nama Anda" class="w-full p-4 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-green-500 transition">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-bold text-gray-800 mb-2">Nomor WhatsApp</label>
                        <input type="tel" x-model="form.contact_wa" placeholder="08123456789" class="w-full p-4 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-green-500 transition">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-800 mb-2">Email</label>
                        <input type="email" x-model="form.contact_email" placeholder="email@example.com" class="w-full p-4 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-green-500 transition">
                    </div>
                </div>
            </div>

            <!-- Section 6: Checklist -->
            <div class="space-y-4">
                <h2 class="text-2xl font-bold text-gray-900">Checklist Kelayakan Barang *</h2>
                <p class="text-gray-500">Pastikan semua poin berikut terpenuhi sebelum mendonasikan barang</p>
                
                <div class="bg-gray-50 rounded-2xl p-6 space-y-5">
                    <template x-for="(item, index) in checklistItems" :key="index">
                        <label class="flex items-start gap-4 cursor-pointer group">
                            <input type="checkbox" x-model="checklist[index]" class="mt-1 h-5 w-5 rounded text-green-600 border-gray-300 focus:ring-green-500">
                            <div>
                                <span class="block font-bold text-gray-900 group-hover:text-green-600 transition" :class="item.isDanger ? 'text-red-700' : ''">
                                    <span x-text="item.icon" class="mr-1"></span> <span x-text="item.title"></span>
                                </span>
                                <span class="text-sm text-gray-500" x-text="item.desc"></span>
                            </div>
                        </label>
                    </template>
                </div>

                <div x-show="!isChecklistComplete" class="p-4 bg-red-50 border border-red-100 rounded-xl flex items-center gap-3 text-red-700">
                    <span class="text-lg">🚫</span>
                    <span class="text-sm font-medium">Harap centang semua poin checklist sebelum submit</span>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col md:flex-row gap-4 pt-6">
                <button type="button" class="flex-1 py-4 border border-gray-300 rounded-2xl font-bold text-gray-700 hover:bg-gray-50 transition">Batal</button>
                <button type="submit" 
                        :disabled="!isReady"
                        :class="isReady ? 'bg-green-600 hover:bg-green-700 transform hover:scale-[1.02]' : 'bg-gray-300 cursor-not-allowed'"
                        class="flex-1 py-4 text-white rounded-2xl font-bold transition-all shadow-lg shadow-green-200">
                    Donasikan Barang
                </button>
            </div>
        </form>
    </main>

    <script>
        function donationForm() {
            return {
                categories: [],
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
                    contact_name: '',
                    contact_wa: '',
                    contact_email: ''
                },
                checklistItems: [
                    { icon: '✅', title: 'Barang masih berfungsi dengan sempurna', desc: 'Elektronik dapat menyala, furniture tidak goyang, pakaian tidak rusak' },
                    { icon: '✅', title: 'Tidak ada kerusakan, sobek, atau bolong', desc: 'Barang dalam kondisi layak pakai tanpa cacat parah' },
                    { icon: '✅', title: 'Sudah dibersihkan/dicuci dengan bersih', desc: 'Bebas dari debu, noda, dan bau tidak sedap' },
                    { icon: '✅', title: 'Semua bagian/aksesoris lengkap', desc: 'Tidak ada komponen penting yang hilang' },
                    { icon: '⚠️', title: 'Saya bertanggung jawab atas kondisi barang', desc: 'Saya memahami bahwa donasi barang tidak layak dapat menurunkan rating dan berisiko banned', isDanger: true }
                ],

                init() {
                    // Fetch categories from API on load
                    fetch('/api/v1/categories')
                        .then(res => res.json())
                        .then(data => this.categories = data)
                        .catch(err => console.error('Gagal memuat kategori'));
                },

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
                    return this.isChecklistComplete && this.previews.length >= 4 && this.form.name !== '';
                },

                async submitForm() {
                    const formData = new FormData();
                    // Append form fields
                    Object.keys(this.form).forEach(key => formData.append(key, this.form[key]));
                    // Append files
                    this.files.forEach(file => formData.append('items[]', file));

                    try {
                        const response = await fetch('/api/v1/items', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        if (response.ok) {
                            alert('Donasi berhasil dikirim!');
                            window.location.reload();
                        } else {
                            alert('Terjadi kesalahan saat mengirim data.');
                        }
                    } catch (error) {
                        console.error('Submit error:', error);
                    }
                }
            }
        }
    </script>
</body>
</html>
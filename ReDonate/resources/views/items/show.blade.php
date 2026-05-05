<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReDonate - Detail Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #fcfcfc; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body x-data="itemDetail({{ $id }})">

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
                <a href="{{ route('items.create') }}" class="hidden md:block px-4 py-2 text-green-600 font-medium hover:bg-green-50 rounded-xl transition">Donasi Barang</a>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 py-12" x-cloak>
        <template x-if="isLoading">
            <div class="flex justify-center items-center h-64">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600"></div>
            </div>
        </template>

        <template x-if="!isLoading && item">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Kiri: Foto Barang -->
                <div class="space-y-4">
                    <div class="w-full h-96 bg-gray-100 rounded-3xl overflow-hidden shadow-sm border border-gray-100">
                        <img :src="item.image_url ? '/storage/' + item.image_url : 'https://placehold.co/600x400?text=No+Image'" alt="Item Photo" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Kanan: Detail Barang -->
                <div>
                    <!-- Breadcrumb / Status -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase tracking-wider" x-text="item.status === 'available' ? 'Tersedia' : 'Tidak Tersedia'"></span>
                        <span class="text-sm text-gray-400" x-text="new Date(item.created_at).toLocaleDateString('id-ID')"></span>
                    </div>

                    <h1 class="text-4xl font-bold text-gray-900 mb-2" x-text="item.item_name"></h1>
                    
                    <div class="flex items-center gap-4 text-sm text-gray-500 mb-6 border-b border-gray-100 pb-6">
                        <div class="flex items-center gap-1">
                            <span>📂</span> <span x-text="item.category?.name || 'Kategori Umum'"></span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span>📍</span> <span x-text="item.location"></span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span>🏷️</span> <span x-text="formatCondition(item.condition)"></span>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Deskripsi Barang</h3>
                        <p class="text-gray-600 leading-relaxed whitespace-pre-line" x-text="item.description || 'Tidak ada deskripsi.'"></p>
                    </div>

                    <!-- Profil Donatur -->
                    <div class="bg-gray-50 rounded-2xl p-6 mb-8 flex items-center justify-between border border-gray-100">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-green-200 rounded-full flex items-center justify-center text-green-700 font-bold text-xl">
                                <span x-text="(item.user?.name || 'A')[0].toUpperCase()"></span>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900" x-text="item.user?.name || 'Anonim'"></h4>
                                <p class="text-xs text-gray-500">Donatur Terverifikasi ✅</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="space-y-3">
                        <button class="w-full py-4 bg-green-600 text-white rounded-2xl font-bold shadow-lg shadow-green-200 hover:bg-green-700 transform hover:scale-[1.02] transition-all">
                            Minta Barang Ini
                        </button>

                        <div class="flex gap-3 pt-4 border-t border-gray-100">
                            <!-- PBI 7: Tombol Edit -->
                            <a :href="'/items/' + item.id + '/edit'" class="flex-1 text-center py-3 border border-gray-300 rounded-xl font-bold text-gray-700 hover:bg-gray-50 transition">
                                Edit Informasi
                            </a>
                            <!-- PBI 8: Tombol Hapus -->
                            <button @click="deleteItem" class="flex-1 py-3 border border-red-200 bg-red-50 rounded-xl font-bold text-red-600 hover:bg-red-100 hover:border-red-300 transition">
                                Hapus Listing
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        
        <template x-if="!isLoading && !item">
            <div class="text-center py-20">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Barang Tidak Ditemukan</h2>
                <p class="text-gray-500 mb-6">Barang yang Anda cari mungkin sudah dihapus atau tidak tersedia.</p>
                <a href="/" class="px-6 py-3 bg-green-600 text-white rounded-xl font-bold hover:bg-green-700 transition">Kembali ke Beranda</a>
            </div>
        </template>
    </main>

    <script>
        function itemDetail(id) {
            return {
                id: id,
                item: null,
                isLoading: true,

                init() {
                    this.fetchItem();
                },

                formatCondition(condition) {
                    const map = {
                        'baru': 'Baru',
                        'bekas_baik': 'Bekas (Baik)',
                        'bekas_layak': 'Bekas (Layak Pakai)'
                    };
                    return map[condition] || condition;
                },

                async fetchItem() {
                    try {
                        const response = await fetch(`/api/v1/items/${this.id}`);
                        const json = await response.json();
                        if (response.ok) {
                            this.item = json.data;
                        }
                    } catch (error) {
                        console.error('Error fetching item:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },

                async deleteItem() {
                    if (confirm('Apakah Anda yakin ingin menghapus listing barang ini? Tindakan ini tidak dapat dibatalkan.')) {
                        try {
                            const response = await fetch(`/api/v1/items/${this.id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            });
                            
                            if (response.ok) {
                                alert('Barang berhasil dihapus.');
                                window.location.href = '/dashboard'; // Redirect ke dashboard setelah hapus
                            } else {
                                alert('Gagal menghapus barang.');
                            }
                        } catch (error) {
                            console.error('Delete error:', error);
                            alert('Terjadi kesalahan sistem.');
                        }
                    }
                }
            }
        }
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Permintaan - ReDonate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-800">
    <nav class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
        <div class="max-w-5xl mx-auto flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="text-green-600 font-bold text-xl flex items-center gap-2">
                <i class="fa-solid fa-heart"></i> ReDonate
            </a>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto py-10 px-4 sm:px-6">
        <a href="{{ route('items.show', $item->id) }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-green-600 mb-6 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Detail Barang
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-green-50/50 border-b border-gray-100 p-6 sm:p-8">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                        @if($item->image_url)
                            <img src="{{ Str::startsWith($item->image_url, ['http://', 'https://']) ? $item->image_url : asset('storage/' . $item->image_url) }}" alt="Gambar Barang" class="w-full h-full object-cover">
                        @else
                            <i class="fa-solid fa-box-open text-gray-400 text-2xl"></i>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-2xl font-extrabold text-gray-900">Ajukan Permintaan</h2>
                        <p class="text-gray-500 mt-1">Anda sedang mengajukan permintaan untuk <strong class="text-green-600">{{ $item->item_name }}</strong></p>
                    </div>
                </div>
            </div>

            <form action="{{ route('requests.store', $item->id) }}" method="POST" class="p-6 sm:p-8 space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Pesan untuk Donatur <span class="text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <textarea name="message" rows="4" class="w-full border border-gray-300 rounded-xl p-4 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all resize-none shadow-sm" placeholder="Halo, saya sangat membutuhkan barang ini untuk..."></textarea>
                    <p class="text-xs text-gray-500 mt-2"><i class="fa-solid fa-circle-info mr-1"></i> Berikan alasan yang sopan agar donatur memilih Anda.</p>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Metode Pengambilan <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="pickup_method" class="w-full border border-gray-300 rounded-xl p-4 appearance-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm bg-white cursor-pointer" required>
                            <option value="" disabled selected>-- Pilih Metode --</option>
                            <option value="ambil_sendiri">🚶‍♂️ Ambil Sendiri ke Lokasi Donatur</option>
                            <option value="kurir">📦 Gunakan Jasa Kurir</option>
                            <option value="cod">🤝 Bertemu Langsung (COD)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                    </div>
                </div>
                
                <div class="pt-4 border-t border-gray-100">
                    <button type="submit" class="w-full flex items-center justify-center gap-2 bg-green-600 text-white font-bold py-4 rounded-xl hover:bg-green-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Permintaan Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
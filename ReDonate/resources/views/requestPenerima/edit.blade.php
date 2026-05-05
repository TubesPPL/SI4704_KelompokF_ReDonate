<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Preferensi Pengambilan - ReDonate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-800">

    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-xl w-full bg-white p-8 sm:p-10 rounded-2xl shadow-lg border border-gray-100 relative">
            
            <!-- Icon Dekoratif -->
            <div class="absolute -top-6 left-1/2 -translate-x-1/2 w-12 h-12 bg-blue-600 text-white rounded-xl flex items-center justify-center shadow-lg transform rotate-3">
                <i class="fa-solid fa-pen-to-square text-xl"></i>
            </div>

            <div class="text-center mt-6 mb-8">
                <h2 class="text-2xl font-extrabold text-gray-900">Ubah Metode Pengambilan</h2>
                <p class="text-gray-500 mt-2 text-sm">Ubah cara Anda menerima barang untuk permintaan <strong class="text-gray-700">{{ $requestItem->item->item_name ?? 'Barang ini' }}</strong>.</p>
            </div>
            
            <form action="{{ route('requests.update', $requestItem->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-8">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Metode Baru</label>
                    <div class="relative">
                        <select name="pickup_method" class="w-full border border-gray-300 rounded-xl p-4 appearance-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm bg-white cursor-pointer" required>
                            <option value="ambil_sendiri" {{ $requestItem->pickup_method == 'ambil_sendiri' ? 'selected' : '' }}>🚶‍♂️ Ambil Sendiri ke Lokasi</option>
                            <option value="kurir" {{ $requestItem->pickup_method == 'kurir' ? 'selected' : '' }}>📦 Gunakan Jasa Kurir</option>
                            <option value="cod" {{ $requestItem->pickup_method == 'cod' ? 'selected' : '' }}>🤝 Bertemu Langsung (COD)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-3.5 px-4 rounded-xl hover:bg-blue-700 transition-all shadow border border-transparent">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('requests.index') }}" class="flex-1 bg-white text-gray-700 border border-gray-300 font-bold py-3.5 px-4 rounded-xl hover:bg-gray-50 transition-all text-center shadow-sm">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
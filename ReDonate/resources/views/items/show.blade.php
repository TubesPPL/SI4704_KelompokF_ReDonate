<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Barang - ReDonate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">
    <nav class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
        <a href="{{ route('dashboard') }}" class="text-green-600 font-bold text-xl flex items-center gap-2">
            <i class="fa-solid fa-heart"></i> ReDonate
        </a>
    </nav>

    <div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-green-600 mb-6 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row">
            <div class="md:w-1/2 bg-gray-100 relative min-h-[300px]">
                @if($item->image_url)
                    @if(Str::startsWith($item->image_url, ['http://', 'https://']))
                        <img src="{{ $item->image_url }}" alt="{{ $item->item_name }}" class="absolute inset-0 w-full h-full object-cover">
                    @else
                        <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->item_name }}" class="absolute inset-0 w-full h-full object-cover">
                    @endif
                @else
                    <div class="absolute inset-0 flex items-center justify-center text-gray-400 flex-col">
                        <i class="fa-regular fa-image text-4xl mb-2"></i>
                        <span>Tidak ada gambar</span>
                    </div>
                @endif
            </div>

            <div class="p-8 md:w-1/2 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="uppercase tracking-wider text-xs font-bold text-green-600 bg-green-50 px-3 py-1 rounded-full">
                            {{ $item->category->category_name ?? 'Tanpa Kategori' }}
                        </span>
                        <span class="bg-blue-50 text-blue-600 text-xs px-3 py-1 rounded-full font-medium border border-blue-100">
                            Kondisi: {{ ucfirst($item->condition) }}
                        </span>
                    </div>
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-4">{{ $item->item_name }}</h1>
                    <p class="text-gray-600 leading-relaxed mb-8">
                        {{ $item->description ?: 'Tidak ada deskripsi untuk barang ini.' }}
                    </p>

                    <div class="space-y-4 border-t border-gray-100 pt-6">
                        <div class="flex items-center text-gray-600">
                            <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center mr-3">
                                <i class="fa-solid fa-location-dot text-gray-400"></i>
                            </div>
                            <span class="font-medium">{{ $item->location ?? 'Lokasi tidak diketahui' }}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center mr-3">
                                <i class="fa-regular fa-user text-gray-400"></i>
                            </div>
                            <span>Didonasikan oleh <strong class="text-gray-900">{{ $item->user->name ?? 'Anonim' }}</strong></span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center mr-3">
                                <i class="fa-regular fa-calendar text-gray-400"></i>
                            </div>
                            <span>Diunggah pada {{ $item->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-10 pt-6 border-t border-gray-100">
                    <a href="{{ route('requests.create', $item->id) }}" class="w-full flex items-center justify-center px-8 py-4 border border-transparent text-lg font-bold rounded-xl text-white bg-green-600 hover:bg-green-700 transition-all duration-200 shadow-sm hover:shadow">
                        <i class="fa-solid fa-hand-holding-heart mr-2"></i> Ajukan Permintaan
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
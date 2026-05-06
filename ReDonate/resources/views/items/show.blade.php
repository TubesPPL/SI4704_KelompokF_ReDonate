<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Barang - ReDonate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-50">

    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-20">
            <a href="{{ route('dashboard') }}" class="text-green-600 font-bold text-2xl flex items-center">
                <span class="mr-1">🌱</span> ReDonate
            </a>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded-2xl shadow-sm border border-gray-100">
        <a href="{{ route('dashboard') }}" class="text-green-600 hover:underline mb-6 inline-block font-semibold">&larr; Kembali ke Dashboard</a>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                @if($item->image_url)
                    <img src="{{ Str::startsWith($item->image_url, ['http', 'https']) ? $item->image_url : asset('storage/' . $item->image_url) }}" 
                         alt="{{ $item->item_name }}" 
                         class="w-full h-96 object-cover rounded-xl border border-gray-200">
                @else
                    <img src="https://placehold.co/400x400/e2e8f0/475569?text=No+Image" alt="No Image" class="w-full h-96 object-cover rounded-xl border border-gray-200">
                @endif
            </div>
            
            <div class="flex flex-col justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $item->item_name }}</h1>
                    <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-bold mb-4">{{ ucfirst($item->condition) }}</span>
                    
                    <p class="text-gray-600 mb-6 leading-relaxed">{{ $item->description ?? 'Tidak ada deskripsi.' }}</p>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex items-center text-gray-700">
                            <i class="fa-solid fa-location-dot w-6 text-gray-400"></i> 
                            <span class="font-medium">{{ $item->location ?? 'Lokasi tidak diketahui' }}</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fa-regular fa-user w-6 text-gray-400"></i> 
                            <span class="font-medium">{{ $item->user->name ?? 'Donatur' }}</span>
                        </div>
                    </div>
                </div>

                <div>
                    @if(!Auth::check())
                        <a href="{{ route('login') }}" class="block w-full text-center bg-blue-600 text-white font-bold py-4 rounded-xl">
                            Login untuk Request
                        </a>
                    @elseif(Auth::id() === $item->user_id)
                        <button disabled class="block w-full text-center bg-gray-200 text-gray-500 font-bold py-4 rounded-xl cursor-not-allowed">
                            Ini adalah barang donasi Anda
                        </button>
                    @else
                        <form action="{{ route('request.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                            <input type="hidden" name="title" value="Permintaan untuk {{ $item->item_name }}">
                            <input type="hidden" name="quantity" value="1">
                            <input type="hidden" name="description" value="Saya tertarik dengan barang ini.">
                            
                            <button type="submit" class="block w-full text-center bg-green-600 text-white font-bold py-4 rounded-xl hover:bg-green-700">
                                <i class="fa-solid fa-hand-holding-heart mr-2"></i> Request Barang Ini
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>
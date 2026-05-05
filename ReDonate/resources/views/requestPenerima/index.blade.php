<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Permintaan Saya - ReDonate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-800">

    <!-- Navbar Simple -->
    <nav class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="text-green-600 font-bold text-xl flex items-center gap-2">
                <i class="fa-solid fa-heart"></i> ReDonate
            </a>
            <div class="text-sm text-gray-500 font-medium">Panel Penerima</div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto py-10 px-4 sm:px-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Permintaan Saya</h1>
                <p class="text-gray-500 mt-1">Pantau status barang yang Anda ajukan di sini.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                Cari Barang Lain
            </a>
        </div>

        <!-- Alert Success -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-xl"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50/80 border-b border-gray-200">
                        <tr>
                            <th class="p-5 text-sm font-bold text-gray-600 uppercase tracking-wider">Barang</th>
                            <th class="p-5 text-sm font-bold text-gray-600 uppercase tracking-wider">Waktu Pengajuan</th>
                            <th class="p-5 text-sm font-bold text-gray-600 uppercase tracking-wider">Metode Pengambilan</th>
                            <th class="p-5 text-sm font-bold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="p-5 text-sm font-bold text-gray-600 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($requests as $req)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <!-- Barang -->
                            <td class="p-5">
                                <div class="font-bold text-gray-900">{{ $req->item->item_name ?? 'Barang telah dihapus' }}</div>
                                <div class="text-xs text-gray-500 mt-1 truncate max-w-xs">{{ $req->message ?: 'Tanpa pesan' }}</div>
                            </td>
                            <!-- Tanggal -->
                            <td class="p-5 text-gray-600 text-sm">
                                {{ $req->created_at->format('d M Y, H:i') }}
                            </td>
                            <!-- Metode -->
                            <td class="p-5">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold border border-gray-200">
                                    @if($req->pickup_method == 'ambil_sendiri') <i class="fa-solid fa-person-walking"></i>
                                    @elseif($req->pickup_method == 'kurir') <i class="fa-solid fa-truck"></i>
                                    @else <i class="fa-solid fa-handshake"></i> @endif
                                    {{ str_replace('_', ' ', Str::title($req->pickup_method)) }}
                                </span>
                            </td>
                            <!-- Status -->
                            <td class="p-5">
                                @if($req->status == 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-yellow-50 text-yellow-700 text-xs font-bold border border-yellow-200">
                                        <i class="fa-regular fa-clock"></i> Menunggu
                                    </span>
                                @elseif($req->status == 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-bold border border-green-200">
                                        <i class="fa-solid fa-check"></i> Disetujui
                                    </span>
                                @elseif($req->status == 'cancelled' || $req->status == 'rejected')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold border border-red-200">
                                        <i class="fa-solid fa-xmark"></i> {{ ucfirst($req->status) }}
                                    </span>
                                @endif
                            </td>
                            <!-- Aksi -->
                            <td class="p-5 text-right">
                                @if($req->status == 'pending')
                                    <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('requests.edit', $req->id) }}" class="w-9 h-9 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-colors" title="Ubah Preferensi">
                                            <i class="fa-solid fa-pen text-sm"></i>
                                        </a>
                                        <form action="{{ route('requests.cancel', $req->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan permintaan barang ini?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-colors" title="Batalkan Permintaan">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Terkunci</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-box-open text-6xl mb-4 text-gray-300"></i>
                                    <p class="text-lg font-medium text-gray-600">Belum ada barang yang Anda minta.</p>
                                    <p class="text-sm mt-1 mb-6">Mulai jelajahi barang-barang menarik yang bisa Anda dapatkan.</p>
                                    <a href="{{ route('dashboard') }}" class="px-6 py-2.5 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors shadow-sm">Jelajahi Sekarang</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
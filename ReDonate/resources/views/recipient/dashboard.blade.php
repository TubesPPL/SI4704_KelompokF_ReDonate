<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Klaim Saya') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if (session('success'))
                <div class="bg-teal-50 border-l-4 border-teal-500 p-4 mb-4 rounded shadow-sm">
                    <p class="text-sm text-teal-700 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border-l-4 border-amber-500">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate mb-1">Menunggu Konfirmasi</dt>
                        <dd class="text-3xl font-bold text-gray-900">{{ $pendingCount }}</dd>
                    </dl>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border-l-4 border-teal-500">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate mb-1">Klaim Disetujui</dt>
                        <dd class="text-3xl font-bold text-gray-900">{{ $approvedCount }}</dd>
                    </dl>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border-l-4 border-green-500">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate mb-1">Donasi Selesai</dt>
                        <dd class="text-3xl font-bold text-gray-900">{{ $completedCount }}</dd>
                    </dl>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border-l-4 border-red-500">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate mb-1">Klaim Ditolak</dt>
                        <dd class="text-3xl font-bold text-gray-900">{{ $rejectedCount }}</dd>
                    </dl>
                </div>
            </div>

            <!-- List Klaim -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 border-b border-gray-100 bg-white flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">Daftar Pengajuan Klaim</h3>
                    <a href="{{ route('catalog.index') }}" class="text-sm text-teal-600 font-medium hover:text-teal-800 transition">Cari Barang Lain</a>
                </div>
                
                <div class="divide-y divide-gray-100">
                    @forelse($claims as $claim)
                        <div class="p-6 flex flex-col md:flex-row gap-6 items-start hover:bg-gray-50 transition">
                            <!-- Image -->
                            <div class="flex-shrink-0 w-full md:w-32">
                                @if($claim->item->images && count($claim->item->images) > 0)
                                    <img class="h-24 w-full md:w-32 rounded-lg object-cover shadow-sm border border-gray-200" src="{{ Storage::url($claim->item->images[0]) }}" alt="{{ $claim->item->title }}">
                                @else
                                    <div class="h-24 w-full md:w-32 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200 text-gray-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Info -->
                            <div class="flex-1 w-full">
                                <div class="flex justify-between items-start gap-4">
                                    <div>
                                        <a href="{{ route('items.show', $claim->item->slug) }}" class="text-lg font-bold text-gray-900 hover:text-teal-600 transition line-clamp-1 mb-1">{{ $claim->item->title }}</a>
                                        <p class="text-xs text-gray-500 mb-3 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Diajukan {{ $claim->created_at->format('d M Y') }}
                                        </p>
                                    </div>
                                    
                                    <!-- Badge Status -->
                                    <div>
                                        @if($claim->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">Menunggu</span>
                                        @elseif($claim->status === 'approved')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-teal-100 text-teal-800">Disetujui</span>
                                        @elseif($claim->status === 'completed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Selesai</span>
                                        @elseif($claim->status === 'rejected')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Ditolak</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Pesan Klaim -->
                                <div class="bg-white p-3 rounded border border-gray-100 text-sm text-gray-600 italic shadow-sm mb-4 relative">
                                    <div class="absolute -top-2 left-5 w-4 h-4 bg-white border-t border-l border-gray-100 transform rotate-45"></div>
                                    "{{ $claim->message }}"
                                </div>

                                <!-- Status Info Box -->
                                @if($claim->status === 'pending')
                                    <p class="text-sm font-medium text-amber-600 flex items-center gap-1.5 bg-amber-50 p-2 rounded">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        Menunggu konfirmasi dari donatur.
                                    </p>
                                @elseif($claim->status === 'approved')
                                    <div class="bg-teal-50 border border-teal-100 p-4 rounded-lg">
                                        <p class="text-sm font-bold text-teal-800 mb-2">🎉 Selamat! Klaim Anda disetujui.</p>
                                        <p class="text-sm text-teal-700 mb-3">Silakan hubungi donatur untuk mengatur proses serah terima barang.</p>
                                        <div class="flex items-center justify-between flex-wrap gap-4 bg-white p-3 rounded border border-teal-100">
                                            <div class="flex items-center gap-3">
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ $claim->item->user->avatar ? Storage::url($claim->item->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($claim->item->user->name).'&color=0D9488&background=CCFBF1' }}" alt="{{ $claim->item->user->name }}">
                                                <div>
                                                    <p class="text-sm font-bold text-gray-900">{{ $claim->item->user->name }}</p>
                                                    <p class="text-xs font-medium text-gray-500">{{ $claim->item->user->phone ?? 'Nomor HP tidak tersedia' }}</p>
                                                </div>
                                            </div>
                                            @php $unreadCount = $claim->unreadMessagesCount(Auth::id()); @endphp
                                            <a href="{{ route('chat.show', $claim->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold rounded-lg shadow-sm transition relative">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                                Buka Chat
                                                @if($unreadCount > 0)
                                                    <span class="absolute -top-2 -right-2 flex h-5 w-5">
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-5 w-5 bg-red-500 text-[10px] font-bold text-white items-center justify-center">{{ $unreadCount }}</span>
                                                    </span>
                                                @endif
                                            </a>
                                        </div>
                                    </div>
                                @elseif($claim->status === 'rejected')
                                    <div class="bg-red-50 p-3 rounded-lg border border-red-100 text-sm text-red-700">
                                        <p class="font-bold mb-1">Maaf, klaim tidak disetujui.</p>
                                        <p class="text-xs">Alasan donatur: {{ $claim->notes ?? 'Barang telah diberikan kepada pemohon lain atau alasan lain.' }}</p>
                                    </div>
                                @elseif($claim->status === 'completed')
                                    <div class="bg-green-50 p-4 rounded-lg border border-green-100 flex flex-col md:flex-row justify-between items-center gap-4">
                                        <div>
                                            <p class="text-sm font-bold text-green-800">Donasi berhasil diterima!</p>
                                            <p class="text-xs text-green-700">Terima kasih telah menggunakan ReDonate.</p>
                                        </div>
                                        @if(!$claim->review)
                                            <a href="{{ route('reviews.create', $claim->id) }}" class="whitespace-nowrap px-4 py-2 bg-white border border-green-300 text-green-700 rounded shadow-sm text-sm font-bold hover:bg-green-50 transition">
                                                ⭐ Beri Ulasan Donatur
                                            </a>
                                        @else
                                            <span class="text-xs font-bold text-gray-500 flex items-center gap-1">
                                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                Ulasan telah diberikan
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <div class="mx-auto h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">Belum Ada Klaim</h3>
                            <p class="text-gray-500 mb-4">Anda belum mengajukan klaim untuk barang apapun.</p>
                            <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-md hover:bg-teal-700 transition shadow-sm">
                                Jelajahi Katalog Barang
                            </a>
                        </div>
                    @endforelse
                </div>
                
                @if($claims->hasPages())
                    <div class="p-4 border-t border-gray-100 bg-gray-50">
                        {{ $claims->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

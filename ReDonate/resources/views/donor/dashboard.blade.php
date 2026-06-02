<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Donatur') }}
            </h2>
            <a href="{{ route('items.create') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-700 focus:bg-teal-700 active:bg-teal-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Donasikan Barang
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if (session('success'))
                <div class="bg-teal-50 border-l-4 border-teal-500 p-4 mb-4 rounded shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-teal-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-teal-700 font-medium">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-teal-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-teal-100 rounded-md p-3">
                            <svg class="h-6 w-6 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Barang Aktif</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $activeItemsCount }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-amber-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-amber-100 rounded-md p-3">
                            <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Sedang Diklaim</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $claimedItemsCount }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Selesai Didonasikan</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $completedItemsCount }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Dilihat</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ number_format($totalViews) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Klaim Masuk Section -->
            @if($incomingClaims->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-amber-200">
                <div class="p-6 bg-amber-50 border-b border-amber-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-amber-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        Persetujuan Klaim Masuk ({{ $incomingClaims->count() }})
                    </h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($incomingClaims as $claim)
                    <div class="p-6 flex flex-col md:flex-row gap-6 items-start md:items-center hover:bg-gray-50 transition">
                        <div class="flex-shrink-0">
                            @if($claim->item->images && count($claim->item->images) > 0)
                                <img class="h-20 w-20 rounded-lg object-cover shadow-sm border border-gray-200" src="{{ Storage::url($claim->item->images[0]) }}" alt="{{ $claim->item->title }}">
                            @else
                                <div class="h-20 w-20 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200 text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="text-md font-bold text-gray-900">{{ $claim->item->title }}</h4>
                            <div class="mt-2 flex items-center gap-3">
                                <img class="h-8 w-8 rounded-full object-cover" src="{{ $claim->user->avatar ? Storage::url($claim->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($claim->user->name).'&color=0D9488&background=CCFBF1' }}" alt="{{ $claim->user->name }}">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $claim->user->name }} <span class="text-xs font-normal text-gray-500">mengajukan klaim</span></p>
                                    <p class="text-xs text-gray-500">{{ $claim->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="mt-3 bg-white p-3 rounded border border-gray-100 text-sm text-gray-600 italic shadow-sm relative">
                                <div class="absolute -top-2 left-5 w-4 h-4 bg-white border-t border-l border-gray-100 transform rotate-45"></div>
                                "{{ $claim->message }}"
                            </div>
                            @if($claim->pickup_date)
                                <p class="mt-2 text-xs font-medium text-teal-600 bg-teal-50 inline-block px-2 py-1 rounded">Rencana Pengambilan: {{ $claim->pickup_date->format('d M Y') }}</p>
                            @endif
                        </div>
                        <div class="flex flex-col gap-2 w-full md:w-auto mt-4 md:mt-0">
                            <form action="{{ route('claims.approve', $claim->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-md shadow-sm transition">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Setujui
                                </button>
                            </form>
                            <form action="{{ route('claims.reject', $claim->id) }}" method="POST" x-data="{ showReason: false }">
                                @csrf
                                @method('PATCH')
                                <button type="button" @click="showReason = !showReason" x-show="!showReason" class="w-full inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium rounded-md shadow-sm transition">
                                    Tolak
                                </button>
                                <div x-show="showReason" x-cloak class="flex flex-col gap-2">
                                    <input type="text" name="notes" placeholder="Alasan (opsional)..." class="text-sm border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                                    <div class="flex gap-2">
                                        <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium py-1.5 rounded-md transition">Kirim Tolakan</button>
                                        <button type="button" @click="showReason = false" class="px-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-md transition">Batal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Tabel Barang Donasi -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">Barang Donasi Anda</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klaim Masuk</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($items as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($item->images && count($item->images) > 0)
                                                <img class="h-10 w-10 rounded-md object-cover" src="{{ Storage::url($item->images[0]) }}" alt="">
                                            @else
                                                <div class="h-10 w-10 rounded-md bg-gray-100 flex items-center justify-center text-gray-400">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 truncate max-w-xs">{{ $item->title }}</div>
                                            <div class="text-sm text-gray-500">{{ $item->category->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->status === 'active')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-teal-100 text-teal-800">Aktif</span>
                                    @elseif($item->status === 'draft')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Draft</span>
                                    @elseif($item->status === 'claimed')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">Diklaim</span>
                                    @elseif($item->status === 'completed')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Dibatalkan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->claims()->pending()->count() }} Permintaan
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end gap-3">
                                    <a href="{{ route('items.show', $item->slug) }}" class="text-teal-600 hover:text-teal-900" title="Lihat">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    
                                    @if(in_array($item->status, ['active', 'draft']))
                                        <a href="{{ route('items.edit', $item->slug) }}" class="text-amber-600 hover:text-amber-900" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        
                                        <form action="{{ route('items.destroy', $item->slug) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan donasi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Batalkan">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @endif

                                    @if($item->status === 'claimed')
                                        @php
                                            $approvedClaim = $item->claims()->where('status', 'approved')->first();
                                            $unreadCount = $approvedClaim ? $approvedClaim->unreadMessagesCount(Auth::id()) : 0;
                                        @endphp
                                        @if($approvedClaim)
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('chat.show', $approvedClaim->id) }}" class="inline-flex items-center text-teal-600 hover:text-teal-900 bg-teal-50 hover:bg-teal-100 px-3 py-1 rounded-full transition relative">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                                    Chat
                                                    @if($unreadCount > 0)
                                                        <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                            <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-[9px] font-bold text-white items-center justify-center">{{ $unreadCount }}</span>
                                                        </span>
                                                    @endif
                                                </a>
                                                <form action="{{ route('claims.complete', $approvedClaim->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah serah terima barang sudah benar-benar selesai?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-green-600 hover:text-green-900 font-bold underline" title="Selesaikan">
                                                        Selesai
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    Belum ada barang yang didonasikan.
                                    <div class="mt-4">
                                        <a href="{{ route('items.create') }}" class="text-teal-600 hover:text-teal-900 font-medium hover:underline">Mulai donasi pertamamu sekarang!</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($items->hasPages())
                    <div class="p-4 border-t border-gray-200">
                        {{ $items->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

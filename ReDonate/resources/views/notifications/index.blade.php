<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pusat Notifikasi') }}
            </h2>
            <form action="{{ route('notifications.markAllRead') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm font-medium text-teal-600 hover:text-teal-800 bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200 transition">
                    Tandai Semua Dibaca
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="bg-teal-50 border-l-4 border-teal-500 p-4 mb-6 rounded shadow-sm">
                    <p class="text-sm text-teal-700 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <!-- Filters -->
                <div class="px-6 py-4 border-b border-gray-100 flex gap-4 bg-gray-50">
                    <a href="{{ route('notifications.index') }}" class="text-sm font-bold px-4 py-2 rounded-full transition {{ !request()->has('filter') ? 'bg-teal-600 text-white shadow-sm' : 'text-gray-500 hover:bg-gray-200' }}">
                        Semua Notifikasi
                    </a>
                    <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" class="text-sm font-bold px-4 py-2 rounded-full transition {{ request('filter') === 'unread' ? 'bg-teal-600 text-white shadow-sm' : 'text-gray-500 hover:bg-gray-200' }}">
                        Belum Dibaca
                    </a>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($notifications as $notif)
                        <div class="p-6 flex items-start gap-4 hover:bg-gray-50 transition {{ is_null($notif->read_at) ? 'bg-teal-50/20' : '' }}">
                            
                            <!-- Ikon -->
                            <div class="flex-shrink-0 mt-1">
                                @if($notif->type === 'claim_received')
                                    <div class="h-10 w-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shadow-sm border border-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg></div>
                                @elseif($notif->type === 'claim_approved' || $notif->type === 'claim_completed')
                                    <div class="h-10 w-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center shadow-sm border border-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                                @elseif($notif->type === 'claim_rejected')
                                    <div class="h-10 w-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center shadow-sm border border-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                                @elseif($notif->type === 'review_received')
                                    <div class="h-10 w-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center shadow-sm border border-white"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg></div>
                                @else
                                    <div class="h-10 w-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shadow-sm border border-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                                @endif
                            </div>

                            <!-- Konten -->
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('notifications.read', $notif->id) }}" class="block">
                                    <div class="flex items-center gap-2 mb-1">
                                        <p class="text-base font-bold text-gray-900 {{ is_null($notif->read_at) ? '' : 'font-medium' }}">
                                            {{ $notif->title }}
                                        </p>
                                        @if(is_null($notif->read_at))
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-teal-100 text-teal-800 uppercase tracking-wide">Baru</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2 leading-relaxed">{{ $notif->message }}</p>
                                    <p class="text-xs text-gray-400 font-medium flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $notif->created_at->diffForHumans() }} 
                                        <span class="mx-1">•</span> 
                                        {{ $notif->created_at->format('d M Y, H:i') }}
                                    </p>
                                </a>
                            </div>

                            <!-- Actions -->
                            <div class="flex-shrink-0 flex items-center gap-2">
                                <form action="{{ route('notifications.destroy', $notif->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-full transition" title="Hapus Notifikasi">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-16 text-center">
                            <div class="mx-auto h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada notifikasi</h3>
                            <p class="text-gray-500">
                                @if(request()->has('filter') && request('filter') === 'unread')
                                    Anda sudah membaca semua notifikasi yang ada.
                                @else
                                    Belum ada aktivitas terkait akun Anda.
                                @endif
                            </p>
                        </div>
                    @endforelse
                </div>

                @if($notifications->hasPages())
                    <div class="p-4 border-t border-gray-100 bg-gray-50">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

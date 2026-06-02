<div x-data="{ open: false }" class="relative" @click.outside="open = false" @close.stop="open = false">
    @php
        $unreadCount = Auth::user()->notifications()->whereNull('read_at')->count();
        $notifications = Auth::user()->notifications()->latest()->take(5)->get();
    @endphp

    <button @click="open = ! open" class="relative p-2 text-gray-400 hover:text-gray-500 focus:outline-none transition ease-in-out duration-150 mr-3">
        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        
        @if($unreadCount > 0)
            <span class="absolute top-1 right-1 block h-4 w-4 rounded-full bg-red-500 text-white text-[10px] font-bold text-center leading-4 shadow-sm animate-pulse">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 sm:w-96 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50 overflow-hidden"
         style="display: none;">
         
         <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50">
             <h3 class="text-sm font-bold text-gray-900">Notifikasi</h3>
             @if($unreadCount > 0)
                <form action="{{ route('notifications.markAllRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs text-teal-600 hover:text-teal-800 font-medium">Tandai semua dibaca</button>
                </form>
             @endif
         </div>

         <div class="max-h-96 overflow-y-auto divide-y divide-gray-100">
             @forelse($notifications as $notif)
                 <a href="{{ route('notifications.read', $notif->id) }}" class="block p-4 hover:bg-gray-50 transition {{ is_null($notif->read_at) ? 'bg-teal-50/30' : '' }}">
                     <div class="flex gap-3">
                         <!-- Ikon Tipe -->
                         <div class="flex-shrink-0 mt-0.5">
                             @if($notif->type === 'claim_received')
                                 <div class="h-8 w-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg></div>
                             @elseif($notif->type === 'claim_approved' || $notif->type === 'claim_completed')
                                 <div class="h-8 w-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                             @elseif($notif->type === 'claim_rejected')
                                 <div class="h-8 w-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                             @elseif($notif->type === 'review_received')
                                 <div class="h-8 w-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg></div>
                             @else
                                 <div class="h-8 w-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                             @endif
                         </div>
                         
                         <!-- Konten -->
                         <div class="flex-1 min-w-0">
                             <p class="text-sm font-semibold text-gray-900 truncate {{ is_null($notif->read_at) ? 'font-bold' : '' }}">
                                 {{ $notif->title }}
                             </p>
                             <p class="text-xs text-gray-600 line-clamp-2 mt-0.5">{{ $notif->message }}</p>
                             <p class="text-[10px] text-gray-400 mt-1 font-medium">{{ $notif->created_at->diffForHumans() }}</p>
                         </div>
                         
                         <!-- Titik Belum Dibaca -->
                         @if(is_null($notif->read_at))
                             <div class="flex-shrink-0 flex items-center">
                                 <div class="h-2 w-2 bg-teal-500 rounded-full"></div>
                             </div>
                         @endif
                     </div>
                 </a>
             @empty
                 <div class="p-6 text-center text-sm text-gray-500">
                     <p>Belum ada notifikasi.</p>
                 </div>
             @endforelse
         </div>

         <div class="p-2 border-t border-gray-100 bg-gray-50 text-center">
             <a href="{{ route('notifications.index') }}" class="block text-sm font-bold text-teal-600 hover:text-teal-800 transition py-1">
                 Lihat Semua Notifikasi
             </a>
         </div>
    </div>
</div>

<div x-data="{ open: false }" class="relative" @click.outside="open = false" @close.stop="open = false">
    @php
        $user = Auth::user();
        $globalUnreadChatCount = $user->unreadChatCount();
        
        // Ambil klaim yang berstatus approved dimana user sebagai pemohon atau donatur
        $activeChats = \App\Models\Claim::where('status', 'approved')
            ->where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereHas('item', function($q2) use ($user) {
                      $q2->where('user_id', $user->id);
                  });
            })
            ->with(['item.user', 'user', 'messages' => function($q) {
                $q->latest();
            }])
            ->get()
            ->sortByDesc(function($claim) {
                return $claim->messages->first()->created_at ?? $claim->updated_at;
            })
            ->take(5); // Ambil 5 chat teratas
    @endphp

    <button @click="open = ! open" class="relative p-2 text-gray-400 hover:text-gray-500 focus:outline-none transition ease-in-out duration-150 mr-1" title="Pesan Obrolan">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
        
        @if($globalUnreadChatCount > 0)
            <span class="absolute top-1 right-1 block h-4 w-4 rounded-full bg-red-500 text-white text-[10px] font-bold text-center leading-4 shadow-sm animate-pulse">
                {{ $globalUnreadChatCount > 9 ? '9+' : $globalUnreadChatCount }}
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
             <h3 class="text-sm font-bold text-gray-900">Pesan Obrolan</h3>
         </div>

         <div class="max-h-96 overflow-y-auto divide-y divide-gray-100">
             @forelse($activeChats as $chat)
                 @php
                    $isDonor = $chat->item->user_id === $user->id;
                    $opponent = $isDonor ? $chat->user : $chat->item->user;
                    $unreadCount = $chat->unreadMessagesCount($user->id);
                    $lastMessage = $chat->messages->first();
                 @endphp
                 <a href="{{ route('chat.show', $chat->id) }}" class="block p-4 hover:bg-gray-50 transition {{ $unreadCount > 0 ? 'bg-teal-50/30' : '' }}">
                     <div class="flex gap-3">
                         <div class="flex-shrink-0 mt-0.5">
                            <img class="h-10 w-10 rounded-full object-cover ring-2 ring-white shadow-sm" src="{{ $opponent->avatar ? Storage::url($opponent->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($opponent->name).'&color=0D9488&background=CCFBF1' }}" alt="{{ $opponent->name }}">
                         </div>
                         
                         <div class="flex-1 min-w-0">
                             <div class="flex justify-between items-baseline mb-0.5">
                                 <p class="text-sm font-bold text-gray-900 truncate {{ $unreadCount > 0 ? 'text-teal-900' : '' }}">
                                     {{ $opponent->name }}
                                 </p>
                                 @if($lastMessage)
                                    <p class="text-[10px] text-gray-400 font-medium whitespace-nowrap ml-2">{{ $lastMessage->created_at->diffForHumans(null, true, true) }}</p>
                                 @endif
                             </div>
                             
                             <p class="text-xs font-semibold text-teal-600 truncate mb-1">{{ $chat->item->title }}</p>
                             
                             <div class="flex justify-between items-center">
                                 <p class="text-xs text-gray-500 truncate {{ $unreadCount > 0 ? 'font-semibold text-gray-700' : '' }}">
                                     @if($lastMessage)
                                         @if($lastMessage->sender_id === $user->id)
                                            <span class="text-gray-400">Anda:</span> 
                                         @endif
                                         {{ $lastMessage->body }}
                                     @else
                                         <span class="italic">Mulai percakapan...</span>
                                     @endif
                                 </p>
                                 
                                 @if($unreadCount > 0)
                                     <span class="ml-2 inline-flex items-center justify-center h-5 min-w-[20px] px-1.5 rounded-full bg-red-500 text-white text-[10px] font-bold">
                                         {{ $unreadCount }}
                                     </span>
                                 @endif
                             </div>
                         </div>
                     </div>
                 </a>
             @empty
                 <div class="p-8 text-center flex flex-col items-center">
                     <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                         <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                     </div>
                     <p class="text-sm font-medium text-gray-900">Belum ada obrolan aktif</p>
                     <p class="text-xs text-gray-500 mt-1">Obrolan akan muncul di sini setelah klaim disetujui.</p>
                 </div>
             @endforelse
         </div>

         <div class="p-2 border-t border-gray-100 bg-gray-50 text-center">
             <a href="{{ route('dashboard') }}" class="block text-sm font-bold text-teal-600 hover:text-teal-800 transition py-1">
                 Lihat Dashboard
             </a>
         </div>
    </div>
</div>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('dashboard') }}" class="p-2 -ml-2 text-gray-500 hover:text-teal-600 transition rounded-full hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Obrolan') }}
            </h2>
        </div>
    </x-slot>

    @php
        $opponent = $claim->user_id === Auth::id() ? $claim->item->user : $claim->user;
        $isApproved = $claim->status === 'approved';
    @endphp

    <div class="py-6 h-[calc(100vh-140px)] flex flex-col">
        <div class="max-w-4xl mx-auto w-full px-4 sm:px-6 lg:px-8 h-full flex flex-col">
            
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm flex flex-col h-full overflow-hidden" 
                 x-data="chatSystem({{ $claim->id }}, {{ Auth::id() }}, {{ $messages->last()?->id ?? 0 }})">
                
                <!-- Chat Header -->
                <div class="bg-white border-b border-gray-100 p-4 sm:px-6 flex items-center justify-between z-10 shadow-sm flex-shrink-0">
                    <div class="flex items-center gap-3 min-w-0">
                        <img class="h-10 w-10 rounded-full object-cover ring-2 ring-gray-50 flex-shrink-0" src="{{ $opponent->avatar ? Storage::url($opponent->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($opponent->name).'&color=0D9488&background=CCFBF1' }}" alt="{{ $opponent->name }}">
                        <div class="min-w-0">
                            <h3 class="text-sm font-bold text-gray-900 truncate">{{ $opponent->name }}</h3>
                            <p class="text-xs text-gray-500 truncate">
                                @if($claim->user_id === Auth::id())
                                    Pemilik Barang (Donatur)
                                @else
                                    Pemohon (Penerima)
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('items.show', $claim->item->slug) }}" class="hidden sm:flex items-center gap-2 p-2 hover:bg-gray-50 rounded-lg transition border border-transparent hover:border-gray-100">
                            @if($claim->item->images && count($claim->item->images) > 0)
                                <img src="{{ Storage::url($claim->item->images[0]) }}" class="h-8 w-8 rounded object-cover" alt="Item">
                            @else
                                <div class="h-8 w-8 rounded bg-gray-100 flex items-center justify-center"><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                            @endif
                            <div class="text-right hidden md:block">
                                <p class="text-xs font-semibold text-gray-900 line-clamp-1 w-32">{{ $claim->item->title }}</p>
                                @php
                                    $sc = match($claim->status) {
                                        'pending'   => 'text-amber-600',
                                        'approved'  => 'text-teal-600',
                                        'completed' => 'text-green-600',
                                        'rejected'  => 'text-red-600',
                                        default     => 'text-gray-500',
                                    };
                                @endphp
                                <p class="text-[10px] font-bold uppercase {{ $sc }}">{{ $claim->status }}</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Chat Messages Area -->
                <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50/50 space-y-4" id="chat-messages" x-ref="messagesContainer" @scroll="checkScroll()">
                    
                    <template x-if="messages.length === 0">
                        <div class="h-full flex flex-col items-center justify-center text-gray-400 space-y-3">
                            <div class="h-16 w-16 bg-white rounded-full flex items-center justify-center shadow-sm">
                                <svg class="w-8 h-8 text-teal-200" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path></svg>
                            </div>
                            <p class="text-sm font-medium">Belum ada pesan. Sapa {{ $opponent->name }} sekarang!</p>
                            @if($claim->status === 'approved')
                                <p class="text-xs max-w-xs text-center leading-relaxed">Diskusikan lokasi dan waktu serah terima barang donasi di sini dengan aman.</p>
                            @endif
                        </div>
                    </template>

                    <template x-for="(msg, index) in messages" :key="msg.id">
                        <div class="flex flex-col">
                            <!-- Date Separator (simulated based on diff) -->
                            <template x-if="index === 0 || new Date(msg.created_at).toDateString() !== new Date(messages[index-1].created_at).toDateString()">
                                <div class="flex justify-center my-4">
                                    <span class="text-[10px] font-bold text-gray-500 bg-gray-200/50 px-3 py-1 rounded-full uppercase tracking-wider" x-text="formatDateHeader(msg.created_at)"></span>
                                </div>
                            </template>

                            <div :class="msg.sender_id === authId ? 'justify-end' : 'justify-start'" class="flex w-full mb-2">
                                <div :class="msg.sender_id === authId ? 'bg-teal-600 text-white rounded-tl-2xl rounded-tr-2xl rounded-bl-2xl rounded-br-sm' : 'bg-white text-gray-800 border border-gray-100 rounded-tl-2xl rounded-tr-2xl rounded-br-2xl rounded-bl-sm shadow-sm'" 
                                     class="max-w-[75%] sm:max-w-md px-4 py-2.5 relative group">
                                    
                                    <p class="text-sm whitespace-pre-wrap break-words leading-relaxed" x-text="msg.body"></p>
                                    
                                    <div class="flex items-center justify-end gap-1 mt-1 opacity-70">
                                        <span class="text-[10px]" :class="msg.sender_id === authId ? 'text-teal-100' : 'text-gray-400'" x-text="formatTime(msg.created_at)"></span>
                                        <!-- Read Receipt -->
                                        <template x-if="msg.sender_id === authId">
                                            <span class="ml-0.5">
                                                <svg x-show="msg.read_at" class="w-3.5 h-3.5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7M5 13l4 4L19 7"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 13l4 4L23 7" style="opacity:0.5"></path></svg>
                                                <svg x-show="!msg.read_at" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Chat Input Area -->
                <div class="bg-white border-t border-gray-100 p-4 flex-shrink-0">
                    @if(!$isApproved)
                        <div class="bg-gray-50 border border-gray-200 text-gray-500 rounded-lg p-3 text-center text-sm font-medium">
                            <svg class="w-5 h-5 mx-auto mb-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            @if($claim->status === 'completed')
                                Obrolan ditutup karena donasi telah selesai.
                            @else
                                Obrolan hanya aktif saat klaim disetujui.
                            @endif
                        </div>
                    @else
                        <form @submit.prevent="sendMessage" class="flex gap-2 items-end">
                            <div class="flex-1 relative">
                                <textarea x-model="newMessage" @keydown.enter.prevent="sendMessageOnEnter($event)"
                                          class="w-full rounded-xl border-gray-200 focus:border-teal-500 focus:ring-teal-500 shadow-sm text-sm py-3 px-4 resize-none min-h-[50px] max-h-[120px]" 
                                          rows="1" placeholder="Tulis pesan..." :disabled="isSending"
                                          x-ref="messageInput" @input="adjustTextareaHeight()"></textarea>
                            </div>
                            <button type="submit" :disabled="isSending || newMessage.trim() === ''" 
                                    class="h-[50px] px-5 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition flex items-center justify-center shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg x-show="!isSending" class="w-5 h-5 transform rotate-90" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
                                <svg x-show="isSending" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </button>
                        </form>
                        <p class="text-[10px] text-gray-400 mt-2 text-center">Tekan Enter untuk mengirim, Shift+Enter untuk baris baru.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function chatSystem(claimId, authId, initialLastId) {
            return {
                claimId: claimId,
                authId: authId,
                lastId: initialLastId,
                messages: @json($messages),
                newMessage: '',
                isSending: false,
                isPolling: false,
                pollInterval: null,
                shouldAutoScroll: true,

                init() {
                    this.$nextTick(() => { this.scrollToBottom(); });
                    
                    // Mulai polling jika status approved
                    @if($isApproved)
                        this.pollInterval = setInterval(() => this.pollMessages(), 5000);
                        
                        // Fokus ke input
                        this.$nextTick(() => { this.$refs.messageInput?.focus(); });
                    @endif

                    // Bersihkan interval saat komponen dihancurkan (misal pindah halaman via Turbolinks jika ada)
                    document.addEventListener('alpine:uninitialized', () => {
                        clearInterval(this.pollInterval);
                    });
                },

                checkScroll() {
                    const container = this.$refs.messagesContainer;
                    // Jika scroll berada di dekat paling bawah (toleransi 50px), aktifkan autoscroll
                    this.shouldAutoScroll = (container.scrollHeight - container.scrollTop - container.clientHeight) < 50;
                },

                scrollToBottom(force = false) {
                    if (this.shouldAutoScroll || force) {
                        const container = this.$refs.messagesContainer;
                        if(container) {
                            container.scrollTop = container.scrollHeight;
                        }
                    }
                },

                adjustTextareaHeight() {
                    const el = this.$refs.messageInput;
                    if(el) {
                        el.style.height = '50px';
                        el.style.height = (el.scrollHeight) + 'px';
                    }
                },

                sendMessageOnEnter(e) {
                    if (!e.shiftKey) {
                        this.sendMessage();
                    }
                },

                async sendMessage() {
                    const text = this.newMessage.trim();
                    if (text === '' || this.isSending) return;

                    this.isSending = true;
                    const tempMessage = {
                        id: 'temp-' + Date.now(),
                        sender_id: this.authId,
                        body: text,
                        created_at: new Date().toISOString(),
                        read_at: null,
                    };
                    
                    // Optimistic update
                    this.messages.push(tempMessage);
                    this.newMessage = '';
                    this.$nextTick(() => { 
                        this.adjustTextareaHeight();
                        this.scrollToBottom(true); 
                    });

                    try {
                        const response = await fetch(`/chat/${this.claimId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ body: text })
                        });

                        const data = await response.json();
                        if (response.ok) {
                            // Ganti pesan temporary dengan pesan asli dari server
                            const index = this.messages.findIndex(m => m.id === tempMessage.id);
                            if (index !== -1) {
                                this.messages[index] = data.message;
                                this.lastId = Math.max(this.lastId, data.message.id);
                            }
                        } else {
                            alert(data.error || 'Gagal mengirim pesan.');
                            this.messages = this.messages.filter(m => m.id !== tempMessage.id);
                        }
                    } catch (error) {
                        console.error('Error sending message:', error);
                        this.messages = this.messages.filter(m => m.id !== tempMessage.id);
                    } finally {
                        this.isSending = false;
                        this.$nextTick(() => { this.$refs.messageInput?.focus(); });
                    }
                },

                async pollMessages() {
                    if (this.isPolling) return;
                    this.isPolling = true;

                    try {
                        const response = await fetch(`/chat/${this.claimId}/poll?last_id=${this.lastId}`, {
                            headers: { 'Accept': 'application/json' }
                        });
                        const newMsgs = await response.json();

                        if (newMsgs && newMsgs.length > 0) {
                            newMsgs.forEach(msg => {
                                if (!this.messages.find(m => m.id === msg.id)) {
                                    this.messages.push(msg);
                                }
                                this.lastId = Math.max(this.lastId, msg.id);
                            });
                            this.$nextTick(() => { this.scrollToBottom(); });
                        }

                        // Periksa apakah pesan lama kita sekarang sudah dibaca
                        // Dalam sistem canggih kita bisa polling status read, tapi di sini
                        // kita biarkan saat poll message sekalian untuk simple implementation
                    } catch (error) {
                        console.error('Error polling messages:', error);
                    } finally {
                        this.isPolling = false;
                    }
                },

                formatTime(datetimeStr) {
                    const d = new Date(datetimeStr);
                    return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                },

                formatDateHeader(datetimeStr) {
                    const d = new Date(datetimeStr);
                    const today = new Date();
                    const yesterday = new Date(today);
                    yesterday.setDate(yesterday.getDate() - 1);

                    if (d.toDateString() === today.toDateString()) return 'Hari Ini';
                    if (d.toDateString() === yesterday.toDateString()) return 'Kemarin';
                    
                    return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                }
            }
        }
    </script>
</x-app-layout>

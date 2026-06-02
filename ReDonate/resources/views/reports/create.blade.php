<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporkan Konten') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('warning'))
                <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-6 rounded shadow-sm">
                    <p class="text-sm text-amber-700 font-medium">{{ session('warning') }}</p>
                    <a href="{{ url()->previous() }}" class="mt-2 inline-block text-xs font-bold text-amber-800 hover:underline">&larr; Kembali</a>
                </div>
            @endif
            
            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded shadow-sm">
                    <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="p-8">
                    <!-- Subject Info -->
                    <div class="mb-8 p-4 bg-gray-50 border border-gray-100 rounded-lg flex items-center gap-4">
                        @if($type === 'item')
                            @if($subject->images && count($subject->images) > 0)
                                <img src="{{ Storage::url($subject->images[0]) }}" alt="{{ $title }}" class="w-16 h-16 rounded-md object-cover shadow-sm">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded-md flex items-center justify-center text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-teal-600 bg-teal-50 px-2 py-0.5 rounded">Barang</span>
                                <h3 class="text-lg font-bold text-gray-900 mt-1">{{ $title }}</h3>
                                <p class="text-xs text-gray-500">Pemilik: {{ $subject->user->name }}</p>
                            </div>
                        @else
                            <img src="{{ $subject->avatar ? Storage::url($subject->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($subject->name).'&color=0D9488&background=CCFBF1' }}" alt="{{ $title }}" class="w-16 h-16 rounded-full object-cover shadow-sm">
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-purple-600 bg-purple-50 px-2 py-0.5 rounded">Pengguna</span>
                                <h3 class="text-lg font-bold text-gray-900 mt-1">{{ $title }}</h3>
                                <p class="text-xs text-gray-500">Bergabung: {{ $subject->created_at->format('M Y') }}</p>
                            </div>
                        @endif
                    </div>

                    <form action="{{ route('report.store') }}" method="POST" x-data="{ reason: '{{ old('reason') }}', loading: false }" @submit="loading = true">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">
                        <input type="hidden" name="id" value="{{ $subject->id }}">

                        <div class="mb-8">
                            <h4 class="text-sm font-bold text-gray-900 mb-4">Mengapa Anda melaporkan ini? *</h4>
                            <div class="space-y-3">
                                @php
                                    $reasons = $type === 'item' ? [
                                        'Barang Fiktif/Palsu' => 'Barang ini tidak nyata atau deskripsinya sangat menyesatkan.',
                                        'Konten Tidak Pantas' => 'Foto atau deskripsi mengandung unsur kebencian, kekerasan, atau pornografi.',
                                        'Komersialisasi/Penjualan' => 'Pemilik meminta bayaran atau meminta ganti ongkir yang tidak wajar.',
                                        'Lainnya' => 'Alasan lain yang tidak tercantum di atas.'
                                    ] : [
                                        'Spam/Penipuan' => 'Pengguna ini mengirim pesan spam atau mencoba menipu.',
                                        'Perilaku Kasar/Pelecehan' => 'Pengguna ini menggunakan kata-kata kasar atau mengancam.',
                                        'Akun Palsu' => 'Profil ini berpura-pura menjadi orang lain atau organisasi tertentu.',
                                        'Lainnya' => 'Alasan lain yang tidak tercantum di atas.'
                                    ];
                                @endphp

                                @foreach($reasons as $val => $desc)
                                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus-within:ring-2 focus-within:ring-teal-500 transition-colors hover:bg-gray-50"
                                           :class="reason === '{{ $val }}' ? 'border-teal-500 ring-1 ring-teal-500 bg-teal-50/10' : 'border-gray-200'">
                                        <input type="radio" name="reason" value="{{ $val }}" x-model="reason" class="sr-only" required>
                                        <div class="flex flex-1">
                                            <div class="flex flex-col">
                                                <span class="block text-sm font-bold text-gray-900">{{ $val }}</span>
                                                <span class="mt-1 flex items-center text-xs text-gray-500">{{ $desc }}</span>
                                            </div>
                                        </div>
                                        <div x-show="reason === '{{ $val }}'" class="text-teal-600" style="display: none;">
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                        </div>

                        <!-- Textarea Dinamis -->
                        <div x-show="reason === 'Lainnya'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" style="display: none;" class="mb-8" x-data="{ desc: '{{ old('description') }}' }">
                            <label for="description" class="block text-sm font-bold text-gray-900 mb-2">Jelaskan lebih detail *</label>
                            <textarea id="description" name="description" rows="4" x-model="desc" :required="reason === 'Lainnya'"
                                      placeholder="Berikan detail tambahan agar admin kami dapat meninjaunya dengan lebih baik..." 
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 text-sm" maxlength="500"></textarea>
                            <div class="flex justify-between items-center mt-1">
                                <x-input-error :messages="$errors->get('description')" />
                                <span class="text-xs text-gray-400 font-medium" :class="desc.length > 480 ? 'text-red-500' : ''" x-text="desc.length + '/500'"></span>
                            </div>
                        </div>

                        <!-- Peringatan -->
                        <div class="mb-8 bg-blue-50 border-l-4 border-blue-500 p-4 rounded text-sm text-blue-800 flex gap-3">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <p class="font-bold mb-1">Informasi Keamanan</p>
                                <p class="text-blue-700 text-xs leading-relaxed">Laporan Anda bersifat rahasia dan tidak akan diberitahukan kepada pihak yang dilaporkan. Mohon laporkan dengan jujur. Laporan palsu atau berulang (spam) dapat mengakibatkan pembatasan akun Anda sendiri.</p>
                            </div>
                        </div>

                        <!-- Aksi -->
                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
                            <a href="{{ url()->previous() }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Batal</a>
                            <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-lg shadow-sm transition flex items-center justify-center gap-2" :class="{'opacity-75 cursor-not-allowed': loading}" :disabled="loading">
                                <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Kirim Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

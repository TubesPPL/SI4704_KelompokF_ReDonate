<x-admin-layout>
    <x-slot name="header">Manajemen Barang Donasi</x-slot>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-5 flex flex-wrap gap-3 items-center">
        <form method="GET" class="flex flex-wrap gap-3 items-center flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul barang..."
                   class="flex-1 min-w-48 rounded-lg border-gray-200 text-sm focus:border-teal-500 focus:ring-teal-500 shadow-sm">

            <select name="status" onchange="this.form.submit()" class="rounded-lg border-gray-200 text-sm focus:border-teal-500 focus:ring-teal-500 shadow-sm">
                <option value="">Semua Status</option>
                @foreach(['active','draft','claimed','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status')===$s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-teal-600 text-white text-sm font-semibold rounded-lg hover:bg-teal-700 transition">Cari</button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.items.index') }}" class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
            @endif
        </form>
        <p class="text-sm text-gray-500 font-medium">{{ $items->total() }} barang</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Donatur</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Diunggah</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($items as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-3">
                            @if($item->images && count($item->images) > 0)
                                <img class="h-10 w-10 rounded-lg object-cover border border-gray-200 flex-shrink-0" src="{{ Storage::url($item->images[0]) }}" alt="{{ $item->title }}">
                            @else
                                <div class="h-10 w-10 rounded-lg bg-gray-100 flex-shrink-0 flex items-center justify-center text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div>
                                <a href="{{ route('items.show', $item->slug) }}" class="text-sm font-semibold text-gray-900 hover:text-teal-600 transition line-clamp-1">{{ $item->title }}</a>
                                <p class="text-xs text-gray-400">{{ $item->views }} kali dilihat</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-600">{{ $item->user->name }}</td>
                    <td class="px-6 py-3 text-sm text-gray-600">{{ $item->category->name }}</td>
                    <td class="px-6 py-3">
                        @php
                            $sc = match($item->status) {
                                'active'    => 'bg-teal-100 text-teal-700',
                                'draft'     => 'bg-gray-100 text-gray-600',
                                'claimed'   => 'bg-amber-100 text-amber-700',
                                'completed' => 'bg-green-100 text-green-700',
                                'cancelled' => 'bg-red-100 text-red-700',
                                default     => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $sc }}">{{ ucfirst($item->status) }}</span>
                    </td>
                    <td class="px-6 py-3 text-xs text-gray-400">{{ $item->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-3">
                        <div class="flex items-center justify-end gap-1" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" class="p-2 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path></svg>
                            </button>
                            <div x-show="open" x-transition class="absolute right-4 mt-1 w-52 bg-white border border-gray-200 rounded-xl shadow-lg z-10 overflow-hidden" style="display:none;">
                                <a href="{{ route('items.show', $item->slug) }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">Lihat Detail</a>
                                @foreach(['active'=>'Aktifkan', 'draft'=>'Set ke Draft', 'cancelled'=>'Batalkan'] as $status => $label)
                                    @if($item->status !== $status)
                                    <form action="{{ route('admin.items.updateStatus', $item) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $status }}">
                                        <button class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">{{ $label }}</button>
                                    </form>
                                    @endif
                                @endforeach
                                <form action="{{ route('admin.items.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus permanen barang ini?')">
                                    @csrf @method('DELETE')
                                    <button class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition font-medium">Hapus Permanen</button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">Tidak ada barang ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($items->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">{{ $items->links() }}</div>
        @endif
    </div>
</x-admin-layout>

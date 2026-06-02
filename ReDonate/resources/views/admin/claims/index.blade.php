<x-admin-layout>
    <x-slot name="header">Manajemen Klaim</x-slot>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-5 flex flex-wrap gap-3 items-center">
        <form method="GET" class="flex flex-wrap gap-3 items-center flex-1">
            <select name="status" onchange="this.form.submit()" class="rounded-lg border-gray-200 text-sm focus:border-teal-500 focus:ring-teal-500 shadow-sm">
                <option value="">Semua Status</option>
                @foreach(['pending'=>'Menunggu','approved'=>'Disetujui','completed'=>'Selesai','rejected'=>'Ditolak'] as $s => $l)
                    <option value="{{ $s }}" {{ request('status')===$s ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>
            @if(request('status'))
                <a href="{{ route('admin.claims.index') }}" class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
            @endif
        </form>
        <p class="text-sm text-gray-500 font-medium">{{ $claims->total() }} klaim</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pemohon</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Donatur</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Diajukan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($claims as $claim)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-3">
                            <img class="h-8 w-8 rounded-full object-cover border border-gray-200 flex-shrink-0"
                                 src="{{ $claim->user->avatar ? Storage::url($claim->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($claim->user->name).'&color=0D9488&background=CCFBF1' }}"
                                 alt="{{ $claim->user->name }}">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $claim->user->name }}</p>
                                <p class="text-xs text-gray-400 line-clamp-1">{{ Str::limit($claim->message, 40) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-3">
                        <a href="{{ route('items.show', $claim->item->slug) }}" class="text-sm font-semibold text-gray-900 hover:text-teal-600 transition line-clamp-1">{{ $claim->item->title }}</a>
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-600">{{ $claim->item->user->name }}</td>
                    <td class="px-6 py-3">
                        @php
                            $sc = match($claim->status) {
                                'pending'   => 'bg-amber-100 text-amber-700',
                                'approved'  => 'bg-teal-100 text-teal-700',
                                'completed' => 'bg-green-100 text-green-700',
                                'rejected'  => 'bg-red-100 text-red-700',
                                default     => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $sc }}">{{ ucfirst($claim->status) }}</span>
                    </td>
                    <td class="px-6 py-3 text-xs text-gray-400">{{ $claim->created_at->format('d M Y, H:i') }}</td>
                </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">Tidak ada klaim ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($claims->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">{{ $claims->links() }}</div>
        @endif
    </div>
</x-admin-layout>

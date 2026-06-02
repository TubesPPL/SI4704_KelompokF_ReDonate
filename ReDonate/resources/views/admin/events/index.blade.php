<x-admin-layout>
    <x-slot name="header">Manajemen Event</x-slot>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-5 flex flex-wrap gap-3 items-center">
        <form method="GET" class="flex flex-wrap gap-3 items-center flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul event..."
                   class="flex-1 min-w-48 rounded-lg border-gray-200 text-sm focus:border-teal-500 focus:ring-teal-500 shadow-sm">

            <select name="status" onchange="this.form.submit()" class="rounded-lg border-gray-200 text-sm focus:border-teal-500 focus:ring-teal-500 shadow-sm">
                <option value="">Semua Status</option>
                @foreach(['upcoming'=>'Akan Datang','active'=>'Berlangsung','completed'=>'Selesai','cancelled'=>'Dibatalkan'] as $s => $l)
                    <option value="{{ $s }}" {{ request('status')===$s ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-teal-600 text-white text-sm font-semibold rounded-lg hover:bg-teal-700 transition">Cari</button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.events.index') }}" class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
            @endif
        </form>
        <a href="{{ route('events.create') }}" class="px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Buat Event
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Event</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Penyelenggara</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Periode</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Progress</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($events as $event)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-3">
                            @if($event->banner)
                                <img class="h-10 w-14 rounded-lg object-cover border border-gray-200 flex-shrink-0" src="{{ Storage::url($event->banner) }}" alt="{{ $event->title }}">
                            @else
                                <div class="h-10 w-14 rounded-lg bg-gray-100 flex-shrink-0 flex items-center justify-center text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01"></path></svg>
                                </div>
                            @endif
                            <a href="{{ route('events.show', $event->slug) }}" class="text-sm font-semibold text-gray-900 hover:text-teal-600 transition line-clamp-1">{{ $event->title }}</a>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-600">{{ $event->creator->name }}</td>
                    <td class="px-6 py-3 text-xs text-gray-500">
                        <p>{{ $event->start_date->format('d M Y') }}</p>
                        <p class="text-gray-400">s/d {{ $event->end_date->format('d M Y') }}</p>
                    </td>
                    <td class="px-6 py-3">
                        @php $pct = $event->target_items > 0 ? min(100, round(($event->items_count / $event->target_items) * 100)) : 0; @endphp
                        <div class="flex items-center gap-2 min-w-24">
                            <div class="flex-1 bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-teal-500 h-1.5 rounded-full" style="width:{{ $pct }}%"></div>
                            </div>
                            <span class="text-xs font-bold text-gray-700">{{ $event->items_count }}/{{ $event->target_items }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-3">
                        @php
                            $sc = match($event->status) {
                                'active'    => 'bg-teal-100 text-teal-700',
                                'upcoming'  => 'bg-amber-100 text-amber-700',
                                'completed' => 'bg-green-100 text-green-700',
                                'cancelled' => 'bg-red-100 text-red-700',
                                default     => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $sc }}">{{ ucfirst($event->status) }}</span>
                    </td>
                    <td class="px-6 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('events.show', $event->slug) }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition" title="Lihat Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            <a href="{{ route('events.edit', $event->slug) }}" class="p-1.5 rounded-lg hover:bg-amber-50 text-gray-400 hover:text-amber-600 transition" title="Edit Event">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            @if($event->status !== 'cancelled')
                            <form action="{{ route('admin.events.cancel', $event) }}" method="POST" onsubmit="return confirm('Batalkan event ini?')">
                                @csrf @method('PATCH')
                                <button class="p-1.5 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-600 transition" title="Batalkan Event">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">Tidak ada event ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($events->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">{{ $events->links() }}</div>
        @endif
    </div>
</x-admin-layout>

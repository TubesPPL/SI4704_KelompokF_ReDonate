<x-admin-layout>
    <x-slot name="header">Dashboard</x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
        @php
            $cards = [
                ['label'=>'Total Pengguna',       'value'=>$stats['total_users'],          'icon'=>'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'color'=>'blue'],
                ['label'=>'Item Aktif',            'value'=>$stats['total_active_items'],   'icon'=>'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'color'=>'teal'],
                ['label'=>'Donasi Selesai (Bln)', 'value'=>$stats['completed_this_month'], 'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color'=>'green'],
                ['label'=>'Event Aktif',           'value'=>$stats['active_events'],        'icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'color'=>'purple'],
            ];
            $colorMap = [
                'blue'   => 'bg-blue-50 text-blue-600 border-blue-100',
                'teal'   => 'bg-teal-50 text-teal-600 border-teal-100',
                'green'  => 'bg-green-50 text-green-600 border-green-100',
                'purple' => 'bg-purple-50 text-purple-600 border-purple-100',
            ];
        @endphp

        @foreach($cards as $card)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl {{ $colorMap[$card['color']] }} flex items-center justify-center flex-shrink-0 border">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900">{{ number_format($card['value']) }}</p>
                <p class="text-sm text-gray-500 font-medium">{{ $card['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
        <!-- Chart -->
        <div class="xl:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h2 class="text-base font-bold text-gray-900 mb-4">Donasi Selesai (12 Bulan Terakhir)</h2>
            <div class="relative h-64">
                <canvas id="donationChart"></canvas>
            </div>
        </div>

        <!-- Recent Claims -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h2 class="text-base font-bold text-gray-900 mb-4">Aktivitas Klaim Terbaru</h2>
            <div class="space-y-3">
                @forelse($recentClaims as $claim)
                <div class="flex items-start gap-3 p-2 rounded-lg hover:bg-gray-50">
                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $claim->user->name }}</p>
                        <p class="text-xs text-gray-500 truncate">Klaim: {{ $claim->item->title }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            @php
                                $badgeClass = match($claim->status) {
                                    'pending'   => 'bg-amber-100 text-amber-700',
                                    'approved'  => 'bg-teal-100 text-teal-700',
                                    'completed' => 'bg-green-100 text-green-700',
                                    'rejected'  => 'bg-red-100 text-red-700',
                                    default     => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded {{ $badgeClass }} uppercase">{{ $claim->status }}</span>
                            <span class="text-[10px] text-gray-400">{{ $claim->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-4">Belum ada aktivitas klaim.</p>
                @endforelse
            </div>
            <a href="{{ route('admin.claims.index') }}" class="block mt-4 text-center text-xs font-bold text-teal-600 hover:text-teal-800 transition">Lihat Semua Klaim →</a>
        </div>
    </div>

    <!-- Recent Items Table -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-base font-bold text-gray-900">Barang Terbaru</h2>
            <a href="{{ route('admin.items.index') }}" class="text-xs font-bold text-teal-600 hover:text-teal-800">Lihat Semua →</a>
        </div>
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Donatur</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Diunggah</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($recentItems as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-3">
                        <a href="{{ route('items.show', $item->slug) }}" class="text-sm font-semibold text-gray-900 hover:text-teal-600 transition line-clamp-1">{{ $item->title }}</a>
                        <p class="text-xs text-gray-400">{{ $item->category->name }}</p>
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-600">{{ $item->user->name }}</td>
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
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
    const ctx = document.getElementById('donationChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Donasi Selesai',
                data: @json($data),
                backgroundColor: 'rgba(13, 148, 136, 0.15)',
                borderColor: 'rgba(13, 148, 136, 0.9)',
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f3f4f6' } },
                x: { grid: { display: false } }
            }
        }
    });
    </script>
</x-admin-layout>

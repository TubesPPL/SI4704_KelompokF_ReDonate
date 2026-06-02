<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Moderasi Laporan') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-teal-50 border-l-4 border-teal-500 p-4 mb-6 rounded shadow-sm">
                    <p class="text-sm text-teal-700 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                
                <!-- Filter Section -->
                <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gray-50/50">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.reports.index') }}" class="px-3 py-1.5 text-sm font-medium rounded-md {{ !request('status') ? 'bg-gray-800 text-white' : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50' }}">Semua</a>
                        <a href="{{ route('admin.reports.index', array_merge(request()->query(), ['status' => 'pending'])) }}" class="px-3 py-1.5 text-sm font-medium rounded-md flex items-center gap-1.5 {{ request('status') === 'pending' ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50' }}">
                            Pending
                            @if(isset($pendingReports) && $pendingReports > 0)
                                <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $pendingReports }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.reports.index', array_merge(request()->query(), ['status' => 'reviewing'])) }}" class="px-3 py-1.5 text-sm font-medium rounded-md {{ request('status') === 'reviewing' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50' }}">Ditinjau</a>
                        <a href="{{ route('admin.reports.index', array_merge(request()->query(), ['status' => 'resolved'])) }}" class="px-3 py-1.5 text-sm font-medium rounded-md {{ request('status') === 'resolved' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50' }}">Selesai</a>
                    </div>
                    
                    <form action="{{ route('admin.reports.index') }}" method="GET" class="flex gap-2" x-data="{ submitForm() { this.$el.submit() } }">
                        @if(request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        <select name="type" @change="submitForm" class="border-gray-300 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm text-sm">
                            <option value="">Semua Jenis</option>
                            <option value="item" {{ request('type') === 'item' ? 'selected' : '' }}>Barang</option>
                            <option value="user" {{ request('type') === 'user' ? 'selected' : '' }}>Pengguna</option>
                        </select>
                    </form>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Subjek</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Alasan Laporan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pelapor</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($reports as $report)
                                <tr class="{{ $report->status === 'pending' ? 'bg-amber-50/30' : '' }} hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($report->reportable_type === 'App\Models\Item')
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if($report->reportable && $report->reportable->images && count($report->reportable->images) > 0)
                                                        <img class="h-10 w-10 rounded-md object-cover border border-gray-200" src="{{ Storage::url($report->reportable->images[0]) }}" alt="">
                                                    @else
                                                        <div class="h-10 w-10 bg-gray-200 rounded-md flex items-center justify-center text-gray-400">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-xs font-bold text-teal-600 bg-teal-50 px-2 py-0.5 rounded uppercase inline-block mb-1">Barang</div>
                                                    <div class="text-sm font-medium text-gray-900 truncate w-40">{{ $report->reportable ? $report->reportable->title : 'Barang Terhapus' }}</div>
                                                </div>
                                            @else
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover border border-gray-200" src="{{ $report->reportable && $report->reportable->avatar ? Storage::url($report->reportable->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($report->reportable ? $report->reportable->name : 'X').'&color=0D9488&background=CCFBF1' }}" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-xs font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded uppercase inline-block mb-1">Pengguna</div>
                                                    <div class="text-sm font-medium text-gray-900 truncate w-40">{{ $report->reportable ? $report->reportable->name : 'Akun Terhapus' }}</div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $report->reason }}</div>
                                        <div class="text-xs text-gray-500 mt-1 line-clamp-1 w-48">{{ $report->description ?? 'Tidak ada deskripsi tambahan.' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $report->reporter->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $report->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($report->status === 'pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">Menunggu</span>
                                        @elseif($report->status === 'reviewing')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Ditinjau</span>
                                        @elseif($report->status === 'resolved')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.reports.show', $report) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-900 hover:bg-gray-800 text-white text-xs font-bold rounded shadow-sm transition">
                                            @if($report->status === 'pending')
                                                Mulai Tinjau
                                            @else
                                                Lihat Detail
                                            @endif
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <p>Tidak ada laporan yang sesuai dengan filter.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($reports->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $reports->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>

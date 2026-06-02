<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.reports.index') }}" class="text-gray-500 hover:text-gray-700 bg-white p-2 rounded-full shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Laporan #') . $report->id }}
            </h2>
            @if($report->status === 'pending')
                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-800 uppercase tracking-wider">Menunggu</span>
            @elseif($report->status === 'reviewing')
                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800 uppercase tracking-wider">Sedang Ditinjau</span>
            @elseif($report->status === 'resolved')
                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 uppercase tracking-wider">Selesai</span>
            @else
                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-800 uppercase tracking-wider">Ditolak</span>
            @endif
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Kolom Kiri: Info Laporan -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-8">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">Informasi Laporan</h3>
                                <p class="text-xs text-gray-500">Dilaporkan oleh: <strong>{{ $report->reporter->name }}</strong> ({{ $report->reporter->email }})</p>
                            </div>
                            <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1.5 rounded-lg">{{ $report->created_at->format('d M Y, H:i') }}</span>
                        </div>

                        <div class="mb-6 p-5 bg-red-50 border border-red-100 rounded-lg">
                            <p class="text-xs font-bold text-red-600 uppercase tracking-wider mb-1">Alasan Pelaporan</p>
                            <p class="text-lg font-bold text-red-900 mb-4">{{ $report->reason }}</p>
                            
                            <p class="text-xs font-bold text-red-600 uppercase tracking-wider mb-1">Deskripsi Tambahan</p>
                            <p class="text-sm text-red-800 {{ !$report->description ? 'italic opacity-75' : '' }} whitespace-pre-line">{{ $report->description ?? 'Pelapor tidak memberikan deskripsi tambahan.' }}</p>
                        </div>

                        @if($report->status !== 'pending' && $report->status !== 'reviewing')
                            <div class="mt-8 pt-6 border-t border-gray-100">
                                <h4 class="text-sm font-bold text-gray-900 mb-3">Catatan Admin (Keputusan)</h4>
                                <div class="bg-gray-50 p-4 rounded-lg text-sm text-gray-700">
                                    {{ $report->admin_notes ?? 'Tidak ada catatan.' }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Riwayat Laporan Sebelumnya -->
                    @if($historyReports->isNotEmpty())
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6">
                            <h3 class="text-md font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Riwayat Pelaporan Subjek Ini ({{ $historyReports->count() }})
                            </h3>
                            <div class="space-y-3 max-h-60 overflow-y-auto pr-2">
                                @foreach($historyReports as $hist)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-100">
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">{{ $hist->reason }}</p>
                                            <p class="text-xs text-gray-500">{{ $hist->reporter->name }} • {{ $hist->created_at->format('d M Y') }}</p>
                                        </div>
                                        <div>
                                            @if($hist->status === 'resolved')
                                                <span class="text-[10px] font-bold px-2 py-1 bg-green-100 text-green-800 rounded uppercase">Selesai</span>
                                            @elseif($hist->status === 'rejected')
                                                <span class="text-[10px] font-bold px-2 py-1 bg-gray-200 text-gray-800 rounded uppercase">Ditolak</span>
                                            @else
                                                <span class="text-[10px] font-bold px-2 py-1 bg-amber-100 text-amber-800 rounded uppercase">{{ $hist->status }}</span>
                                            @endif
                                            <a href="{{ route('admin.reports.show', $hist) }}" class="text-xs font-bold text-teal-600 hover:underline ml-2">Lihat</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Kolom Kanan: Info Subjek & Form Keputusan -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Info Subjek -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Subjek Dilaporkan</h3>
                            @if($report->reportable_type === 'App\Models\Item')
                                <span class="text-xs font-bold text-teal-600 bg-teal-50 px-2 py-1 rounded">BARANG</span>
                            @else
                                <span class="text-xs font-bold text-purple-600 bg-purple-50 px-2 py-1 rounded">PENGGUNA</span>
                            @endif
                        </div>

                        @if(!$report->reportable)
                            <div class="bg-red-50 p-4 rounded-lg text-center">
                                <p class="text-sm text-red-600 font-medium">Subjek sudah dihapus dari sistem.</p>
                            </div>
                        @else
                            <div class="text-center mb-6">
                                @if($report->reportable_type === 'App\Models\Item')
                                    @if($report->reportable->images && count($report->reportable->images) > 0)
                                        <img src="{{ Storage::url($report->reportable->images[0]) }}" class="w-24 h-24 rounded-lg object-cover mx-auto mb-3 shadow-sm">
                                    @else
                                        <div class="w-24 h-24 bg-gray-100 rounded-lg mx-auto mb-3 flex items-center justify-center"><svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                                    @endif
                                    <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $report->reportable->title }}</h4>
                                    <p class="text-xs text-gray-500 mb-3">Milik: {{ $report->reportable->user->name }}</p>
                                    <div class="mb-2">
                                        @if($report->reportable->status === 'active')
                                            <span class="text-xs font-bold bg-green-100 text-green-800 px-2 py-1 rounded">Aktif</span>
                                        @elseif($report->reportable->status === 'cancelled')
                                            <span class="text-xs font-bold bg-red-100 text-red-800 px-2 py-1 rounded">Di-suspend/Batal</span>
                                        @else
                                            <span class="text-xs font-bold bg-gray-100 text-gray-800 px-2 py-1 rounded">{{ $report->reportable->status }}</span>
                                        @endif
                                    </div>
                                @else
                                    <img src="{{ $report->reportable->avatar ? Storage::url($report->reportable->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($report->reportable->name).'&color=0D9488&background=CCFBF1' }}" class="w-24 h-24 rounded-full object-cover mx-auto mb-3 shadow-sm ring-4 ring-gray-50">
                                    <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $report->reportable->name }}</h4>
                                    <p class="text-xs text-gray-500 mb-3">{{ $report->reportable->email }}</p>
                                    <div class="mb-2">
                                        @if($report->reportable->is_banned)
                                            <span class="text-xs font-bold bg-red-100 text-red-800 px-2 py-1 rounded">BANNED</span>
                                        @elseif($report->reportable->is_verified)
                                            <span class="text-xs font-bold bg-green-100 text-green-800 px-2 py-1 rounded">Terverifikasi</span>
                                        @else
                                            <span class="text-xs font-bold bg-gray-100 text-gray-800 px-2 py-1 rounded">Belum Verifikasi</span>
                                        @endif
                                    </div>
                                @endif
                                
                                <a href="{{ $report->subject_url }}" target="_blank" class="mt-2 inline-flex items-center gap-1 text-sm font-bold text-teal-600 hover:underline">
                                    Lihat Halaman Publik <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Form Tindakan (Hanya jika belum resolved/rejected) -->
                    @if(in_array($report->status, ['pending', 'reviewing']))
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-900 p-6 relative">
                            <div class="absolute top-0 left-0 w-full h-1 bg-gray-900"></div>
                            <h3 class="text-md font-bold text-gray-900 mb-4">Ambil Tindakan</h3>
                            
                            <form action="{{ route('admin.reports.update', $report) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin dengan keputusan ini? Tindakan yang diambil (seperti Suspend/Ban) akan langsung dieksekusi.')">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-4">
                                    <label for="status" class="block text-sm font-bold text-gray-700 mb-1">Keputusan Laporan</label>
                                    <select name="status" id="status" class="w-full rounded-md border-gray-300 focus:border-teal-500 focus:ring-teal-500 text-sm" required>
                                        <option value="resolved">Selesai (Terbukti Melanggar/Ditindak)</option>
                                        <option value="rejected">Tolak (Laporan Palsu/Tidak Melanggar)</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label for="action" class="block text-sm font-bold text-gray-700 mb-1">Tindakan Terhadap Subjek</label>
                                    <select name="action" id="action" class="w-full rounded-md border-gray-300 focus:border-teal-500 focus:ring-teal-500 text-sm" required>
                                        <option value="no_action">Tidak Ada Tindakan (Abaikan)</option>
                                        <option value="warn_user">Kirim Peringatan ke Pemilik/Pengguna</option>
                                        @if($report->reportable_type === 'App\Models\Item')
                                            <option value="suspend_item">Turunkan Barang (Suspend)</option>
                                        @endif
                                        <option value="ban_user">Blokir Pengguna (Ban)</option>
                                    </select>
                                    <p class="text-[10px] text-gray-500 mt-1">Tindakan hanya dieksekusi jika Keputusan Laporan = Selesai.</p>
                                </div>

                                <div class="mb-6">
                                    <label for="admin_notes" class="block text-sm font-bold text-gray-700 mb-1">Catatan Internal Admin</label>
                                    <textarea name="admin_notes" id="admin_notes" rows="3" class="w-full rounded-md border-gray-300 focus:border-teal-500 focus:ring-teal-500 text-sm" placeholder="Catatan untuk riwayat moderasi..."></textarea>
                                </div>

                                <button type="submit" class="w-full py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-lg shadow-sm transition">
                                    Simpan Keputusan
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-admin-layout>

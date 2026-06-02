<x-admin-layout>
    <x-slot name="header">Manajemen Pengguna</x-slot>

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-5 flex flex-wrap gap-3 items-center">
        <form method="GET" class="flex flex-wrap gap-3 items-center flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                   class="flex-1 min-w-48 rounded-lg border-gray-200 text-sm focus:border-teal-500 focus:ring-teal-500 shadow-sm">

            <select name="filter" onchange="this.form.submit()" class="rounded-lg border-gray-200 text-sm focus:border-teal-500 focus:ring-teal-500 shadow-sm">
                <option value="">Semua Pengguna</option>
                <option value="admin"    {{ request('filter')==='admin'    ? 'selected' : '' }}>Admin</option>
                <option value="user"     {{ request('filter')==='user'     ? 'selected' : '' }}>User Biasa</option>
                <option value="verified" {{ request('filter')==='verified' ? 'selected' : '' }}>Terverifikasi</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-teal-600 text-white text-sm font-semibold rounded-lg hover:bg-teal-700 transition">Cari</button>
            @if(request('search') || request('filter'))
                <a href="{{ route('admin.users.index') }}" class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
            @endif
        </form>
        <p class="text-sm text-gray-500 font-medium">{{ $users->total() }} pengguna</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pengguna</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kontak</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Donasi</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Bergabung</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-3">
                            <img class="h-9 w-9 rounded-full object-cover border border-gray-200 flex-shrink-0"
                                 src="{{ $user->avatar ? Storage::url($user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=0D9488&background=CCFBF1' }}"
                                 alt="{{ $user->name }}">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                                <div class="flex items-center gap-1 mt-0.5">
                                    @if($user->role === 'admin')
                                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-purple-100 text-purple-700 uppercase">Admin</span>
                                    @endif
                                    @if($user->is_verified)
                                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-teal-100 text-teal-700 uppercase">Verified</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-3">
                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                        <p class="text-xs text-gray-400">{{ $user->phone ?? '—' }}</p>
                    </td>
                    <td class="px-6 py-3">
                        <p class="text-sm font-semibold text-gray-900">{{ $user->items_count }} <span class="font-normal text-gray-500 text-xs">didonasikan</span></p>
                        <p class="text-xs text-gray-400">{{ $user->claims_count }} klaim</p>
                    </td>
                    <td class="px-6 py-3">
                        @if($user->email_verified_at)
                            <span class="text-xs font-semibold text-green-700 bg-green-50 px-2 py-0.5 rounded-full">Aktif</span>
                        @else
                            <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">Email Belum Verifikasi</span>
                        @endif
                    </td>
                    <td class="px-6 py-3 text-xs text-gray-400">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-3">
                        <div class="flex items-center justify-end gap-2" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" class="p-2 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path></svg>
                            </button>
                            <div x-show="open" x-transition class="absolute right-4 mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-lg z-10 overflow-hidden" style="display:none;">
                                <a href="{{ route('profile.show', $user->id) }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                    Lihat Profil
                                </a>
                                <form action="{{ route('admin.users.toggleVerified', $user) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                        {{ $user->is_verified ? 'Cabut Verifikasi' : 'Verifikasi Akun' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.toggleRole', $user) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="w-full text-left px-4 py-2.5 text-sm {{ $user->role === 'admin' ? 'text-red-600' : 'text-purple-600' }} hover:bg-gray-50 transition">
                                        {{ $user->role === 'admin' ? 'Turunkan ke User' : 'Jadikan Admin' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">Tidak ada pengguna ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>

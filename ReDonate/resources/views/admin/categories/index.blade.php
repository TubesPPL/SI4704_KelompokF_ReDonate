<x-admin-layout>
    <x-slot name="header">Manajemen Kategori</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah/Edit -->
        <div x-data="categoryForm()" class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 sticky top-4">
                <h2 class="text-base font-bold text-gray-900 mb-5" x-text="editMode ? 'Edit Kategori' : 'Tambah Kategori Baru'"></h2>

                <form :action="editMode ? editUrl : '{{ route('admin.categories.store') }}'" method="POST">
                    @csrf
                    <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>

                    <!-- Nama -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                        <input type="text" name="name" x-model="name" required
                               class="w-full rounded-lg border-gray-200 text-sm focus:border-teal-500 focus:ring-teal-500 shadow-sm" placeholder="Pakaian, Elektronik, dll.">
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <!-- Ikon (SVG path dari Heroicons) -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ikon <span class="text-gray-400 font-normal">(Heroicons SVG path)</span></label>
                        <div class="grid grid-cols-5 gap-2 mb-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                            @php
                                $icons = [
                                    'shirt'   => 'M3 3h18v2H3zM3 7h18v2H3zM3 11h12v2H3zm0 4h18v2H3z',
                                    'box'     => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                                    'device'  => 'M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18',
                                    'book'    => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                                    'home'    => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                                    'bike'    => 'M12 4v16m8-8H4',
                                    'heart'   => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                                    'chair'   => 'M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z',
                                    'star'    => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
                                    'more'    => 'M4 6h16M4 10h16M4 14h16M4 18h16',
                                ];
                            @endphp
                            @foreach($icons as $key => $path)
                                <button type="button" @click="icon = '{{ $path }}'; selectedIconKey = '{{ $key }}'"
                                        :class="selectedIconKey === '{{ $key }}' ? 'ring-2 ring-teal-500 bg-teal-50' : 'hover:bg-gray-200'"
                                        class="p-2 rounded-lg transition flex items-center justify-center" title="{{ $key }}">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}"></path>
                                    </svg>
                                </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="icon" x-model="icon">
                        <p class="text-xs text-gray-400">Klik ikon untuk memilih, atau kosongkan jika tidak butuh ikon.</p>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" x-model="description" rows="3"
                                  class="w-full rounded-lg border-gray-200 text-sm focus:border-teal-500 focus:ring-teal-500 shadow-sm"
                                  placeholder="Opsional"></textarea>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-teal-600 text-white font-bold py-2.5 px-4 rounded-lg hover:bg-teal-700 transition text-sm">
                            <span x-text="editMode ? 'Simpan Perubahan' : 'Tambah Kategori'"></span>
                        </button>
                        <button type="button" x-show="editMode" @click="resetForm()" class="px-3 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- List Kategori -->
        <div class="lg:col-span-2" x-data="categoryForm()">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-base font-bold text-gray-900">Semua Kategori</h2>
                    <p class="text-sm text-gray-500">{{ $categories->total() }} kategori</p>
                </div>

                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Slug</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Barang</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($categories as $cat)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    @if($cat->icon)
                                        <div class="h-8 w-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cat->icon }}"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $cat->name }}</p>
                                        @if($cat->description)
                                            <p class="text-xs text-gray-400 line-clamp-1">{{ $cat->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-xs text-gray-400 font-mono">{{ $cat->slug }}</td>
                            <td class="px-6 py-3">
                                <span class="text-sm font-bold text-gray-900">{{ $cat->items_count }}</span>
                            </td>
                            <td class="px-6 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button
                                        @click="window.dispatchEvent(new CustomEvent('edit-category', { detail: { id: {{ $cat->id }}, name: '{{ addslashes($cat->name) }}', icon: '{{ addslashes($cat->icon ?? '') }}', description: '{{ addslashes($cat->description ?? '') }}', editUrl: '{{ route('admin.categories.update', $cat) }}' } }))"
                                        class="p-1.5 rounded-lg hover:bg-amber-50 text-gray-400 hover:text-amber-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ $cat->name }}?')">
                                        @csrf @method('DELETE')
                                        <button class="p-1.5 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-600 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-12 text-center text-gray-400">Belum ada kategori.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                @if($categories->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">{{ $categories->links() }}</div>
                @endif
            </div>
        </div>
    </div>

    <script>
    function categoryForm() {
        return {
            editMode: false,
            editUrl: '',
            name: '',
            icon: '',
            description: '',
            selectedIconKey: '',

            init() {
                window.addEventListener('edit-category', (e) => {
                    this.editMode = true;
                    this.editUrl = e.detail.editUrl;
                    this.name = e.detail.name;
                    this.icon = e.detail.icon;
                    this.description = e.detail.description;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            },

            resetForm() {
                this.editMode = false;
                this.editUrl = '';
                this.name = '';
                this.icon = '';
                this.description = '';
                this.selectedIconKey = '';
            }
        }
    }
    </script>
</x-admin-layout>

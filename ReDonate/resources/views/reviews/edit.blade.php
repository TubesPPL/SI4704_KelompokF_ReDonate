<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Ulasan Donatur') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-8 border-b border-gray-100 bg-white">
                    <h3 class="text-2xl font-extrabold text-gray-900 mb-2 text-center">Edit Ulasan Anda 📝</h3>
                    <p class="text-gray-500 text-center mb-8">Sesuaikan penilaian atau pesan Anda untuk donatur ini.</p>
                    
                    <div class="flex items-center justify-center gap-4 p-4 bg-teal-50 rounded-xl border border-teal-100 mb-8">
                        <img class="h-16 w-16 rounded-full object-cover shadow-sm border-2 border-white" src="{{ $review->claim->item->user->avatar ? Storage::url($review->claim->item->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($review->claim->item->user->name).'&color=0D9488&background=CCFBF1' }}" alt="{{ $review->claim->item->user->name }}">
                        <div>
                            <p class="text-xs text-teal-600 font-semibold uppercase tracking-wider">Mendonasikan:</p>
                            <p class="font-bold text-gray-900 text-lg">{{ $review->claim->item->title }}</p>
                            <p class="text-sm text-gray-600 font-medium">Oleh: {{ $review->claim->item->user->name }}</p>
                        </div>
                    </div>

                    <form action="{{ route('reviews.update', $review->id) }}" method="POST" x-data="reviewForm({{ $review->rating }})">
                        @csrf
                        @method('PUT')
                        
                        <!-- Star Rating Interactive -->
                        <div class="mb-8 text-center">
                            <label class="block text-sm font-semibold text-gray-900 mb-4">Seberapa puas Anda?</label>
                            <input type="hidden" name="rating" x-model="rating">
                            
                            <div class="flex justify-center gap-2">
                                <template x-for="star in 5">
                                    <button 
                                        type="button" 
                                        @click="rating = star" 
                                        @mouseover="hoverRating = star" 
                                        @mouseleave="hoverRating = 0"
                                        class="focus:outline-none transition-transform hover:scale-110"
                                    >
                                        <svg class="w-12 h-12 transition-colors" :class="(hoverRating >= star || (!hoverRating && rating >= star)) ? 'text-amber-400' : 'text-gray-200'" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    </button>
                                </template>
                            </div>
                            <div class="mt-3 text-sm font-bold h-5" :class="ratingColor()" x-text="ratingLabel()"></div>
                            <x-input-error class="mt-2 flex justify-center" :messages="$errors->get('rating')" />
                        </div>

                        <div class="mb-8">
                            <label for="comment" class="block text-sm font-semibold text-gray-900 mb-2">Pesan Ulasan (Opsional)</label>
                            <textarea id="comment" name="comment" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-teal-500 focus:ring-teal-500" placeholder="Ucapkan terima kasih atau berikan komentar atas pengalaman Anda...">{{ old('comment', $review->comment) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('comment')" />
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('profile.show', $review->reviewee_id) }}" class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Batal</a>
                            <button type="submit" class="px-8 py-3 bg-teal-600 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition disabled:opacity-50 disabled:cursor-not-allowed" :disabled="rating === 0">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function reviewForm(initialRating = 0) {
            return {
                rating: initialRating,
                hoverRating: 0,
                
                ratingLabel() {
                    const current = this.hoverRating || this.rating;
                    switch(current) {
                        case 1: return 'Sangat Buruk 😞';
                        case 2: return 'Kurang Baik 😕';
                        case 3: return 'Cukup Baik 😐';
                        case 4: return 'Sangat Baik 🙂';
                        case 5: return 'Luar Bisa! 😍';
                        default: return '';
                    }
                },
                
                ratingColor() {
                    const current = this.hoverRating || this.rating;
                    if(current >= 4) return 'text-green-600';
                    if(current === 3) return 'text-amber-600';
                    if(current > 0) return 'text-red-600';
                    return 'text-gray-500';
                }
            }
        }
    </script>
</x-app-layout>

@props(['event'])

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row hover:shadow-md transition group">
    <!-- Banner -->
    <div class="w-full md:w-2/5 aspect-w-4 aspect-h-3 md:aspect-none relative bg-gray-200 overflow-hidden">
        @if($event->banner)
            <img src="{{ Storage::url($event->banner) }}" alt="{{ $event->title }}" class="object-cover w-full h-full md:absolute md:inset-0 group-hover:scale-105 transition duration-500">
        @else
            <div class="w-full h-full md:absolute md:inset-0 flex items-center justify-center text-gray-400">
                <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        @endif
        
        <!-- Badge Status -->
        <div class="absolute top-3 left-3">
            @if($event->status === 'active')
                <span class="px-2.5 py-1 bg-teal-500 text-white text-xs font-bold rounded-full shadow-sm">Sedang Berlangsung</span>
            @elseif($event->status === 'upcoming')
                <span class="px-2.5 py-1 bg-amber-500 text-white text-xs font-bold rounded-full shadow-sm">Segera Hadir</span>
            @else
                <span class="px-2.5 py-1 bg-gray-500 text-white text-xs font-bold rounded-full shadow-sm">Telah Selesai</span>
            @endif
        </div>
    </div>
    
    <!-- Content -->
    <div class="w-full md:w-3/5 p-5 flex flex-col">
        <h3 class="font-bold text-xl text-gray-900 line-clamp-2 mb-2 group-hover:text-teal-600 transition">
            <a href="{{ route('events.show', $event->slug) }}">{{ $event->title }}</a>
        </h3>
        
        <p class="text-sm text-gray-500 flex items-center gap-1.5 mb-4">
            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            {{ $event->start_date->format('d M') }} - {{ $event->end_date->format('d M Y') }}
        </p>

        <!-- Progress Bar -->
        <div class="mt-auto mb-5">
            <div class="flex justify-between text-xs font-bold mb-1">
                <span class="text-teal-600">{{ $event->items_count }} Terkumpul</span>
                <span class="text-gray-500">Target: {{ $event->target_items }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                @php
                    $percentage = $event->target_items > 0 ? min(100, round(($event->items_count / $event->target_items) * 100)) : 0;
                @endphp
                <div class="bg-teal-500 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
            </div>
        </div>

        <a href="{{ route('events.show', $event->slug) }}" class="w-full text-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50 hover:border-teal-300 transition">
            Lihat Event
        </a>
    </div>
</div>

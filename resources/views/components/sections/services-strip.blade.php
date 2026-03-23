@props(['data'])
<section class="py-12 bg-gray-50 border-y border-gray-100">
    <div class="max-w-6xl mx-auto px-6">
        @if($data['title'] ?? false)
            <h2 class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-8 text-center">{{ $data['title'] }}</h2>
        @endif

        <div class="flex flex-wrap justify-center gap-x-12 gap-y-8">
            @foreach($data['items'] ?? [] as $item)
                <div class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-white shadow-sm rounded-full flex items-center justify-center text-teal-600 group-hover:bg-teal-600 group-hover:text-white transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                    </div>
                    <span class="font-bold text-gray-900 group-hover:text-teal-600 transition-colors">{{ $item['title'] ?? '' }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

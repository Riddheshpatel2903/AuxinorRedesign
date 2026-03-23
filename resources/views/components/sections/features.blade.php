@props(['data'])
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        @if($data['title'] ?? false)
            <h2 class="text-3xl font-bold mb-6" data-element-key="title">{{ $data['title'] }}</h2>
        @endif
        
        @if($data['description'] ?? false)
            <p class="text-gray-600 mb-10 max-w-3xl" data-element-key="description">{{ $data['description'] }}</p>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($data['items'] ?? [] as $i => $item)
                <div class="p-8 bg-gray-50 rounded-2xl border border-gray-100 hover:border-teal-500/30 hover:shadow-xl transition-all duration-300 group">
                    <div class="w-12 h-12 bg-teal-500/10 rounded-xl flex items-center justify-center mb-6 text-teal-600 group-hover:bg-teal-500 group-hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check"><path d="M20 6 9 17l-5-5"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-gray-900" data-element-key="items.{{ $i }}.title">{{ $item['title'] ?? '' }}</h3>
                    <p class="text-gray-600 leading-relaxed" data-element-key="items.{{ $i }}.description">{{ $item['description'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

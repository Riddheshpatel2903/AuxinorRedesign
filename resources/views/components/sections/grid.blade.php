@props(['data'])
@php
    $cols = $data['columns'] ?? 3;
    $gridCols = [
        1 => 'grid-cols-1',
        2 => 'grid-cols-1 md:grid-cols-2',
        3 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
        4 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
    ][$cols] ?? 'grid-cols-1 md:grid-cols-3';
@endphp

<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        @if($data['label'] ?? false)
            <span class="text-center block text-teal-600 font-bold uppercase tracking-widest text-sm mb-4" data-element-key="label">{{ $data['label'] }}</span>
        @endif
        
        @if($data['title'] ?? false)
            <h2 class="text-3xl md:text-4xl font-bold mb-16 text-center text-gray-900" data-element-key="title">{{ $data['title'] }}</h2>
        @endif

        <div class="grid {{ $gridCols }} gap-10">
            @foreach($data['items'] ?? [] as $i => $item)
                <div class="group h-full flex flex-col">
                    @if($item['image'] ?? false)
                        <div class="overflow-hidden rounded-2xl mb-6 aspect-[4/3]">
                            <img src="{{ asset($item['image']) }}" alt="{{ $item['title'] ?? '' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" data-element-key="items.{{ $i }}.image">
                        </div>
                    @endif
                    <h3 class="text-xl font-bold mb-3 text-gray-900 group-hover:text-teal-600 transition-colors" data-element-key="items.{{ $i }}.title">{{ $item['title'] ?? '' }}</h3>
                    @if($item['description'] ?? false)
                        <p class="text-gray-600 leading-relaxed" data-element-key="items.{{ $i }}.description">{{ $item['description'] }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
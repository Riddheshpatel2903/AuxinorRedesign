@props(['data'])
@php
    $imagePosition = $data['image_position'] ?? 'right';
    $image = $data['image'] ?? null;
    $title = $data['title'] ?? '';
    $label = $data['label'] ?? '';
    $description = $data['description'] ?? '';
@endphp
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        <div class="flex flex-col {{ $imagePosition === 'left' ? 'md:flex-row-reverse' : 'md:flex-row' }} gap-16 items-center">
            <div class="flex-1">
                @if($label)
                    <span class="text-xs uppercase tracking-wider text-teal-600 font-bold mb-4 block" data-element-key="label">{{ $label }}</span>
                @endif
                <h2 class="text-3xl md:text-4xl font-bold mb-6 text-gray-900 leading-tight" data-element-key="title">{{ $title }}</h2>
                <div class="text-gray-600 text-lg leading-relaxed space-y-4" data-element-key="description">
                    {!! $description !!}
                </div>
            </div>
            
            @if($image)
                <div class="flex-1 w-full">
                    <div class="relative group">
                        <div class="absolute -inset-4 bg-teal-500/10 rounded-2xl scale-95 group-hover:scale-100 transition-transform duration-500"></div>
                        <img src="{{ asset($image) }}" alt="{{ $title }}" class="relative rounded-xl shadow-2xl w-full object-cover aspect-[4/3]" data-element-key="image">
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
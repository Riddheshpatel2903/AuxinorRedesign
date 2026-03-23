@props(['data'])
<section class="py-20 bg-teal-600 relative overflow-hidden">
    <div class="absolute inset-0 bg-grid-white/[0.1] bg-[size:20px_20px]"></div>
    <div class="absolute inset-0 bg-gradient-to-br from-teal-600 to-teal-800"></div>
    
    <div class="relative max-w-4xl mx-auto px-6 text-center text-white">
        <h2 class="text-3xl md:text-5xl font-bold mb-6" data-element-key="title">{{ $data['title'] ?? 'Ready to get started?' }}</h2>
        <p class="text-xl text-teal-50 mb-10 opacity-90" data-element-key="subtitle">{{ $data['subtitle'] ?? '' }}</p>
        
        @if($data['button_text'] ?? false)
            <div class="flex justify-center">
                <a href="{{ $data['button_link'] ?? '#' }}" class="bg-white text-teal-700 px-10 py-4 rounded-xl font-bold text-lg hover:bg-teal-50 shadow-xl transition-all hover:scale-105" data-element-key="button_text">
                    {{ $data['button_text'] }}
                </a>
            </div>
        @endif
    </div>
</section>

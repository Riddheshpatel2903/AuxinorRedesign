@props(['data'])
@php
    $label = $data['label'] ?? '';
    $heading = $data['heading'] ?? '';
    $subheading = $data['subheading'] ?? '';
    $buttonText = $data['button_text'] ?? '';
    $buttonLink = $data['button_link'] ?? '#';
    $backgroundImage = $data['background_image'] ?? '';
    $styles = $data['styles'] ?? [];
    
    // Simple inline style generator
    $styleString = '';
    if ($backgroundImage) {
        $imgUrl = str_starts_with($backgroundImage, 'http') ? $backgroundImage : asset($backgroundImage);
        $styleString .= "background-image: url('$imgUrl'); background-size: cover; background-position: center;";
    }
    foreach ($styles as $k => $v) {
        $cssKey = strtolower(preg_replace('/([A-Z])/', '-$1', $k));
        $styleString .= "{$cssKey}:{$v};";
    }
@endphp

<section class="relative min-h-[60vh] flex items-center pt-32 pb-20 overflow-hidden" style="background-color: #0f172a; {{ $styleString }}">
    <!-- Background Accents -->
    <div class="absolute inset-0 z-0">
        <div class="absolute top-0 right-0 w-[50%] h-[100%] bg-teal-500/10 blur-[120px] rounded-full translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 left-0 w-[40%] h-[80%] bg-blue-500/10 blur-[120px] rounded-full -translate-x-1/2 translate-y-1/2"></div>
    </div>
    @if($data['background_overlay'] ?? false)
        <div class="absolute inset-0 bg-black/{{ (int)($data['background_overlay'] * 100) }}"></div>
    @endif

    <div class="relative max-w-6xl mx-auto px-6 py-20 text-white w-full">
        @if($label)
            <span class="text-xs uppercase tracking-wider text-teal-300" data-element-key="label">{{ $label }}</span>
        @endif
        
        @if($heading)
            <h1 class="mt-4 text-4xl md:text-6xl font-bold" data-element-key="heading">
                {{ $heading }}
            </h1>
        @endif
        
        @if($subheading)
            <p class="mt-6 text-lg text-white/80 max-w-2xl" data-element-key="subheading">{!! $subheading !!}</p>
        @endif
 
        @if($buttonText)
            <div class="mt-10 flex flex-wrap gap-4">
                <a href="{{ $buttonLink }}" class="btn-primary" data-element-key="button_text">{!! $buttonText !!}</a>
            </div>
        @endif
    </div>
</section>

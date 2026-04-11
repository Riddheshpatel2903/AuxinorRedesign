@extends('layouts.app')

@section('title', 'Industries We Serve | ' . ($globalSettings['company_name'] ?? 'Auxinor Chemicals'))

@section('content')
<!-- Hero Banner -->
<section data-section-id="99" data-section-key="ind_hero" data-section-label="Industries Hero" class="relative text-white py-24 px-4 md:px-8 border-b-4 border-teal @auth cms-editable @endauth" data-cms-label="Edit Industries Hero">
    <!-- Background Overlay Layer -->
    <div class="ed-bg-overlay absolute inset-0 bg-gradient-to-r from-ink to-ink2 -z-10"></div>
    
    <div class="max-w-[1400px] mx-auto text-center relative z-10">
 <div data-element-id="ind_hero_badge" class="font-sans font-bold text-[10px] uppercase tracking-[2px] text-teal-2 mb-4">Our Reach</div>
        <h1 data-element-id="ind_hero_title" class="font-display font-extrabold text-[40px] md:text-[72px] leading-[1.1] mb-8">Industries We <em class="italic text-teal-2 font-normal">Serve</em></h1>
        <div class="font-sans font-medium text-[11px] uppercase tracking-wider text-white/50 space-x-2">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
            <span>/</span>
            <span class="text-teal-2">Industries</span>
        </div>
    </div>
</section>

<!-- Industry Rows -->
<section data-section-id="130" data-section-key="ind_grid" data-section-label="Industries Grid" class="bg-white @auth cms-editable @endauth" data-cms-label="Edit Industries Grid">
    @foreach($industries as $idx => $industry)
    <div class="grid grid-cols-1 lg:grid-cols-2 {{ $idx % 2 !== 0 ? 'bg-bg border-y border-line' : '' }} min-h-[500px]">
        
        <!-- Image Block -->
        <div class="relative overflow-hidden sr-scale group {{ $idx % 2 !== 0 ? 'lg:order-last' : '' }}">
            <div class="absolute inset-0 bg-ink/20 mix-blend-multiply z-10 group-hover:bg-transparent transition-colors duration-500"></div>
            <img src="{{ asset($industry->image_path) }}" alt="{{ $industry->name }}" class="absolute inset-0 w-full h-full object-cover filter grayscale-[10%] transform group-hover:scale-105 transition-transform duration-700" onerror="this.src='{{ asset('assets/images/hero-1.jpg') }}'">
            <div class="absolute bottom-6 {{ $idx % 2 !== 0 ? 'right-6 text-right' : 'left-6' }} z-20">
 <span class="font-sans font-bold text-[48px] text-white/30 leading-none">0{{ $idx + 1 }}</span>
            </div>
        </div>
        
        <!-- Content Block -->
        <div class="px-8 lg:px-20 py-16 flex flex-col justify-center sr-up">
 <h2 class="font-display font-extrabold text-[32px] md:text-[40px] leading-tight mb-6 text-ink">{{ $industry->name }}</h2>
            <p class="font-sans text-[15px] text-muted leading-relaxed mb-8 max-w-lg">{{ $industry->description }}</p>
            
 <div class="mb-10">
                <span class="block font-sans font-bold text-[10px] uppercase tracking-[2px] text-teal mb-2">Key Products Supplied</span>
                <p class="font-sans font-bold text-[14px] text-ink2">{{ $industry->products }}</p>
            </div>
            
            <div>
                <a href="{{ route('contact') }}?industry={{ urlencode($industry->name) }}#enquiry-form" class="inline-flex items-center text-teal hover:text-ink transition-colors font-display font-bold text-xs uppercase tracking-widest group">
                    Discuss Requirements 
                    <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>
        
    </div>
    @endforeach
</section>

<!-- CTA Bar -->
<section data-section-id="120" data-section-key="services_strip" data-section-label="Industries CTA" class="bg-teal text-white py-16 text-center border-t-4 border-ink @auth cms-editable @endauth" data-cms-label="Edit CTA Bar">
    <div class="max-w-2xl mx-auto px-4">
 <h2 data-element-id="cta_title" class="font-display font-extrabold text-[32px] md:text-[40px] mb-4">Don't see your industry listed?</h2>
        <p data-element-id="cta_desc" class="font-sans italic text-white/80 mb-8">We supply to various niche manufacturing sectors. Contact us for custom chemical procurement strategies.</p>
        <a data-element-id="cta_btn" href="{{ route('contact') }}" class="btn-primary bg-ink text-white hover:bg-white hover:text-ink px-8 py-4 font-display font-bold text-xs uppercase tracking-widest inline-block transition-colors">Contact Our Team</a>
    </div>
</section>
@endsection

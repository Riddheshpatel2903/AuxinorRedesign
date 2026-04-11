@extends('layouts.app')

@section('title', 'Industries We Serve | ' . ($globalSettings['company_name'] ?? 'Auxinor Chemicals'))

@section('content')
<!-- Hero Banner -->
<section data-section-id="{{ $sections->where('section_key', 'ind_hero')->first()->id ?? '99' }}" data-section-key="ind_hero" data-section-label="Industries Hero" class="bg-gradient-to-r from-ink to-ink2 text-white py-24 px-4 md:px-8 border-b-4 border-teal @auth cms-editable @endauth" data-cms-label="Edit Industries Hero">
    <div class="max-w-[1400px] mx-auto text-center">
        <div class="font-mono text-[10px] uppercase tracking-widest text-teal-2 mb-4">Our Reach</div>
        <h1 class="font-display font-extrabold text-[40px] md:text-[56px] leading-tight mb-4">Industries We <em class="font-serif italic font-normal text-teal-2">Serve</em></h1>
        <div class="font-mono text-[11px] text-white/50 space-x-2">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
            <span>/</span>
            <span class="text-teal-2">Industries</span>
        </div>
    </div>
</section>

@php
    $industries = [
        [
            'name' => 'Speciality Chemicals',
            'desc' => 'Supplying high-purity monomers, acrylates, and solvents essential for the manufacturing of specialty chemical grades, coatings, and adhesives.',
            'products' => 'Acrylates, Toluene, Benzene, Acetone',
            'image' => asset('assets/images/industry-4.jpg'),
        ],
        [
            'name' => 'Petrochemicals',
            'desc' => 'As core building blocks, we distribute bulk shipments of foundational petrochemicals serving robust industrial applications.',
            'products' => 'Mixed Xylene, Base Monomers, Glycols',
            'image' => asset('assets/images/industry-5.jpg'),
        ],
        [
            'name' => 'Pharmaceuticals',
            'desc' => 'Providing IPA, extreme purity solvents, and intermediates used widely across Active Pharmaceutical Ingredient (API) production lines.',
            'products' => 'Isopropyl Alcohol (IPA), Acetone, Toluene',
            'image' => asset('assets/images/industry-1.jpg'),
        ],
        [
            'name' => 'Dyes & Intermediates',
            'desc' => 'Crucial solvents and compounds utilized heavily in the manufacturing of synthetic dyes, pigments, and colorants.',
            'products' => 'Cyclohexane, Ethyl Acetate',
            'image' => asset('assets/images/industry-2.jpg'),
        ],
        [
            'name' => 'Agrochemicals',
            'desc' => 'Specialized intermediate compounds acting as carriers and active solubilizers in pesticide and herbicide formulations.',
            'products' => 'Toluene, Selected Glycols',
            'image' => asset('assets/images/industry-3.jpg'),
        ],
        [
            'name' => 'Food Industry',
            'desc' => 'Safe, highly regulated food-grade packaging chemicals and compliant additives used throughout food preservation.',
            'products' => 'Propylene Glycol, Selected Oxo Alcohols',
            'image' => asset('assets/images/industry-6.jpg'),
        ]
    ];
@endphp

<!-- Industry Rows -->
<section data-section-id="{{ $sections->where('section_key', 'ind_grid')->first()->id ?? '100' }}" data-section-key="ind_grid" data-section-label="Industries Grid" class="bg-white @auth cms-editable @endauth" data-cms-label="Edit Industries Grid">
    @foreach($industries as $idx => $industry)
    <div class="grid grid-cols-1 lg:grid-cols-2 {{ $idx % 2 !== 0 ? 'bg-bg border-y border-line' : '' }} min-h-[500px]">
        
        <!-- Image Block -->
        <div class="relative overflow-hidden sr-scale group {{ $idx % 2 !== 0 ? 'lg:order-last' : '' }}">
            <div class="absolute inset-0 bg-ink/20 mix-blend-multiply z-10 group-hover:bg-transparent transition-colors duration-500"></div>
            <img src="{{ $industry['image'] }}" alt="{{ $industry['name'] }}" class="absolute inset-0 w-full h-full object-cover filter grayscale-[10%] transform group-hover:scale-105 transition-transform duration-700" onerror="this.style.display='none'">
            <div class="absolute bottom-6 {{ $idx % 2 !== 0 ? 'right-6 text-right' : 'left-6' }} z-20">
                <span class="font-mono text-[48px] text-white/30 leading-none font-bold">0{{ $idx + 1 }}</span>
            </div>
        </div>
        
        <!-- Content Block -->
        <div class="px-8 lg:px-20 py-16 flex flex-col justify-center sr-up">
            <h2 class="font-display font-extrabold text-[32px] md:text-[40px] leading-tight mb-6">{{ $industry['name'] }}</h2>
            <p class="font-serif text-[15px] text-muted leading-relaxed mb-8 max-w-lg">{{ $industry['desc'] }}</p>
            
            <div class="mb-10">
                <span class="block font-mono text-[10px] uppercase tracking-wider text-teal mb-2">Key Products Supplied</span>
                <p class="font-display font-bold text-[14px] text-ink2">{{ $industry['products'] }}</p>
            </div>
            
            <div>
                <a href="{{ route('contact') }}?industry={{ urlencode($industry['name']) }}#enquiry-form" class="inline-flex items-center text-teal hover:text-ink transition-colors font-display font-bold text-xs uppercase tracking-widest group">
                    Discuss Requirements 
                    <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>
        
    </div>
    @endforeach
</section>

<!-- CTA Bar -->
<section class="bg-teal text-white py-16 text-center border-t-4 border-ink">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="font-display font-bold text-2xl md:text-3xl mb-4">Don't see your industry listed?</h2>
        <p class="font-serif italic text-white/80 mb-8">We supply to various niche manufacturing sectors. Contact us for custom chemical procurement strategies.</p>
        <a href="{{ route('contact') }}" class="btn-primary bg-ink text-white hover:bg-white hover:text-ink px-8 py-4 font-display font-bold text-xs uppercase tracking-widest inline-block transition-colors">Contact Our Team</a>
    </div>
</section>
@endsection

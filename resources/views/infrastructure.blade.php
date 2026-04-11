@extends('layouts.app')

@section('title', 'Infrastructure & Logistics | ' . ($globalSettings['company_name'] ?? 'Auxinor Chemicals'))

@section('content')
<!-- Hero Banner -->
<section data-section-id="{{ $sections->where('section_key', 'infra_hero')->first()->id ?? '101' }}" data-section-key="infra_hero" data-section-label="Infrastructure Hero" class="bg-gradient-to-r from-ink to-ink2 text-white py-24 px-4 md:px-8 border-b-4 border-teal relative overflow-hidden @auth cms-editable @endauth" data-cms-label="Edit Infra Hero">
    <!-- Faint grid background -->
    <div class="absolute inset-0 opacity-10" style="background-image: linear-gradient(var(--white) 1px, transparent 1px), linear-gradient(90deg, var(--white) 1px, transparent 1px); background-size: 40px 40px;"></div>
    
    <div class="max-w-[1400px] mx-auto text-center relative z-10">
        <div class="font-mono text-[10px] uppercase tracking-widest text-teal-2 mb-4">Our Operations</div>
        <h1 class="font-display font-extrabold text-[40px] md:text-[56px] leading-tight mb-4">Infrastructure & <em class="font-serif italic font-normal text-teal-2">Logistics</em></h1>
        <div class="font-mono text-[11px] text-white/50 space-x-2">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
            <span>/</span>
            <span class="text-teal-2">Infrastructure</span>
        </div>
    </div>
</section>

<!-- Warehousing -->
<section data-section-id="{{ $sections->where('section_key', 'infra_details')->first()->id ?? '102' }}" data-section-key="infra_details" data-section-label="Warehousing" class="py-24 px-4 md:px-8 border-b border-line bg-white sr-up @auth cms-editable @endauth" data-cms-label="Edit Warehousing">
    <div class="max-w-[1400px] mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div>
            <span class="font-mono text-[10px] uppercase tracking-widest text-teal block mb-4">01. Storage Facilities</span>
            <h2 class="font-display font-extrabold text-[36px] md:text-[44px] leading-tight mb-6">Bulk Storage & <em class="font-serif italic text-teal font-normal">Warehousing</em></h2>
            <p class="font-serif text-[15px] text-muted leading-relaxed mb-6">
                Our state-of-the-art warehousing facilities in Ahmedabad are equipped to handle large volumes of liquid and solid industrial chemicals safely and efficiently.
            </p>
            <ul class="space-y-4 mb-8">
                <li class="flex items-center space-x-3 text-ink2">
                    <span class="text-teal text-lg">✓</span>
                    <span class="font-display font-bold text-sm">Dedicated petroleum & hazmat storage zones</span>
                </li>
                <li class="flex items-center space-x-3 text-ink2">
                    <span class="text-teal text-lg">✓</span>
                    <span class="font-display font-bold text-sm">Temperature-controlled solvent silos</span>
                </li>
                <li class="flex items-center space-x-3 text-ink2">
                    <span class="text-teal text-lg">✓</span>
                    <span class="font-display font-bold text-sm">Strict adherence to safety protocols (PESO compliant)</span>
                </li>
            </ul>
        </div>
        <div class="relative group">
            <div class="absolute -inset-4 bg-teal-light transform -rotate-2 transition-transform group-hover:rotate-0"></div>
            <div class="relative z-10 overflow-hidden shadow-lg">
                <img src="{{ $globalSettings['infra_image_url'] ?? asset('assets/images/service-warehousing.jpg') }}" alt="Warehousing" class="w-full filter grayscale-[10%] transform group-hover:scale-105 transition-transform duration-700" onerror="this.style.display='none'">
            </div>
        </div>
    </div>
</section>

<!-- Logistics -->
<section data-section-id="{{ $sections->where('section_key', 'infra_details')->first()->id ?? '102' }}" data-section-key="infra_details" data-section-label="Logistics" class="py-24 px-4 md:px-8 border-b border-line bg-bg sr-up @auth cms-editable @endauth" data-cms-label="Edit Logistics">
    <div class="max-w-[1400px] mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div class="order-last lg:order-first relative group">
            <div class="absolute -inset-4 bg-ink/5 transform rotate-2 transition-transform group-hover:rotate-0 border border-line"></div>
            <div class="relative z-10 overflow-hidden shadow-lg">
                <img src="{{ asset('assets/images/service-logistics.jpg') }}" alt="Supply Chain Map" class="w-full filter grayscale-[15%] transform group-hover:scale-105 transition-transform duration-700" onerror="this.style.display='none'">
            </div>
        </div>
        <div>
            <span class="font-mono text-[10px] uppercase tracking-widest text-teal block mb-4">02. Supply Chain</span>
            <h2 class="font-display font-extrabold text-[36px] md:text-[44px] leading-tight mb-6">Pan-India <em class="font-serif italic text-teal font-normal">Logistics</em></h2>
            <p class="font-serif text-[15px] text-muted leading-relaxed mb-6">
                From bulk tankers to ISO container deliveries, our robust logistical network ensures secure and timely dispatch of chemicals across all major industrial hubs in India.
            </p>
            <div class="grid grid-cols-2 gap-6 mt-8">
                <div class="bg-white p-6 border border-line shadow-sm">
                    <div class="font-display font-bold text-xl text-teal mb-2">GPS Monitored</div>
                    <p class="font-mono text-[11px] text-muted">Real-time truck tracing</p>
                </div>
                <div class="bg-white p-6 border border-line shadow-sm">
                    <div class="font-display font-bold text-xl text-teal mb-2">Custom Packaging</div>
                    <p class="font-mono text-[11px] text-muted">Drums, IBCs, Tankers</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Vendor Network -->
<section data-section-id="{{ $sections->where('section_key', 'infra_details')->first()->id ?? '102' }}" data-section-key="infra_details" data-section-label="Procurement" class="py-24 px-4 md:px-8 bg-white sr-up @auth cms-editable @endauth" data-cms-label="Edit Procurement">
    <div class="max-w-[1000px] mx-auto text-center">
        <span class="font-mono text-[10px] uppercase tracking-widest text-teal block mb-4">03. Procurement</span>
        <h2 class="font-display font-extrabold text-[36px] md:text-[44px] leading-tight mb-8">Global <em class="font-serif italic text-teal font-normal">Vendor Network</em></h2>
        <p class="font-serif text-[16px] text-muted leading-relaxed mb-12 max-w-2xl mx-auto">
            Our established relationships with major chemical manufacturers in India and abroad allow us to source premium materials and secure off-spec & surplus chemical batches at optimal prices, ensuring value passes directly to our clients.
        </p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-8 border border-line hover:border-teal transition-colors rounded">
                <div class="font-display font-bold text-3xl mb-2 text-ink">Tier 1</div>
                <div class="font-mono text-[10px] uppercase tracking-wider text-muted">Direct Manufacturer Tie-ups</div>
            </div>
            <div class="p-8 border border-line hover:border-teal transition-colors rounded">
                <div class="font-display font-bold text-3xl mb-2 text-ink">Surplus</div>
                <div class="font-mono text-[10px] uppercase tracking-wider text-muted">Asset Recovery Trading</div>
            </div>
            <div class="p-8 border border-line hover:border-teal transition-colors rounded">
                <div class="font-display font-bold text-3xl mb-2 text-ink">Quality</div>
                <div class="font-mono text-[10px] uppercase tracking-wider text-muted">Pre-dispatch Checks</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Strip -->
<section class="bg-ink text-white py-12 px-4 text-center border-t-2 border-teal">
    <div class="flex flex-col md:flex-row items-center justify-center gap-6">
        <h2 class="font-display font-bold text-xl md:text-2xl">Need reliable bulk chemical supply?</h2>
        <a href="{{ route('contact') }}#enquiry-form" class="btn-primary px-8 py-3 font-display font-bold text-xs uppercase tracking-widest bg-teal text-white border-0 transition-colors">Get in touch →</a>
    </div>
</section>
@endsection

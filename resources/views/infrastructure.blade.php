@extends('layouts.app')

@section('title', 'Infrastructure & Logistics | ' . ($globalSettings['company_name'] ?? 'Auxinor Chemicals'))

@section('content')
<!-- Hero Banner -->
<section data-section-id="125" data-section-key="infra_hero" data-section-label="Infrastructure Hero" class="relative text-white py-24 px-4 md:px-8 border-b-4 border-teal overflow-hidden @auth cms-editable @endauth" data-cms-label="Edit Infra Hero">
    <!-- Background Overlay Layer (Handles Both Design & Editor Images) -->
    <div class="ed-bg-overlay absolute inset-0 bg-gradient-to-r from-ink to-ink2 -z-10">
        <!-- Faint grid background -->
        <div class="absolute inset-0 opacity-10" style="background-image: linear-gradient(var(--white) 1px, transparent 1px), linear-gradient(90deg, var(--white) 1px, transparent 1px); background-size: 40px 40px;"></div>
    </div>
    
    <div class="max-w-[1400px] mx-auto text-center relative z-10">
 <div data-element-id="infra_badge" class="font-sans font-bold text-[10px] uppercase tracking-[2px] text-teal-2 mb-4">Our Operations</div>
        <h1 data-element-id="infra_title" class="font-display font-extrabold text-[40px] md:text-[72px] leading-[1.1] mb-8">Infrastructure & <em class="italic font-normal text-teal-2">Logistics</em></h1>
        <div class="font-sans font-medium text-[11px] uppercase tracking-wider text-white/50 space-x-2">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
            <span>/</span>
            <span class="text-teal-2">Infrastructure</span>
        </div>
    </div>
</section>

<!-- Warehousing -->
 <section id="warehousing" data-section-id="126" data-section-key="infra_warehousing" data-section-label="Warehousing" class="py-24 px-4 md:px-8 border-b border-line bg-white sr-up @auth cms-editable @endauth" data-cms-label="Edit Warehousing">
    <div class="max-w-[1400px] mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
 <div>
            <span data-element-id="warehousing_badge" class="font-sans font-bold text-[10px] uppercase tracking-[2px] text-teal block mb-4">01. Storage Facilities</span>
            <h2 data-element-id="warehousing_title" class="font-display font-extrabold text-[44px] leading-tight mb-6 text-ink">Bulk Storage & <em class="italic text-teal font-normal">Warehousing</em></h2>
            <p data-element-id="warehousing_desc" class="font-sans text-[15px] text-muted leading-relaxed mb-6">
                Our state-of-the-art warehousing facilities in Ahmedabad are equipped to handle large volumes of liquid and solid industrial chemicals safely and efficiently.
            </p>
            <ul data-element-id="warehousing_list" class="space-y-4 mb-8">
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
                <img data-element-id="warehousing_image" src="{{ $globalSettings['infra_image_url'] ?? asset('assets/images/service-warehousing.jpg') }}" alt="Warehousing" class="w-full filter grayscale-[10%] transform group-hover:scale-105 transition-transform duration-700" onerror="this.style.display='none'">
            </div>
        </div>
    </div>
</section>

<!-- Logistics -->
 <section id="logistics" data-section-id="127" data-section-key="infra_logistics" data-section-label="Logistics" class="py-24 px-4 md:px-8 border-b border-line bg-bg sr-up @auth cms-editable @endauth" data-cms-label="Edit Logistics">
    <div class="max-w-[1400px] mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div class="order-last lg:order-first relative group">
            <div class="absolute -inset-4 bg-ink/5 transform rotate-2 transition-transform group-hover:rotate-0 border border-line"></div>
            <div class="relative z-10 overflow-hidden shadow-lg">
                <img data-element-id="logistics_image" src="{{ asset('assets/images/service-logistics.jpg') }}" alt="Supply Chain Map" class="w-full filter grayscale-[15%] transform group-hover:scale-105 transition-transform duration-700" onerror="this.style.display='none'">
            </div>
        </div>
 <div>
            <span data-element-id="logistics_badge" class="font-sans font-bold text-[10px] uppercase tracking-[2px] text-teal block mb-4">02. Supply Chain</span>
            <h2 data-element-id="logistics_title" class="font-display font-extrabold text-[44px] leading-tight mb-6 text-ink">Pan-India <em class="italic text-teal font-normal">Logistics</em></h2>
            <p data-element-id="logistics_desc" class="font-sans text-[15px] text-muted leading-relaxed mb-6">
                From bulk tankers to ISO container deliveries, our robust logistical network ensures secure and timely dispatch of chemicals across all major industrial hubs in India.
            </p>
 <div class="grid grid-cols-2 gap-6 mt-8">
                <div data-element-id="logistics_stat_1" class="bg-white p-6 border border-line shadow-sm">
                    <div class="font-display font-bold text-xl text-teal mb-2">GPS Monitored</div>
                    <p class="font-sans font-bold text-[9px] uppercase tracking-widest text-muted">Real-time truck tracing</p>
                </div>
                <div data-element-id="logistics_stat_2" class="bg-white p-6 border border-line shadow-sm">
                    <div class="font-display font-bold text-xl text-teal mb-2">Custom Packaging</div>
                    <p class="font-sans font-bold text-[9px] uppercase tracking-widest text-muted">Drums, IBCs, Tankers</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Vendor Network -->
<section data-section-id="128" data-section-key="infra_procurement" data-section-label="Procurement" class="py-24 px-4 md:px-8 bg-white sr-up @auth cms-editable @endauth" data-cms-label="Edit Procurement">
 <div class="max-w-[1000px] mx-auto text-center">
        <span data-element-id="procure_badge" class="font-sans font-bold text-[10px] uppercase tracking-[2px] text-teal block mb-4">03. Procurement</span>
        <h2 data-element-id="procure_title" class="font-display font-extrabold text-[48px] leading-tight mb-8 text-ink">Global <em class="italic text-teal font-normal">Vendor Network</em></h2>
        <p data-element-id="procure_desc" class="font-sans text-[16px] text-muted leading-relaxed mb-12 max-w-2xl mx-auto">
            Our established relationships with major chemical manufacturers in India and abroad allow us to source premium materials and secure off-spec & surplus chemical batches at optimal prices, ensuring value passes directly to our clients.
        </p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div data-element-id="procure_card_1" class="p-8 border border-line hover:border-teal transition-colors rounded">
                <div class="font-display font-bold text-3xl mb-2 text-ink">Tier 1</div>
                <div class="font-sans font-bold text-[9px] uppercase tracking-widest text-muted">Direct Manufacturer Tie-ups</div>
            </div>
            <div data-element-id="procure_card_2" class="p-8 border border-line hover:border-teal transition-colors rounded">
                <div class="font-display font-bold text-3xl mb-2 text-ink">Surplus</div>
                <div class="font-sans font-bold text-[9px] uppercase tracking-widest text-muted">Asset Recovery Trading</div>
            </div>
            <div data-element-id="procure_card_3" class="p-8 border border-line hover:border-teal transition-colors rounded">
                <div class="font-display font-bold text-3xl mb-2 text-ink">Quality</div>
                <div class="font-sans font-bold text-[9px] uppercase tracking-widest text-muted">Pre-dispatch Checks</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Strip -->
<section data-section-id="120" data-section-key="services_strip" data-section-label="CTA Strip" class="bg-ink text-white py-12 px-4 text-center border-t-2 border-teal @auth cms-editable @endauth" data-cms-label="Edit CTA Strip">
    <div class="flex flex-col md:flex-row items-center justify-center gap-6">
        <h2 data-element-id="cta_text" class="font-display font-bold text-xl md:text-2xl">Need reliable bulk chemical supply?</h2>
        <a data-element-id="cta_btn" href="{{ route('contact') }}#enquiry-form" class="btn-primary px-8 py-3 font-display font-bold text-xs uppercase tracking-widest bg-teal text-white border-0 transition-colors">Get in touch →</a>
    </div>
</section>
@endsection

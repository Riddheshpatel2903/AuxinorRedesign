@extends('layouts.app')

@section('title', 'About Us | ' . ($globalSettings['company_name'] ?? 'Auxinor Chemicals'))

@section('content')
<!-- Hero Banner -->
<section data-section-id="8" data-section-key="about_hero" data-section-label="About Hero" class="bg-gradient-to-r from-ink to-ink2 text-white py-24 px-4 md:px-8 border-b-4 border-teal @auth cms-editable @endauth" data-cms-label="Edit About Hero">
    <div class="max-w-[1400px] mx-auto text-center">
        <div class="font-mono text-[10px] uppercase tracking-widest text-teal-2 mb-4">About Auxinor Chemicals</div>
        <h1 class="font-display font-extrabold text-[40px] md:text-[56px] leading-tight mb-4">Trading With <em class="font-serif italic font-normal text-teal-2">Integrity</em></h1>
        <div class="font-mono text-[11px] text-white/50 space-x-2">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
            <span>/</span>
            <span class="text-teal-2">About</span>
        </div>
    </div>
</section>

<!-- Our Story / Mission -->
<section data-section-id="9" data-section-key="about_mission" data-section-label="Mission & Story" class="py-20 px-4 md:px-8 max-w-[1000px] mx-auto text-center sr-up @auth cms-editable @endauth" data-cms-label="Edit Mission & Story">
    <div class="w-12 h-12 bg-teal flex items-center justify-center text-white text-xl mx-auto mb-8 font-serif italic">"</div>
    <h2 data-element-id="el_setting:mission" class="font-display font-bold text-[28px] md:text-[36px] leading-snug mb-8">
        {{ $globalSettings['mission'] ?? 'Our mission is to catalyze global commerce by embodying values and fostering long-term relationships.' }}
    </h2>
    <p data-element-id="el_setting:about_long" class="font-serif text-[16px] text-muted leading-relaxed max-w-2xl mx-auto">
        {{ $globalSettings['about_long'] ?? "Grounded in entrepreneurial spirit, we ensure sustained growth through honest, accountable chemical trading. We specialize in bulk procurement and supply chain optimization." }}
    </p>
</section>

<!-- Values Cards -->
<section data-section-id="10" data-section-key="about_values" data-section-label="Core Values" class="bg-bg py-24 px-4 md:px-8 border-y border-line @auth cms-editable @endauth" data-cms-label="Edit Core Values">
    <div class="max-w-[1400px] mx-auto">
        <div class="text-center mb-16">
            <span class="font-mono text-[10px] uppercase tracking-widest text-teal block mb-4">Core Principles</span>
            <h2 class="font-display font-extrabold text-[40px]">Our <em class="font-serif italic text-teal font-normal">Values</em></h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 sr-stagger">
            <div class="bg-white p-10 border-l-[3px] border-teal shadow-sm hover:shadow-md transition-shadow">
                <h3 class="font-display font-bold text-xl mb-4 text-ink">Teamwork & Trust</h3>
                <p class="font-serif text-[14px] text-muted leading-relaxed">We foster collaborative partnerships with both vendors and clients, building long-term trust that transcends transactional business.</p>
            </div>
            <div class="bg-white p-10 border-l-[3px] border-teal shadow-sm hover:shadow-md transition-shadow">
                <h3 class="font-display font-bold text-xl mb-4 text-ink">Ownership</h3>
                <p class="font-serif text-[14px] text-muted leading-relaxed">We take full accountability for our chemical supply chain, ensuring quality and timely delivery for every order we undertake.</p>
            </div>
            <div class="bg-white p-10 border-l-[3px] border-teal shadow-sm hover:shadow-md transition-shadow">
                <h3 class="font-display font-bold text-xl mb-4 text-ink">Sustainability</h3>
                <p class="font-serif text-[14px] text-muted leading-relaxed">Committed to ethical trading practices and promoting sustainable chemical usage across India's industrial manufacturing sectors.</p>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section data-section-id="11" data-section-key="about_advantage" data-section-label="The Advantage" class="py-24 px-4 md:px-8 max-w-[1400px] mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center @auth cms-editable @endauth" data-cms-label="Edit Advantage">
    <div class="relative sr-l group">
        <div class="absolute -inset-4 bg-teal-light transform rotate-3 transition-transform group-hover:rotate-0"></div>
        <div class="relative z-10 overflow-hidden shadow-lg">
            <img src="{{ $globalSettings['about_image_url'] ?? asset('assets/images/hero-1.jpg') }}" alt="Industrial Facility" class="w-full object-cover filter grayscale-[10%] transform group-hover:scale-105 transition-transform duration-700" onerror="this.style.display='none'">
        </div>
    </div>
    
    <div class="sr-r">
        <span class="font-mono text-[10px] uppercase tracking-widest text-teal block mb-4">The Auxinor Advantage</span>
        <h2 class="font-display font-extrabold text-[40px] leading-tight mb-8">Why Partner With <em class="font-serif italic text-teal font-normal">Us?</em></h2>
        
        <div class="space-y-8">
            <div class="flex items-start space-x-6">
                <div class="w-12 h-12 rounded-full border border-teal flex items-center justify-center flex-shrink-0 text-teal">✨</div>
                <div>
                    <h3 class="font-display font-bold text-[16px] mb-2">Quality Assurance</h3>
                    <p class="font-serif text-[14px] text-muted leading-relaxed">Stringent quality checks and documentation for all chemical consignments.</p>
                </div>
            </div>
            <div class="flex items-start space-x-6">
                <div class="w-12 h-12 rounded-full border border-teal flex items-center justify-center flex-shrink-0 text-teal">🤝</div>
                <div>
                    <h3 class="font-display font-bold text-[16px] mb-2">Reliable Vendor Network</h3>
                    <p class="font-serif text-[14px] text-muted leading-relaxed">Direct relationships with top-tier manufacturers ensuring competitive pricing.</p>
                </div>
            </div>
            <div class="flex items-start space-x-6">
                <div class="w-12 h-12 rounded-full border border-teal flex items-center justify-center flex-shrink-0 text-teal">🔄</div>
                <div>
                    <h3 class="font-display font-bold text-[16px] mb-2">Surplus Optimization</h3>
                    <p class="font-serif text-[14px] text-muted leading-relaxed">Specialized in moving surplus chemicals efficiently to minimize industrial waste.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trust Stats -->
<section data-section-id="12" data-section-key="about_stats" data-section-label="Trust Stats" class="bg-ink py-16 px-4 md:px-8 border-t border-line text-white @auth cms-editable @endauth" data-cms-label="Edit Trust Stats">
    <div class="max-w-[1400px] mx-auto grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-white/10">
        <div>
            <div class="font-display font-bold text-4xl text-teal-2 mb-2" data-count="{{ $globalSettings['stat_years'] ?? '7' }}">0</div>
            <div class="font-mono text-[10px] uppercase tracking-wider text-white/50">Years Active</div>
        </div>
        <div>
            <div class="font-display font-bold text-4xl text-teal-2 mb-2" data-count="{{ $globalSettings['stat_products'] ?? '80' }}">0</div>
            <div class="font-mono text-[10px] uppercase tracking-wider text-white/50">Products Portfolio</div>
        </div>
        <div>
            <div class="font-display font-bold text-4xl text-teal-2 mb-2" data-count="{{ $globalSettings['stat_industries'] ?? '6' }}">0</div>
            <div class="font-mono text-[10px] uppercase tracking-wider text-white/50">Core Industries</div>
        </div>
        <div>
            <div class="font-display font-bold text-4xl text-teal-2 mb-2" data-count="100">0</div>
            <div class="font-mono text-[10px] uppercase tracking-wider text-white/50">Deliveries (Pan-India)</div>
        </div>
    </div>
</section>
@endsection

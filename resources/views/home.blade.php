@extends('layouts.app')

@section('content')
    <!-- HERO SECTION -->
    <section data-section-id="{{ $sections->where('section_key', 'home_hero')->first()->id ?? '113' }}"
        data-section-key="home_hero" data-section-label="Hero Banner"
        class="group min-h-[88vh] border-b border-line relative overflow-hidden @auth cms-editable @endauth"
        data-cms-label="Edit Hero Section" x-data="{ 
                images: [
                    @if(!empty($globalSettings['hero_bg_1'])) { url: '{{ $globalSettings['hero_bg_1'] }}', overlay: {{ $globalSettings['hero_bg_overlay_1'] ?? '0.5' }} }, @endif
                    @if(!empty($globalSettings['hero_bg_2'])) { url: '{{ $globalSettings['hero_bg_2'] }}', overlay: {{ $globalSettings['hero_bg_overlay_2'] ?? '0.5' }} }, @endif
                    @if(!empty($globalSettings['hero_bg_3'])) { url: '{{ $globalSettings['hero_bg_3'] }}', overlay: {{ $globalSettings['hero_bg_overlay_3'] ?? '0.5' }} }, @endif
                    @if(!empty($globalSettings['hero_bg_4'])) { url: '{{ $globalSettings['hero_bg_4'] }}', overlay: {{ $globalSettings['hero_bg_overlay_4'] ?? '0.5' }} }, @endif
                    @if(empty($globalSettings['hero_bg_1']) && empty($globalSettings['hero_bg_2']) && empty($globalSettings['hero_bg_3']) && empty($globalSettings['hero_bg_4']))
                        { url: '{{ asset('assets/images/hero-1.jpg') }}', overlay: 0.5 }
                    @endif
                ],
                activeIndex: 0 
             }" x-init="autoTimer = setInterval(() => { activeIndex = (activeIndex + 1) % images.length }, 4000)"
             @if(app('request')->input('editor_mode'))
             @click.self="window.parent.postMessage({ src: 'auxinor-site', action: 'section-click', payload: { id: '{{ $sections->where('section_key', 'home_hero')->first()->id ?? '113' }}', key: 'home_hero', label: 'Hero Banner' } }, '*')"
             @endif>

        <!-- Selection Shield (Editor Only) -->
        @if(app('request')->input('editor_mode'))
        <div class="absolute inset-0 z-40 bg-teal/0 hover:bg-teal/5 border-2 border-transparent hover:border-teal transition-all pointer-events-none group-hover:pointer-events-auto cursor-pointer" 
             style="pointer-events: none;"
             title="Click header text to edit content, or click empty space to manage library."></div>
        @endif

        <!-- Dynamic Background Images -->
        @if(($globalSettings['show_hero_image'] ?? '1') == '1')
            <template x-for="(slide, index) in images" :key="index">
                <div class="absolute inset-0 transition-opacity duration-[2s] ease-in-out {{ app('request')->input('editor_mode') ? 'pointer-events-none' : '' }}"
                    :class="activeIndex === index ? 'opacity-100' : 'opacity-0'">
                    <img :src="slide.url" alt="Chemical Infrastructure" class="w-full h-full object-cover" />
                    <div class="absolute inset-0" :style="'background: rgba(0,0,0,' + slide.overlay + ')'"></div>
                </div>
            </template>
        @endif

        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 h-full min-h-[88vh]">
            <!-- Left Column -->
            <div class="relative px-8 lg:px-20 py-16 flex flex-col justify-center align-item-center text-white">
                <div class="flex items-center mb-8">
                    <div class="w-6 h-[2px] bg-teal-2 mr-4"></div>
                    <span class="font-sans text-[11px] font-semibold uppercase tracking-[2px] text-teal-2">Chemical Trading
                        & Distribution</span>
                </div>

                <h1 class="font-display font-extrabold text-[50px] lg:text-[72px] leading-[1.05] tracking-tight mb-8">
                    <div class="clip overflow-hidden"><span data-element-id="el_setting:hero_headline_line1"
                            class="block text-white transform translate-y-full transition-transform duration-[1.2s] ease-out"
                            x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)"
                            :class="loaded ? '!translate-y-0' : ''">{{ $globalSettings['hero_headline_line1'] ?? 'Trusted' }}</span>
                    </div>
                    <div class="clip overflow-hidden"><span data-element-id="el_setting:hero_headline_line2"
                            class="block text-white transform translate-y-full transition-transform duration-[1.2s] ease-out delay-[200ms]"
                            x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)"
                            :class="loaded ? '!translate-y-0' : ''">{{ $globalSettings['hero_headline_line2'] ?? 'Chemical' }}</span>
                    </div>
                    <div class="clip overflow-hidden"><span data-element-id="el_setting:hero_headline_line3"
                            class="block text-teal-light font-display italic font-medium transform translate-y-full transition-transform duration-[1.2s] ease-out delay-[400ms]"
                            x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)"
                            :class="loaded ? '!translate-y-0' : ''">{{ $globalSettings['hero_headline_line3'] ?? 'Partners' }}</span>
                    </div>
                </h1>

                <p data-element-id="el_setting:hero_subtext"
                    class="font-sans text-[17px] text-white/80 max-w-lg mb-12 sr data-delay-850 leading-relaxed font-light">
                    {{ $globalSettings['hero_subtext'] ?? "Specialists in procurement, bulk distribution, and surplus chemical trading — serving India's top industrial sectors since 2017." }}
                </p>

                <div class="flex flex-wrap items-center gap-6 mb-16 sr data-delay-1050">
                    <a href="{{ route('products.index') }}"
                        class="btn-primary px-8 py-4 font-sans font-semibold text-[11px] uppercase tracking-widest bg-teal text-white hover:bg-white hover:text-ink transition-colors border border-transparent">Explore
                        Products</a>
                    <a href="{{ route('contact') }}#enquiry-form"
                        class="px-8 py-4 font-sans font-semibold text-[11px] uppercase tracking-widest text-white border border-white/30 hover:bg-white hover:text-ink transition-colors backdrop-blur-sm">Get
                        a Quote</a>
                </div>

                <!-- Trust Strip -->
                <div class="mt-auto pt-8 border-t border-white/20 grid grid-cols-2 md:grid-cols-4 gap-6 sr data-delay-1250">
                    <div>
                        <div class="font-display font-medium text-3xl mb-1 text-white" data-count="{{ $stats['years'] }}">0
                        </div>
                        <div class="font-sans text-[10px] uppercase tracking-wider text-white/50 font-semibold">Years</div>
                    </div>
                    <div>
                        <div class="font-display font-medium text-3xl mb-1 text-white"
                            data-count="{{ $stats['products'] }}">0</div>
                        <div class="font-sans text-[10px] uppercase tracking-wider text-white/50 font-semibold">Products
                        </div>
                    </div>
                    <div>
                        <div class="font-display font-medium text-3xl mb-1 text-white"
                            data-count="{{ $stats['industries'] }}">0</div>
                        <div class="font-sans text-[10px] uppercase tracking-wider text-white/50 font-semibold">Industries
                        </div>
                    </div>
                    <div>
                        <div class="font-display font-medium text-3xl mb-1 text-white">{{ $stats['reach'] }}</div>
                        <div class="font-sans text-[10px] uppercase tracking-wider text-white/50 font-semibold">Reach</div>
                    </div>
                </div>
            </div>

        </div>
        <!-- Slide Controllers -->
        <div class="absolute top-1/2 -translate-y-1/2 w-full px-4 md:px-12 flex justify-between z-20 pointer-events-none">
            <button
                @click="activeIndex = (activeIndex - 1 + images.length) % images.length; clearInterval(autoTimer); autoTimer = setInterval(() => activeIndex = (activeIndex+1) % images.length, 4000)"
                class="w-12 h-12 rounded-full border border-white/30 bg-black/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-teal hover:border-teal transition-all pointer-events-auto transform -translate-x-4 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 focus:opacity-100 active:scale-95 duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button
                @click="activeIndex = (activeIndex + 1) % images.length; clearInterval(autoTimer); autoTimer = setInterval(() => activeIndex = (activeIndex+1) % images.length, 4000)"
                class="w-12 h-12 rounded-full border border-white/30 bg-black/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-teal hover:border-teal transition-all pointer-events-auto transform translate-x-4 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 focus:opacity-100 active:scale-95 duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>

        <!-- Dots -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex gap-3 z-20">
            <template x-for="(slide, index) in images" :key="'dot-'+index">
                <button
                    @click="activeIndex = index; clearInterval(autoTimer); autoTimer = setInterval(() => activeIndex = (activeIndex+1) % images.length, 4000)"
                    class="h-1.5 rounded-full transition-all duration-300 pointer-events-auto"
                    :class="activeIndex === index ? 'w-8 bg-teal-2' : 'w-2 bg-white/40 hover:bg-white/70'">
                </button>
            </template>
        </div>
    </section>

    <!-- SERVICES STRIP -->
    <section data-section-id="{{ $sections->where('section_key', 'services_strip')->first()->id ?? '120' }}"
        data-section-key="services_strip" data-section-label="Services Strip"
        class="bg-ink grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 p-4 lg:p-8 gap-4 sr-up relative z-10 @auth cms-editable @endauth"
        data-cms-label="Edit Services Section">
        <!-- Card 1: Trading -->
        <a href="{{ route('about') }}#chemical-trading"
            class="relative h-[350px] lg:h-[450px] bg-ink2 overflow-hidden group rounded-sm block hover:-translate-y-2 transition-transform duration-500">
            <div data-element-id="el_setting:service_card_bg_1"
                class="absolute inset-0 w-full h-full bg-cover bg-center filter brightness-75 group-hover:scale-105 group-hover:brightness-100 transition-all duration-700"
                style="background-image: url('{{ $globalSettings['service_card_bg_1'] ?? asset('assets/images/service-trading.jpg') }}')">
            </div>
            <div
                class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-90 pointer-events-none">
            </div>
            <div class="absolute inset-0 p-8 flex flex-col justify-between pointer-events-none">
                <h3
                    class="font-display font-medium text-3xl text-white leading-tight max-w-[200px] group-hover:text-4xl group-hover:tracking-tight transition-all duration-500 pointer-events-auto">
                    Chemical<br>Trading</h3>
                <div
                    class="pointer-events-auto inline-flex items-center justify-center space-x-2 border border-white/40 rounded-full px-5 py-2.5 w-max backdrop-blur-md hover:bg-white hover:text-ink transition-all">
                    <span
                        class="font-sans text-[11px] font-semibold uppercase tracking-wider text-white group-hover:text-ink transition-colors">Learn
                        More</span>
                    <svg class="w-4 h-4 text-white group-hover:text-ink transition-colors" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                        </path>
                    </svg>
                </div>
            </div>
        </a>
        <!-- Card 2: Distribution -->
        <a href="{{ route('infrastructure') }}#logistics"
            class="relative h-[350px] lg:h-[450px] bg-ink2 overflow-hidden group rounded-sm block hover:-translate-y-2 transition-transform duration-500">
            <div data-element-id="el_setting:service_card_bg_2"
                class="absolute inset-0 w-full h-full bg-cover bg-center filter brightness-75 group-hover:scale-105 group-hover:brightness-100 transition-all duration-700"
                style="background-image: url('{{ $globalSettings['service_card_bg_2'] ?? asset('assets/images/service-logistics.jpg') }}')">
            </div>
            <div
                class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-90 pointer-events-none">
            </div>
            <div class="absolute inset-0 p-8 flex flex-col justify-between pointer-events-none">
                <h3
                    class="font-display font-medium text-3xl text-white leading-tight max-w-[200px] group-hover:text-4xl group-hover:tracking-tight transition-all duration-500 pointer-events-auto">
                    Distribution<br>& Logistics</h3>
                <div
                    class="pointer-events-auto inline-flex items-center justify-center space-x-2 border border-white/40 rounded-full px-5 py-2.5 w-max backdrop-blur-md hover:bg-white hover:text-ink transition-all">
                    <span
                        class="font-sans text-[11px] font-semibold uppercase tracking-wider text-white group-hover:text-ink transition-colors">Learn
                        More</span>
                    <svg class="w-4 h-4 text-white group-hover:text-ink transition-colors" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                        </path>
                    </svg>
                </div>
            </div>
        </a>
        <!-- Card 3: Warehousing -->
        <a href="{{ route('infrastructure') }}#warehousing"
            class="relative h-[350px] lg:h-[450px] bg-ink2 overflow-hidden group rounded-sm block hover:-translate-y-2 transition-transform duration-500">
            <div data-element-id="el_setting:service_card_bg_3"
                class="absolute inset-0 w-full h-full bg-cover bg-center filter brightness-75 group-hover:scale-105 group-hover:brightness-100 transition-all duration-700"
                style="background-image: url('{{ $globalSettings['service_card_bg_3'] ?? asset('assets/images/service-warehousing.jpg') }}')">
            </div>
            <div
                class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-90 pointer-events-none">
            </div>
            <div class="absolute inset-0 p-8 flex flex-col justify-between pointer-events-none">
                <h3
                    class="font-display font-medium text-3xl text-white leading-tight max-w-[200px] group-hover:text-4xl group-hover:tracking-tight transition-all duration-500 pointer-events-auto">
                    Bulk<br>Warehousing</h3>
                <div
                    class="pointer-events-auto inline-flex items-center justify-center space-x-2 border border-white/40 rounded-full px-5 py-2.5 w-max backdrop-blur-md hover:bg-white hover:text-ink transition-all">
                    <span
                        class="font-sans text-[11px] font-semibold uppercase tracking-wider text-white group-hover:text-ink transition-colors">Learn
                        More</span>
                    <svg class="w-4 h-4 text-white group-hover:text-ink transition-colors" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                        </path>
                    </svg>
                </div>
            </div>
        </a>
        <!-- Card 4: Insights -->
        <a href="{{ route('insights.index') }}"
            class="relative h-[350px] lg:h-[450px] bg-ink2 overflow-hidden group rounded-sm block hover:-translate-y-2 transition-transform duration-500">
            <div data-element-id="el_setting:service_card_bg_4"
                class="absolute inset-0 w-full h-full bg-cover bg-center filter brightness-75 group-hover:scale-105 group-hover:brightness-100 transition-all duration-700"
                style="background-image: url('{{ $globalSettings['service_card_bg_4'] ?? asset('assets/images/service-insights.jpg') }}')">
            </div>
            <div
                class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-90 pointer-events-none">
            </div>
            <div class="absolute inset-0 p-8 flex flex-col justify-between pointer-events-none">
                <h3
                    class="font-display font-medium text-3xl text-white leading-tight max-w-[200px] group-hover:text-4xl group-hover:tracking-tight transition-all duration-500 pointer-events-auto">
                    Market<br>Insights</h3>
                <div
                    class="pointer-events-auto inline-flex items-center justify-center space-x-2 border border-white/40 rounded-full px-5 py-2.5 w-max backdrop-blur-md hover:bg-white hover:text-ink transition-all">
                    <span
                        class="font-sans text-[11px] font-semibold uppercase tracking-wider text-white group-hover:text-ink transition-colors">Learn
                        More</span>
                    <svg class="w-4 h-4 text-white group-hover:text-ink transition-colors" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                        </path>
                    </svg>
                </div>
            </div>
        </a>
    </section>

    <!-- INDUSTRIES SECTION -->
    <section data-section-id="{{ $sections->where('section_key', 'home_industries')->first()->id ?? '114' }}"
        data-section-key="home_industries" data-section-label="Industries Preview"
        class="bg-white py-[100px] px-4 md:px-8 @auth cms-editable @endauth" data-cms-label="Edit Industries Section">
        <div class="max-w-[1400px] mx-auto">
            <div class="mb-16">
 <span class="font-sans font-bold text-[10px] uppercase tracking-[2px] text-teal block mb-4 sr-up">Industries Served</span>
<h2 class="font-display font-extrabold text-[40px] md:text-[56px] leading-tight text-ink mb-2">Where We <em class="italic text-teal font-normal">Deliver</em></h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sr-stagger">
                @foreach($industries as $idx => $industry)
                    <a href="{{ route('industries') }}" data-model-type="Industry"
                        class="group relative rounded-sm h-[320px] overflow-hidden flex flex-col industry-card block">
                        <!-- Background Image -->
                        <img src="{{ asset($industry->image_path) }}"
                            class="absolute inset-0 w-full h-full object-cover filter grayscale-[40%] group-hover:grayscale-0 group-hover:scale-105 transition-all duration-700 ease-out"
                            alt="{{ $industry->name }}" onerror="this.src='{{ asset('assets/images/hero-1.jpg') }}'">

                        <!-- Default Dark Gradient Overlay -->
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-ink via-ink/60 to-ink/20 opacity-90 group-hover:opacity-70 transition-opacity duration-500 z-0">
                        </div>

                        <div class="relative z-10 p-8 flex flex-col h-full justify-between">
                            <div class="flex justify-between items-start">
                                <span
                                    class="font-sans font-semibold text-[12px] text-white/50 group-hover:text-white transition-colors">0{{ $idx + 1 }}</span>
                                <div
                                    class="text-[32px] transform origin-center transition-transform duration-500 group-hover:scale-110">
                                    {{ $industry->icon }}</div>
                            </div>

                            <div class="mt-auto transform transition-transform duration-500 group-hover:-translate-y-2">
                                <h3 class="font-display font-medium text-[22px] text-white mb-2">{{ $industry->name }}</h3>
                                <p
                                    class="font-sans text-[13px] text-white/70 group-hover:text-white/90 transition-colors line-clamp-2">
                                    {{ $industry->description }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- PRODUCTS SECTION -->
    <section data-section-id="{{ $sections->where('section_key', 'home_products')->first()->id ?? '117' }}"
        data-section-key="home_products" data-section-label="Featured Products"
        class="bg-bg py-[100px] px-4 md:px-8 border-y border-line @auth cms-editable @endauth"
        data-cms-label="Edit Products Preview">
        <div class="max-w-[1400px] mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16">
                <div>
 <span class="font-sans font-bold text-[10px] uppercase tracking-[2px] text-teal block mb-4 sr-up">Catalogue</span>
<h2 class="font-display font-extrabold text-[40px] md:text-[56px] leading-tight text-ink mb-2">Our <em class="italic text-teal font-normal">Range</em></h2>
                </div>
                <a href="{{ route('products.index') }}"
                    class="font-mono text-[11px] text-teal hover:text-ink uppercase tracking-wider transition-colors mt-6 md:mt-0">All
                    Products →</a>
            </div>

            <!-- Filter Bar -->
            <div class="flex flex-wrap gap-2 mb-12 border border-line p-2 bg-white/5 backdrop-blur-sm pf-container">
                <button class="pf active px-4 py-2 font-mono text-[9px] uppercase tracking-wider bg-teal text-white"
                    data-filter="all">All</button>
                @foreach($categories->take(4) as $cat)
                    <button
                        class="pf px-4 py-2 font-mono text-[9px] uppercase tracking-wider bg-white/10 text-ink hover:bg-teal hover:text-white transition-colors border border-white/5"
                        data-filter=".cat-{{ $cat->id }}">{{ $cat->name }}</button>
                @endforeach
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 product-grid">
                @php
                    $containerImages = [
                        asset('assets/images/product-1.jpg'), // Blue drums
                        asset('assets/images/product-2.jpg'), // IBC Totes
                        asset('assets/images/product-3.jpg'), // Storage tanks
                        asset('assets/images/product-4.jpg'), // Metal barrels
                        asset('assets/images/product-5.jpg'), // White jerrycans
                        asset('assets/images/product-6.jpg')  // Pallets of chemicals
                    ];
                @endphp
                @foreach($featuredProducts as $idx => $product)
                    <a href="{{ route('products.show', ['category' => $product->category->slug ?? 'misc', 'slug' => $product->slug]) }}"
                        data-model-type="Product"
                        class="relative h-[260px] group flex flex-col product-item cat-{{ $product->category_id }} transform transition-all duration-500 sr-product overflow-hidden rounded-sm border border-line hover:border-teal"
                        style="--ds: {{ $idx * 85 }}ms">
                        <!-- Background Image -->
                        <img src="{{ $product->image ? (str_starts_with($product->image, 'http') ? $product->image : Storage::url($product->image)) : $containerImages[$idx % count($containerImages)] }}"
                            class="absolute inset-0 w-full h-full object-cover filter brightness-[0.6] group-hover:scale-105 group-hover:brightness-75 transition-all duration-700"
                            alt="{{ $product->name }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-ink via-ink/60 to-transparent"></div>

                        <!-- Content -->
                        <div class="relative h-full p-6 flex flex-col justify-end">
                            <span
                                class="font-sans font-medium text-[9px] uppercase tracking-widest text-[#a8d5cf] mb-2 block">{{ $product->cas_number ? 'CAS ' . $product->cas_number : 'NO CAS' }}</span>
                            <h3 class="font-display font-medium text-[16px] text-white leading-snug mb-1">{{ $product->name }}
                            </h3>
                            <span
                                class="font-sans text-[12px] text-white/70">{{ $product->category->name ?? 'Uncategorized' }}</span>

                            <div
                                class="absolute top-5 right-5 w-8 h-8 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center group-hover:bg-white group-hover:text-ink text-white transition-colors">
                                <svg class="w-3.5 h-3.5 group-hover:-rotate-45 transition-transform duration-300" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ABOUT / INFRASTRUCTURE SECTION -->
    <section data-section-id="{{ $sections->where('section_key', 'home_about')->first()->id ?? '116' }}"
        data-section-key="home_about" data-section-label="About Preview"
        class="bg-ink py-[110px] px-4 md:px-8 border-b-4 border-teal overflow-hidden relative @auth cms-editable @endauth"
        data-cms-label="Edit About section"
        @if(app('request')->input('editor_mode'))
        @click.self="window.parent.postMessage({ src: 'auxinor-site', action: 'section-click', payload: { id: '{{ $sections->where('section_key', 'home_about')->first()->id ?? '116' }}', key: 'home_about', label: 'About Preview' } }, '*')"
        @endif>
        @php
            $about_image = $globalSettings['about_image_url'] ?? asset('assets/images/hero-1.jpg');
            $infra_image = $globalSettings['infra_image_url'] ?? asset('assets/images/hero-2.jpg');
        @endphp
        
        <img src="{{ $about_image }}" data-element-id="el_setting:about_image_url"
            class="absolute left-0 top-0 w-1/2 h-full object-cover opacity-[0.08] filter grayscale pointer-events-none"
            style="mask-image: linear-gradient(to right, black, transparent);">
            
        <img src="{{ $infra_image }}" data-element-id="el_setting:infra_image_url"
            class="absolute right-0 top-0 w-1/2 h-full object-cover opacity-[0.08] filter grayscale pointer-events-none"
            style="mask-image: linear-gradient(to left, black, transparent);">

        <div class="max-w-[1400px] relative z-[60] mx-auto grid grid-cols-1 lg:grid-cols-2 gap-20">
            <!-- Left: About -->
            <div class="flex flex-col justify-center text-left pointer-events-auto">
                <span data-element-id="about_tagline" class="font-mono text-[10px] uppercase tracking-widest text-teal-2 block mb-6 sr-l">Our Legacy</span>
                <h2 data-element-id="about_headline"
                    class="font-display font-extrabold text-[40px] md:text-[48px] text-white leading-tight mb-8 sr-l data-delay-100">
                    Seven Years of <em class="font-serif italic text-teal-2 font-normal">Chemical</em> Excellence</h2>

 <div class="space-y-6 mb-12 sr-l data-delay-200">
                    <p data-element-id="about_short"
                        class="font-sans italic text-[14px] text-[#5a7080] leading-relaxed">
                        {{ $globalSettings['about_short'] ?? 'Auxinor Chemicals LLP is a B2B chemical trading company based in Ahmedabad, Gujarat. We specialize in the procurement, trading, and distribution of industrial chemicals.' }}
                    </p>
                    <p data-element-id="about_long"
                        class="font-sans italic text-[14px] text-[#5a7080] leading-relaxed">
                        {{ $globalSettings['about_long'] ?? 'Our strength lies in maintaining a strong vendor and sourcing network, providing competitive pricing, and ensuring reliable supply chains.' }}
                    </p>
                </div>

                <!-- Stats Glass Bar -->
                <div class="grid grid-cols-3 bg-white/[0.03] backdrop-blur-md border border-white/10 rounded-sm p-8 shadow-2xl sr-l data-delay-300 relative overflow-hidden group">
                    <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-transparent via-teal-2 to-transparent opacity-50"></div>
                    <div class="text-center border-r border-white/10">
                        <div class="font-display font-bold text-3xl text-teal-2 mb-1 drop-shadow-[0_0_10px_rgba(18,160,142,0.3)]" data-count="{{ $stats['years'] }}">0</div>
                        <div class="font-mono text-[9px] uppercase tracking-[2px] text-white/40">Years</div>
                    </div>
                    <div class="text-center border-r border-white/10">
                        <div class="font-display font-bold text-3xl text-white mb-1" data-count="{{ $stats['products'] }}">0</div>
                        <div class="font-mono text-[9px] uppercase tracking-[2px] text-white/40">Products</div>
                    </div>
                    <div class="text-center">
                        <div class="font-display font-bold text-3xl text-white mb-1" data-count="{{ $stats['industries'] }}">0</div>
                        <div class="font-mono text-[9px] uppercase tracking-[2px] text-white/40">Industries</div>
                    </div>
                </div>
            </div>

            <!-- Right: Premium Feature Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Card 1 -->
                <div class="bg-white/[0.02] backdrop-blur-xl border border-white/5 p-10 hover:border-teal-2 hover:bg-white/[0.05] transition-all duration-500 sr-r group relative rounded-sm" data-delay="0">
                    <div class="absolute inset-0 bg-gradient-to-br from-teal-2/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="w-14 h-14 bg-ink border border-white/10 flex items-center justify-center mb-8 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-500 relative">
                        <svg class="w-8 h-8 text-teal-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" preserveAspectRatio="xMidYMid meet"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h3 class="font-display font-bold text-white text-[17px] mb-3 tracking-wide">Warehousing</h3>
                    <p class="font-serif italic text-[14px] text-white/50 leading-relaxed group-hover:text-white/70 transition-colors">Safe storage for bulk organics and inorganics.</p>
                </div>
                <!-- Card 2 -->
                <div class="bg-white/[0.02] backdrop-blur-xl border border-white/5 p-10 hover:border-teal-2 hover:bg-white/[0.05] transition-all duration-500 sr-r group relative rounded-sm" data-delay="150">
                    <div class="absolute inset-0 bg-gradient-to-br from-teal-2/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="w-14 h-14 bg-ink border border-white/10 flex items-center justify-center mb-8 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-500 relative">
                        <svg class="w-8 h-8 text-teal-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" preserveAspectRatio="xMidYMid meet"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                    </div>
                    <h3 class="font-display font-bold text-white text-[17px] mb-3 tracking-wide">Pan-India Logistics</h3>
                    <p class="font-serif italic text-[14px] text-white/50 leading-relaxed group-hover:text-white/70 transition-colors">Timely dispatch and seamless supply chain.</p>
                </div>
                <!-- Card 3 -->
                <div class="bg-white/[0.02] backdrop-blur-xl border border-white/5 p-10 hover:border-teal-2 hover:bg-white/[0.05] transition-all duration-500 sr-r group relative rounded-sm" data-delay="300">
                    <div class="absolute inset-0 bg-gradient-to-br from-teal-2/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="w-14 h-14 bg-ink border border-white/10 flex items-center justify-center mb-8 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-500 relative">
                        <svg class="w-8 h-8 text-teal-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" preserveAspectRatio="xMidYMid meet"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="font-display font-bold text-white text-[17px] mb-3 tracking-wide">Vendor Network</h3>
                    <p class="font-serif italic text-[14px] text-white/50 leading-relaxed group-hover:text-white/70 transition-colors">Strong alliances with global manufacturers.</p>
                </div>
                <!-- Card 4 -->
                <div class="bg-white/[0.02] backdrop-blur-xl border border-white/5 p-10 hover:border-teal-2 hover:bg-white/[0.05] transition-all duration-500 sr-r group relative rounded-sm" data-delay="450">
                    <div class="absolute inset-0 bg-gradient-to-br from-teal-2/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="w-14 h-14 bg-ink border border-white/10 flex items-center justify-center mb-8 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-500 relative">
                        <svg class="w-8 h-8 text-teal-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" preserveAspectRatio="xMidYMid meet"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <h3 class="font-display font-bold text-white text-[17px] mb-3 tracking-wide">Inventory Mgmt</h3>
                    <p class="font-serif italic text-[14px] text-white/50 leading-relaxed group-hover:text-white/70 transition-colors">Always-in-stock policy for key industrial solvents.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- MARKET INSIGHTS -->
    <section data-section-id="{{ $sections->where('section_key', 'home_insights')->first()->id ?? '118' }}"
        data-section-key="home_insights" data-section-label="Latest Insights"
        class="bg-white py-[100px] px-4 md:px-8 @auth cms-editable @endauth" data-cms-label="Edit Insights Preview">
        <div class="max-w-[1400px] mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16">
                <div>
 <span class="font-sans font-bold text-[10px] uppercase tracking-[2px] text-teal block mb-4 sr-up">Intelligence</span>
<h2 class="font-display font-extrabold text-[40px] md:text-[56px] leading-tight text-ink mb-2">Market <em class="italic text-teal font-normal">Insights</em></h2>
                </div>
                <a href="{{ route('insights.index') }}"
                    class="font-mono text-[11px] text-teal hover:text-ink uppercase tracking-wider transition-colors mt-6 md:mt-0">All
                    Insights →</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-[1.5fr_1fr_1fr] gap-6">
                @foreach($recentPosts as $idx => $post)
                    <a href="{{ route('insights.show', $post->slug) }}" class="group block sr-drop"
                        data-delay="{{ $idx * 120 }}">
                        <div class="relative overflow-hidden aspect-[16/10] mb-6 rounded-sm">
                            <img src="{{ $post->featured_image ?? asset('assets/images/insight-default.jpg') }}"
                                alt="{{ $post->title }}"
                                class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700"
                                onerror="this.style.display='none'">
                            <div class="absolute inset-0 border border-ink/10"></div>
                        </div>
                        <div class="flex items-center space-x-4 mb-4">
                            <span
                                class="font-mono text-[10px] uppercase tracking-wider text-teal bg-teal-light px-2 py-1">{{ $post->category }}</span>
                            <span
                                class="font-serif italic text-[11px] text-muted">{{ $post->published_at->format('M d, Y') }}</span>
                        </div>
                        <h3 class="font-display font-bold text-xl group-hover:text-teal transition-colors mb-3">
                            {{ $post->title }}</h3>
                        <p class="font-serif text-[13px] text-muted line-clamp-2">{{ $post->excerpt }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CONTACT SECTION -->
    <section data-section-id="{{ $sections->where('section_key', 'home_contact')->first()->id ?? '119' }}"
        data-section-key="home_contact" data-section-label="Home CTA"
        class="bg-bg py-[100px] px-4 md:px-8 border-t border-line @auth cms-editable @endauth"
        data-cms-label="Edit Home CTA">
        <div class="max-w-[1400px] mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16">
            <div class="relative bg-ink rounded-sm overflow-hidden p-8 lg:p-12 text-white">
                <img src="{{ asset('assets/images/contact-bg.jpg') }}" alt="Contact Us"
                    class="absolute inset-0 w-full h-full object-cover opacity-[0.15] filter grayscale blur-sm">
                <div class="absolute inset-0 bg-gradient-to-br from-ink via-ink/90 to-transparent"></div>

                <div class="relative z-10">
                    <span
                        class="font-sans font-semibold text-[10px] uppercase tracking-widest text-[#a8d5cf] block mb-4 sr-l">Contact
                        Us</span>
                    <h2
                        class="font-display font-medium text-[40px] md:text-[48px] leading-tight text-white mb-6 sr-l data-delay-100">
                        Let's Talk<br>Business</h2>
                    <p class="font-sans text-[15px] text-[#a8d5cf] mb-12 max-w-sm sr-l data-delay-200">
                        Looking for reliable bulk supply? Drop us a message with your requirements, and our sales team will
                        respond within 24 hours.
                    </p>

                    <ul class="space-y-8 sr-l data-delay-300 block">
                        <li class="flex items-start space-x-5">
                            <div
                                class="w-12 h-12 rounded-full border border-white/20 bg-white/5 backdrop-blur-md flex items-center justify-center text-[#a8d5cf]">
                                &#x1F4CD;</div>
                            <div class="flex-1">
                                <span
                                    class="block font-sans font-semibold text-xs uppercase tracking-wider mb-2 text-white/50">Corporate
                                    Office</span>
                                <p data-element-id="el_setting:office_address"
                                    class="font-sans text-[14px] text-white leading-relaxed">
                                    {{ $globalSettings['office_address'] ?? 'Ahmedabad, Gujarat' }}</p>
                            </div>
                        </li>
                        <li class="flex items-start space-x-5">
                            <div
                                class="w-12 h-12 rounded-full border border-white/20 bg-white/5 backdrop-blur-md flex items-center justify-center text-[#a8d5cf]">
                                &#x1F4DE;</div>
                            <div class="flex-1">
                                <span
                                    class="block font-sans font-semibold text-xs uppercase tracking-wider mb-2 text-white/50">Primary
                                    Line</span>
                                <a data-element-id="el_setting:phone_primary"
                                    href="tel:{{ $globalSettings['phone_primary'] }}"
                                    class="font-sans text-[14px] text-white hover:text-[#a8d5cf] transition-colors">{{ $globalSettings['phone_primary'] ?? '+91' }}</a>
                            </div>
                        </li>
                        <li class="flex items-start space-x-5">
                            <div
                                class="w-12 h-12 rounded-full border border-white/20 bg-white/5 backdrop-blur-md flex items-center justify-center text-[#a8d5cf]">
                                &#x2709;&#xFE0F;</div>
                            <div class="flex-1">
                                <span
                                    class="block font-sans font-semibold text-xs uppercase tracking-wider mb-2 text-white/50">Sales
                                    Email</span>
                                <a data-element-id="el_setting:email_sales"
                                    href="mailto:{{ $globalSettings['email_sales'] }}"
                                    class="font-sans text-[14px] text-white hover:text-[#a8d5cf] transition-colors">{{
                                    $globalSettings['email_sales'] ?? 'sales@auxinorchem.com' }}</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div id="enquiry-form" class="bg-white border border-line p-8 md:p-11 shadow-sm sr-up data-delay-200">
                <h3 class="font-display font-bold text-xl mb-8 border-b border-line pb-4">Bulk Enquiry Form</h3>
                <form x-data="enquiryForm()" @submit.prevent="submitForm">
                    @csrf
                    <div x-show="message"
                        :class="success ? 'bg-teal-light text-teal border border-teal' : 'bg-red-50 text-red-600 border border-red-200'"
                        class="p-4 mb-6 font-mono text-[11px] uppercase tracking-wider" style="display: none;"
                        x-text="message"></div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block font-mono text-[10px] text-muted uppercase tracking-wider mb-2">Company
                                Name</label>
                            <input type="text" x-model="formData.company_name"
                                class="w-full border-b border-line pb-2 focus:outline-none focus:border-teal bg-transparent font-serif text-[14px]">
                        </div>
                        <div>
                            <label class="block font-mono text-[10px] text-muted uppercase tracking-wider mb-2">Contact
                                Person *</label>
                            <input type="text" required x-model="formData.contact_person"
                                class="w-full border-b border-line pb-2 focus:outline-none focus:border-teal bg-transparent font-serif text-[14px]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block font-mono text-[10px] text-muted uppercase tracking-wider mb-2">Email
                                *</label>
                            <input type="email" required x-model="formData.email"
                                class="w-full border-b border-line pb-2 focus:outline-none focus:border-teal bg-transparent font-serif text-[14px]">
                        </div>
                        <div>
                            <label
                                class="block font-mono text-[10px] text-muted uppercase tracking-wider mb-2">Phone</label>
                            <input type="tel" x-model="formData.phone"
                                class="w-full border-b border-line pb-2 focus:outline-none focus:border-teal bg-transparent font-serif text-[14px]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block font-mono text-[10px] text-muted uppercase tracking-wider mb-2">Product
                                Category</label>
                            <select x-model="formData.product_category"
                                class="w-full border-b border-line pb-2 focus:outline-none focus:border-teal bg-transparent font-serif text-[14px]">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-mono text-[10px] text-muted uppercase tracking-wider mb-2">Product
                                Name/Quantity</label>
                            <input type="text" x-model="formData.quantity"
                                class="w-full border-b border-line pb-2 focus:outline-none focus:border-teal bg-transparent font-serif text-[14px]">
                        </div>
                    </div>

                    <div class="mb-10">
                        <label class="block font-mono text-[10px] text-muted uppercase tracking-wider mb-2">Requirements
                            *</label>
                        <textarea required x-model="formData.message" rows="3"
                            class="w-full border-b border-line pb-2 focus:outline-none focus:border-teal bg-transparent font-serif text-[14px] resize-none"></textarea>
                    </div>

                    <button type="submit"
                        class="btn-primary w-full py-4 font-display font-bold text-[11px] uppercase tracking-widest flex justify-center items-center"
                        :disabled="loading">
                        <span x-show="!loading">Send Enquiry</span>
                        <span x-show="loading">Sending...</span>
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
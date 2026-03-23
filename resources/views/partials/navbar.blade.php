<nav id="navbar" 
     @if(isset($navbarSection))
        data-section-id="{{ $navbarSection->id }}" 
        data-section-key="navbar" 
        data-section-label="Global Navbar"
        style="{{ $navbarSection->getStyleString() }}"
     @endif
     class="bg-white border-b border-line sticky top-0 z-[200] transition-shadow duration-300" style="height: 74px;" x-data="{ mobileMenuOpen: false, productsOpen: false }" @scroll.window="$el.classList.toggle('shadow-md', window.scrollY > 60)">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8 h-full flex justify-between items-center">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
            <div class="w-[44px] h-[44px] bg-ink flex flex-col justify-end overflow-hidden relative">
                <div data-element-id="navbar_logo_text" data-element-type="text" class="absolute inset-0 flex items-center justify-center font-mono text-teal-2 text-lg pt-1 group-hover:scale-110 transition-transform">AC</div>
                <div class="h-[3px] bg-teal-2 w-full mt-auto"></div>
            </div>
            <div class="flex flex-col">
                <span data-element-id="navbar_brand_name" data-element-type="text" class="font-display font-bold text-sm leading-tight text-ink uppercase tracking-wider">AUXINOR</span>
                <span data-element-id="navbar_brand_suffix" data-element-type="text" class="font-mono text-[10px] text-muted">Chemicals LLP</span>
            </div>
        </a>

        <!-- Desktop Links -->
        <div class="hidden lg:flex items-center space-x-8">
            @php
                $navLinks = [
                    'Home' => route('home'),
                    'About' => route('about'),
                    'Industries' => route('industries'),
                    'Infrastructure' => route('infrastructure'),
                    'Insights' => route('insights.index'),
                    'Contact' => route('contact'),
                ];
            @endphp
            
            <a href="{{ route('home') }}" class="font-display text-[12px] font-bold tracking-[1.5px] uppercase relative group py-2 {{ request()->routeIs('home') ? 'text-teal' : 'text-ink' }}">
                Home
                <span class="absolute bottom-0 left-0 h-[2px] bg-teal transition-all duration-300 {{ request()->routeIs('home') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
            </a>
            
            <a href="{{ route('about') }}" class="font-display text-[12px] font-bold tracking-[1.5px] uppercase relative group py-2 {{ request()->routeIs('about') ? 'text-teal' : 'text-ink' }}">
                About
                <span class="absolute bottom-0 left-0 h-[2px] bg-teal transition-all duration-300 {{ request()->routeIs('about') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
            </a>

            <!-- Products Dropdown -->
            <div class="relative py-2" @mouseenter="productsOpen = true" @mouseleave="productsOpen = false">
                <a href="{{ route('products.index') }}" class="font-display text-[12px] font-bold tracking-[1.5px] uppercase relative group flex items-center {{ request()->routeIs('products.*') ? 'text-teal' : 'text-ink' }}">
                    Products
                    <svg class="w-3 h-3 ml-1 transition-transform" :class="productsOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    <span class="absolute bottom-0 left-0 h-[2px] bg-teal transition-all duration-300 {{ request()->routeIs('products.*') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </a>
                
                <div x-show="productsOpen" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-2"
                     class="absolute top-full left-0 mt-0 w-64 bg-white border border-line shadow-xl py-2 z-50 pointer-events-auto"
                     style="display: none;">
                     <a href="{{ route('products.index') }}" class="block px-4 py-2 text-xs font-display uppercase tracking-wider hover:bg-bg hover:text-teal border-b border-line mb-1">All Products</a>
                     @foreach($globalCategories ?? [] as $category)
                        @php
                            if (is_object($category)) {
                                $slug = $category->slug ?? '';
                                $name = $category->name ?? $category->slug ?? '';
                                $icon = $category->icon ?? '';
                            } elseif (is_array($category)) {
                                $slug = $category['slug'] ?? '';
                                $name = $category['name'] ?? $category['slug'] ?? '';
                                $icon = $category['icon'] ?? '';
                            } else {
                                $slug = (string) $category;
                                $name = (string) $category;
                                $icon = '';
                            }
                        @endphp
                        @if($slug)
                          <a href="{{ route('products.category', $slug) }}" class="block px-4 py-2 text-xs font-display uppercase tracking-wider hover:bg-bg hover:text-teal">{{ $icon }} {{ $name }}</a>
                        @endif
                     @endforeach
                </div>
            </div>

            <a href="{{ route('industries') }}" class="font-display text-[12px] font-bold tracking-[1.5px] uppercase relative group py-2 {{ request()->routeIs('industries') ? 'text-teal' : 'text-ink' }}">
                Industries
                <span class="absolute bottom-0 left-0 h-[2px] bg-teal transition-all duration-300 {{ request()->routeIs('industries') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
            </a>
            
            <a href="{{ route('infrastructure') }}" class="font-display text-[12px] font-bold tracking-[1.5px] uppercase relative group py-2 {{ request()->routeIs('infrastructure') ? 'text-teal' : 'text-ink' }}">
                Infrastructure
                <span class="absolute bottom-0 left-0 h-[2px] bg-teal transition-all duration-300 {{ request()->routeIs('infrastructure') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
            </a>
            
            <a href="{{ route('insights.index') }}" class="font-display text-[12px] font-bold tracking-[1.5px] uppercase relative group py-2 {{ request()->routeIs('insights.*') ? 'text-teal' : 'text-ink' }}">
                Insights
                <span class="absolute bottom-0 left-0 h-[2px] bg-teal transition-all duration-300 {{ request()->routeIs('insights.*') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
            </a>
            
            <a href="{{ route('contact') }}" class="font-display text-[12px] font-bold tracking-[1.5px] uppercase relative group py-2 {{ request()->routeIs('contact') ? 'text-teal' : 'text-ink' }}">
                Contact
                <span class="absolute bottom-0 left-0 h-[2px] bg-teal transition-all duration-300 {{ request()->routeIs('contact') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
            </a>
        </div>

        <!-- CTA -->
        <div class="hidden lg:block">
            <a href="{{ route('contact') }}#enquiry-form" 
               data-element-id="navbar_cta" 
               data-element-type="button" 
               class="btn-primary nav-quote px-6 py-3 font-display text-[11px] font-bold uppercase tracking-[1.5px]">Get a Quote</a>
        </div>
        
        <!-- Mobile Menu Button -->
        <button class="lg:hidden text-ink p-2" @click="mobileMenuOpen = !mobileMenuOpen">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
    </div>
    
    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" class="lg:hidden absolute top-[74px] left-0 w-full bg-white border-b border-line shadow-lg" style="display: none;">
        <div class="p-4 flex flex-col space-y-4 font-display font-bold uppercase text-sm">
            <a href="{{ route('home') }}" class="v-link pb-2 border-b border-line">Home</a>
            <a href="{{ route('about') }}" class="v-link pb-2 border-b border-line">About</a>
            <a href="{{ route('products.index') }}" class="v-link pb-2 border-b border-line text-teal">Products</a>
            <a href="{{ route('industries') }}" class="v-link pb-2 border-b border-line">Industries</a>
            <a href="{{ route('infrastructure') }}" class="v-link pb-2 border-b border-line">Infrastructure</a>
            <a href="{{ route('insights.index') }}" class="v-link pb-2 border-b border-line">Insights</a>
            <a href="{{ route('contact') }}" class="v-link pb-2">Contact</a>
        </div>
    </div>
</nav>

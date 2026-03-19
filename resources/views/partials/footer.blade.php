<footer class="bg-ink text-white pt-20 pb-6 border-t-[4px] border-teal">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
            <!-- Brand -->
            <div>
                <a href="{{ route('home') }}" class="flex items-center space-x-3 mb-6">
                    <div class="w-[44px] h-[44px] bg-black flex flex-col justify-end overflow-hidden relative border border-white/10">
                        <div class="absolute inset-0 flex items-center justify-center font-mono text-teal-2 text-lg pt-1">AC</div>
                        <div class="h-[3px] bg-teal-2 w-full mt-auto"></div>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-display font-bold text-sm leading-tight text-white uppercase tracking-wider">AUXINOR</span>
                        <span class="font-mono text-[10px] text-muted">Chemicals LLP</span>
                    </div>
                </a>
                <p class="font-serif italic text-[13px] text-white/60 mb-6 leading-relaxed">
                    {{ $globalSettings['footer_tagline'] ?? "Chemical trading, distribution, and market intelligence — serving India's industrial sector with trust, accountability, and quality." }}
                </p>
                <div class="flex space-x-4">
                    <a href="{{ $globalSettings['facebook_url'] ?? '#' }}" class="w-8 h-8 rounded border border-white/10 flex items-center justify-center text-white/60 hover:text-teal hover:border-teal transition-colors">FB</a>
                    <a href="{{ $globalSettings['linkedin_url'] ?? '#' }}" class="w-8 h-8 rounded border border-white/10 flex items-center justify-center text-white/60 hover:text-teal hover:border-teal transition-colors">IN</a>
                </div>
            </div>

            <!-- Products -->
            <div>
                <h4 class="font-display font-bold uppercase text-[12px] tracking-wider mb-6 text-teal-light">Core Categories</h4>
                <ul class="space-y-3">
                    @foreach(($globalCategories ?? [])->take(5) as $cat)
                        <li><a href="{{ route('products.category', $cat->slug) }}" class="font-mono text-[11px] text-white/60 hover:text-teal-2 transition-colors">{{ $cat->name }}</a></li>
                    @endforeach
                    <li><a href="{{ route('products.index') }}" class="font-mono text-[11px] text-teal hover:text-white transition-colors mt-2 inline-block">View All Products →</a></li>
                </ul>
            </div>

            <!-- Company -->
            <div>
                <h4 class="font-display font-bold uppercase text-[12px] tracking-wider mb-6 text-teal-light">Company</h4>
                <ul class="space-y-3 font-mono text-[11px]">
                    <li><a href="{{ route('about') }}" class="text-white/60 hover:text-teal-2 transition-colors">About Us</a></li>
                    <li><a href="{{ route('infrastructure') }}" class="text-white/60 hover:text-teal-2 transition-colors">Infrastructure & Logistics</a></li>
                    <li><a href="{{ route('industries') }}" class="text-white/60 hover:text-teal-2 transition-colors">Industries Served</a></li>
                    <li><a href="{{ route('insights.index') }}" class="text-white/60 hover:text-teal-2 transition-colors">Market Insights</a></li>
                    <li><a href="{{ route('contact') }}" class="text-white/60 hover:text-teal-2 transition-colors">Contact Information</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="font-display font-bold uppercase text-[12px] tracking-wider mb-6 text-teal-light">Get in Touch</h4>
                <div class="space-y-4">
                    <div>
                        <span class="block font-mono text-[9px] uppercase tracking-wider text-teal-2 mb-1">Corporate Office</span>
                        <p class="font-serif text-[13px] text-white/60">{{ $globalSettings['office_address'] ?? 'Ahmedabad, Gujarat' }}</p>
                    </div>
                    <div>
                        <span class="block font-mono text-[9px] uppercase tracking-wider text-teal-2 mb-1">Call Us</span>
                        <a href="tel:{{ $globalSettings['phone_primary'] ?? '' }}" class="font-mono text-[12px] text-white hover:text-teal-2 transition-colors block">{{ $globalSettings['phone_primary'] ?? '+91' }}</a>
                    </div>
                    <div>
                        <span class="block font-mono text-[9px] uppercase tracking-wider text-teal-2 mb-1">Email</span>
                        <a href="mailto:{{ $globalSettings['email_sales'] ?? '' }}" class="font-mono text-[12px] text-white hover:text-teal-2 transition-colors block">{{ $globalSettings['email_sales'] ?? 'sales@auxinorchem.com' }}</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center">
            <p class="font-mono text-[10px] text-white/40 mb-4 md:mb-0">
                &copy; {{ date('Y') }} {{ $globalSettings['company_name'] ?? 'Auxinor Chemicals LLP' }}. All rights reserved.
            </p>
            <p class="font-display text-[10px] uppercase tracking-widest text-white/30">
                Designed for B2B Chemical Trading
            </p>
        </div>
    </div>
</footer>

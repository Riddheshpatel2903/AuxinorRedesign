@extends('layouts.app')

@section('title', 'Product Catalogue | ' . ($globalSettings['company_name'] ?? 'Auxinor Chemicals'))

@section('content')
<!-- Page Header -->
<div class="bg-bg border-b border-line py-12 px-4 md:px-8">
    <div class="max-w-[1400px] mx-auto flex flex-col md:flex-row md:items-end justify-between">
        <div>
            <div class="font-mono text-[10px] uppercase tracking-widest text-teal mb-3">
                <a href="{{ route('home') }}" class="hover:text-ink transition-colors">Home</a>
                <span class="mx-2 text-muted">/</span>
                <span class="text-ink">Products</span>
            </div>
            <h1 class="font-display font-extrabold text-[36px] md:text-[48px] leading-none mb-4 text-ink">Product <em class="font-serif italic font-normal text-teal">Catalogue</em></h1>
            <p class="font-serif text-[14px] text-muted max-w-xl">Browse our complete range of industrial chemicals, encompassing bulk solvents, monomers, and specialized formulations.</p>
        </div>
        
        <!-- Search -->
        <div class="mt-8 md:mt-0 w-full md:w-auto">
            <form action="{{ route('products.index') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products, CAS..." class="w-full md:w-72 bg-white border border-line py-3 px-4 text-sm font-serif focus:outline-none focus:border-teal transition-colors">
                <button type="submit" class="absolute right-0 top-0 bottom-0 px-4 text-teal hover:text-ink transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
        </div>
    </div>
</div>

<section class="py-16 px-4 md:px-8">
    <div class="max-w-[1400px] mx-auto grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-12">
        
        <!-- Sidebar -->
        <aside class="hidden lg:block sticky top-28 self-start">
            <h3 class="font-display font-bold text-[13px] uppercase tracking-wider mb-6 pb-4 border-b border-line text-ink">Categories</h3>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('products.index') }}" class="flex items-center justify-between font-mono text-[11px] uppercase tracking-wider py-2 {{ !request()->routeIs('products.category') ? 'text-teal font-bold' : 'text-muted hover:text-ink' }}">
                        <span>All Products</span>
                        <span>{{ $allProducts->total() }}</span>
                    </a>
                </li>
                @foreach($categories as $cat)
                <li>
                    <a href="{{ route('products.category', $cat->slug) }}" class="flex items-center justify-between font-mono text-[11px] uppercase tracking-wider py-2 {{ request()->segment(2) === $cat->slug ? 'text-teal font-bold' : 'text-muted hover:text-ink' }} transition-colors">
                        <span>{{ $cat->name }}</span>
                        <span>{{ $cat->products->count() }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
        </aside>

        <!-- Main Content -->
        <div>
            @if($allProducts->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12 sr-stagger">
                    @php
                        $imgFallback = [
                            asset('assets/images/product-1.jpg'), // Blue drums
                            asset('assets/images/product-2.jpg'), // IBC Totes
                            asset('assets/images/product-3.jpg'), // Storage tanks
                            asset('assets/images/product-4.jpg'), // Metal barrels
                            asset('assets/images/product-5.jpg'), // White jerrycans
                            asset('assets/images/product-6.jpg')  // Pallets of chemicals
                        ];
                    @endphp
                    @foreach($allProducts as $idx => $product)
                    <a href="{{ route('products.show', ['category' => $product->category->slug ?? 'misc', 'slug' => $product->slug]) }}" class="group block relative rounded-sm overflow-hidden h-72 border border-line bg-white shadow-sm hover:shadow-lg transition-all duration-500 sr-up" data-delay="{{ ($idx % 3) * 100 }}">
                        
                        <!-- Premium Background Image -->
                        <div class="absolute inset-0 z-0">
                            <img src="{{ $imgFallback[$idx % count($imgFallback)] }}" class="w-full h-full object-cover filter brightness-[0.8] group-hover:brightness-100 group-hover:scale-105 transition-all duration-700" alt="Chemical container">
                            <div class="absolute inset-0 bg-gradient-to-t from-ink/90 via-ink/50 to-transparent"></div>
                        </div>

                        <!-- Content Overlay -->
                        <div class="relative z-10 h-full p-6 flex flex-col justify-between">
                            <div class="self-end bg-white/10 backdrop-blur-md px-3 py-1 rounded-sm border border-white/20">
                                <span class="font-display text-[16px] text-white font-medium tracking-wider">{{ $product->chemical_formula ?? $product->name[0] }}</span>
                            </div>
                            
                            <div>
                                <span class="font-mono text-[9px] uppercase tracking-widest text-teal-light/80 mb-2 block">{{ $product->cas_number ? 'CAS ' . $product->cas_number : 'NO CAS' }}</span>
                                <h3 class="font-display font-semibold text-[18px] text-white leading-tight mb-2 group-hover:text-teal-light transition-colors">{{ $product->name }}</h3>
                                <span class="font-serif italic text-[11px] text-white/70 line-clamp-2">{{ $product->short_description }}</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="border-t border-line pt-8">
                    {{ $allProducts->withQueryString()->links('pagination::tailwind') }}
                </div>
            @else
                <div class="bg-bg border border-line border-dashed p-16 text-center src-up">
                    <div class="text-4xl mb-4">🔍</div>
                    <h3 class="font-display font-bold text-xl mb-2 text-ink">No products found for "{{ request('search') }}"</h3>
                    <p class="font-serif text-[14px] text-muted mb-6">Try adjusting your search terms or browse our categories.</p>
                    <a href="{{ route('products.index') }}" class="font-mono text-[11px] uppercase tracking-wider text-teal hover:text-ink transition-colors pb-1 border-b border-teal">Clear Search</a>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

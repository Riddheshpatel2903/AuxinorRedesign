@extends('layouts.app')

@section('title', $category->name . ' | Products | ' . ($globalSettings['company_name'] ?? 'Auxinor Chemicals'))

@section('content')
<!-- Category Hero -->
<div class="bg-ink border-b-4 border-teal py-16 px-4 md:px-8 text-white relative overflow-hidden">
    <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-gradient-to-l from-white/5 to-transparent pointer-events-none"></div>
    <div class="max-w-[1400px] mx-auto relative z-10 flex flex-col md:flex-row items-center md:items-end justify-between">
        <div>
            <div class="font-mono text-[10px] uppercase tracking-widest text-teal-2 mb-4">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                <span class="mx-2 text-white/30">/</span>
                <a href="{{ route('products.index') }}" class="hover:text-white transition-colors">Products</a>
                <span class="mx-2 text-white/30">/</span>
                <span class="text-white">{{ $category->name }}</span>
            </div>
            <div class="flex items-center space-x-6">
                @if($category->icon)
                <div class="w-16 h-16 bg-white/10 flex items-center justify-center text-3xl border border-white/20">
                    {{ $category->icon }}
                </div>
                @endif
                <div>
                    <h1 class="font-display font-extrabold text-[36px] md:text-[48px] leading-none mb-2">{{ $category->name }}</h1>
                    <p class="font-serif italic text-[14px] text-white/60 max-w-xl">{{ $category->description ?? 'Browse our complete range of ' . $category->name . ' available in bulk shipments.' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="py-16 px-4 md:px-8 bg-white">
    <div class="max-w-[1400px] mx-auto grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-12">
        
        <!-- Sidebar -->
        <aside class="hidden lg:block sticky top-28 self-start">
            <h3 class="font-display font-bold text-[13px] uppercase tracking-wider mb-6 pb-4 border-b border-line text-ink">Categories</h3>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('products.index') }}" class="flex items-center justify-between font-mono text-[11px] uppercase tracking-wider py-2 text-muted hover:text-ink transition-colors">
                        <span>All Products</span>
                    </a>
                </li>
                @foreach($categories as $cat)
                <li>
                    <a href="{{ route('products.category', $cat->slug) }}" class="flex items-center justify-between font-mono text-[11px] uppercase tracking-wider py-2 {{ request()->segment(2) === $cat->slug ? 'text-teal font-bold' : 'text-muted hover:text-ink' }} transition-colors">
                        <span>{{ $cat->name }}</span>
                        <span>{{ $cat->products()->count() }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
        </aside>

        <!-- Main Content -->
        <div>
            <!-- Search bar inline for category -->
            <form action="{{ route('products.category', $category->slug) }}" method="GET" class="relative mb-8">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search in {{ $category->name }}..." class="w-full bg-bg border border-line py-3 px-4 text-sm font-serif focus:outline-none focus:border-teal transition-colors">
                <button type="submit" class="absolute right-0 top-0 bottom-0 px-4 text-teal hover:text-ink transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>

            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12 sr-stagger">
                    @php
                        $imgFallback = [
                            asset('assets/images/product-1.jpg'),
                            asset('assets/images/product-2.jpg'),
                            asset('assets/images/product-3.jpg'),
                            asset('assets/images/product-4.jpg'),
                            asset('assets/images/product-5.jpg'),
                            asset('assets/images/product-6.jpg')
                        ];
                    @endphp
                    @foreach($products as $idx => $product)
                    <a href="{{ route('products.show', ['category' => $category->slug, 'slug' => $product->slug]) }}" class="group block relative rounded-sm overflow-hidden h-72 border border-line bg-white shadow-sm hover:shadow-lg transition-all duration-500 sr-up" data-delay="{{ ($idx % 3) * 100 }}">
                        
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
                                <span class="font-serif italic text-[11px] text-white/70 line-clamp-2">{{ $product->short_description ?? 'High-grade chemical blend' }}</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                
                <div class="border-t border-line pt-8">
                    {{ $products->withQueryString()->links('pagination::tailwind') }}
                </div>
            @else
                <div class="bg-bg border border-line border-dashed p-16 text-center">
                    <p class="font-serif text-[14px] text-muted">No products found in this category matching your criteria.</p>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

@extends('layouts.app')

@section('title', $product->name . ' | Products | ' . ($globalSettings['company_name'] ?? 'Auxinor Chemicals'))

@section('content')
<!-- Breadcrumbs -->
<div class="bg-bg border-b border-line py-4 px-4 md:px-8">
    <div class="max-w-[1400px] mx-auto font-mono text-[10px] uppercase tracking-widest text-muted">
        <a href="{{ route('home') }}" class="hover:text-teal transition-colors">Home</a>
        <span class="mx-2">/</span>
        <a href="{{ route('products.index') }}" class="hover:text-teal transition-colors">Products</a>
        <span class="mx-2">/</span>
        <a href="{{ route('products.category', $product->category->slug) }}" class="hover:text-teal transition-colors">{{ $product->category->name }}</a>
        <span class="mx-2">/</span>
        <span class="text-ink font-bold">{{ $product->name }}</span>
    </div>
</div>

<section class="py-16 px-4 md:px-8 bg-white" x-data="{ activeTab: 'overview' }">
    <div class="max-w-[1400px] mx-auto grid grid-cols-1 lg:grid-cols-[2fr_1fr] gap-16">
        
        <!-- Left: Product Details -->
        <div>
            <!-- Header -->
            <div class="mb-12 border-b border-line pb-8">
                <div class="flex flex-wrap items-center gap-3 mb-6">
                    @if($product->cas_number)
                        <span class="border border-teal/30 bg-teal-light text-teal font-mono text-[10px] uppercase tracking-wider px-3 py-1">CAS: {{ $product->cas_number }}</span>
                    @endif
                    @if($product->chemical_formula)
                        <span class="bg-bg border border-line text-ink font-mono text-[10px] uppercase tracking-wider px-3 py-1">Formula: {{ $product->chemical_formula }}</span>
                    @endif
                </div>
                
                <h1 class="font-display font-extrabold text-[40px] md:text-[56px] leading-tight text-ink mb-4">{{ $product->name }}</h1>
                <p class="font-serif italic text-[16px] text-muted leading-relaxed max-w-2xl">{{ $product->short_description ?? 'High-purity industrial grade compound suitable for bulk manufacturing and specialized applications.' }}</p>
            </div>
            
            <!-- Gallery Section -->
            @if($product->image || ($product->gallery && is_array($product->gallery) && count($product->gallery) > 0))
            <div class="mb-12">
                @if($product->image)
                <div class="mb-4 overflow-hidden bg-bg border border-line aspect-[21/9]">
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                </div>
                @endif
                
                @if($product->gallery && is_array($product->gallery) && count($product->gallery) > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($product->gallery as $galImg)
                    <div class="overflow-hidden bg-bg border border-line aspect-square group">
                        <img src="{{ Storage::url($galImg) }}" alt="{{ $product->name }} gallery" class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endif
            
            <!-- Tabs -->
            <div class="flex border-b border-line mb-8 overflow-x-auto">
                <button @click="activeTab = 'overview'" class="font-display font-bold text-[12px] uppercase tracking-wider px-6 py-4 border-b-2 transition-colors whitespace-nowrap" :class="activeTab === 'overview' ? 'border-teal text-teal' : 'border-transparent text-muted hover:text-ink'">Overview</button>
                <button @click="activeTab = 'applications'" class="font-display font-bold text-[12px] uppercase tracking-wider px-6 py-4 border-b-2 transition-colors whitespace-nowrap" :class="activeTab === 'applications' ? 'border-teal text-teal' : 'border-transparent text-muted hover:text-ink'">Applications</button>
                <button @click="activeTab = 'specifications'" class="font-display font-bold text-[12px] uppercase tracking-wider px-6 py-4 border-b-2 transition-colors whitespace-nowrap" :class="activeTab === 'specifications' ? 'border-teal text-teal' : 'border-transparent text-muted hover:text-ink'">Specifications</button>
            </div>
            
            <!-- Tab Content -->
            <div class="min-h-[300px]">
                <!-- Overview -->
                <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="prose prose-sm max-w-none font-serif text-[#4a6080] leading-relaxed">
                    <p>{{ $product->description ?? 'Detailed description is currently unavailable for this product. Please contact our technical team for a comprehensive material safety data sheet (MSDS) and technical data sheet (TDS).' }}</p>
                </div>
                
                <!-- Applications -->
                <div x-show="activeTab === 'applications'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <ul class="space-y-4">
                        @forelse($product->applications_list as $app)
                            <li class="flex items-start space-x-3 bg-bg p-4 border border-line">
                                <span class="text-teal font-display font-bold">✓</span>
                                <span class="font-serif text-[14px] text-ink">{{ $app }}</span>
                            </li>
                        @empty
                            <div class="bg-bg p-6 text-center border text-muted font-serif text-[14px]">Specific applications are not listed. Often used in {{ $product->category->name }} derivatives.</div>
                        @endforelse
                    </ul>
                </div>
                
                <!-- Specifications -->
                <div x-show="activeTab === 'specifications'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    @if($product->specifications && is_array($product->specifications) && count($product->specifications) > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse border border-line">
                                <tbody>
                                    @foreach($product->specifications as $spec)
                                    <tr class="border-b border-line hover:bg-bg transition-colors">
                                        <th class="p-4 font-mono text-[11px] uppercase tracking-wider text-muted bg-white/50 w-1/3">{{ $spec['key'] ?? '' }}</th>
                                        <td class="p-4 font-serif text-[14px] text-ink border-l border-line">{{ $spec['value'] ?? '' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-bg p-6 text-center border text-muted font-serif text-[14px]">Technical specifications available upon request.</div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Right: Sticky Enquiry Form -->
        <div class="relative">
            <div class="sticky top-28 bg-ink text-white shadow-xl border-t-4 border-teal overflow-hidden">
                <div class="absolute right-0 top-0 opacity-10 pointer-events-none transform translate-x-1/2 -translate-y-1/4">
                    <span class="font-mono text-[120px] font-bold">{{ $product->name[0] ?? '' }}</span>
                </div>
                
                <div class="p-8 relative z-10">
                    <h3 class="font-display font-bold text-xl mb-2">Request Quote</h3>
                    <p class="font-mono text-[10px] text-white/50 uppercase tracking-wider mb-8 border-b border-white/10 pb-4">{{ $product->name }}</p>
                    
                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <input type="hidden" name="message" value="I would like to request a quote or more information regarding: {{ $product->name }} (CAS: {{ $product->cas_number }}). Please provide bulk pricing and lead times.">
                        
                        <div class="mb-5">
                            <label class="block font-mono text-[9px] uppercase tracking-widest text-teal-2 mb-2">Company Name</label>
                            <input type="text" name="company_name" class="w-full bg-transparent border-b border-white/20 text-white font-serif focus:border-teal outline-none transition-colors pb-1">
                        </div>
                        <div class="mb-5">
                            <label class="block font-mono text-[9px] uppercase tracking-widest text-teal-2 mb-2">Contact Person *</label>
                            <input type="text" name="contact_person" required class="w-full bg-transparent border-b border-white/20 text-white font-serif focus:border-teal outline-none transition-colors pb-1">
                        </div>
                        <div class="mb-5">
                            <label class="block font-mono text-[9px] uppercase tracking-widest text-teal-2 mb-2">Email *</label>
                            <input type="email" name="email" required class="w-full bg-transparent border-b border-white/20 text-white font-serif focus:border-teal outline-none transition-colors pb-1">
                        </div>
                        <div class="mb-8">
                            <label class="block font-mono text-[9px] uppercase tracking-widest text-teal-2 mb-2">Phone</label>
                            <input type="tel" name="phone" class="w-full bg-transparent border-b border-white/20 text-white font-serif focus:border-teal outline-none transition-colors pb-1">
                        </div>
                        
                        <button type="submit" class="w-full bg-teal hover:bg-teal-light hover:text-teal transition-colors text-white py-4 font-display font-bold text-[11px] uppercase tracking-widest">
                            Send Enquiry
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
</section>

<!-- Related Products -->
@if($related->count() > 0)
<section class="py-16 px-4 md:px-8 bg-bg border-t border-line">
    <div class="max-w-[1400px] mx-auto">
        <h3 class="font-display font-bold text-[24px] text-ink mb-8 pb-4 border-b border-line flex justify-between items-center">
            Related {{ $product->category->name }}
            <a href="{{ route('products.category', $product->category->slug) }}" class="font-mono text-[10px] uppercase tracking-wider text-teal hover:text-ink transition-colors">View All →</a>
        </h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($related as $rel)
            <a href="{{ route('products.show', ['category' => $rel->category->slug, 'slug' => $rel->slug]) }}" class="bg-white border border-line hover:border-teal group block transform transition-all hover:-translate-y-1">
                <div class="p-6">
                    <span class="font-mono text-[8px] uppercase tracking-widest text-muted block mb-2">{{ $rel->cas_number ? 'CAS ' . $rel->cas_number : 'NO CAS' }}</span>
                    <h4 class="font-display font-bold text-[14px] leading-snug group-hover:text-teal transition-colors">{{ $rel->name }}</h4>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

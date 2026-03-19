@extends('layouts.app')

@section('title', 'Market Insights | ' . ($globalSettings['company_name'] ?? 'Auxinor Chemicals'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-ink to-ink2 text-white py-16 px-4 md:px-8 border-b-4 border-teal">
    <div class="max-w-[1400px] mx-auto text-center">
        <h1 class="font-display font-extrabold text-[40px] leading-tight mb-2">Market <em class="font-serif italic text-teal-2 font-normal">Insights</em></h1>
        <p class="font-serif italic text-[15px] text-white/60 max-w-xl mx-auto mb-4">Trade recommendations, price intelligence, and internal news from the desk of Auxinor Chemicals.</p>
        <div class="font-mono text-[11px] text-white/50 space-x-2">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
            <span>/</span>
            <span class="text-teal-2">Insights</span>
        </div>
    </div>
</div>

<section class="py-20 px-4 md:px-8 bg-white" min-h-[60vh]>
    <div class="max-w-[1400px] mx-auto">
        
        @if($posts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 sr-stagger">
                @foreach($posts as $idx => $post)
                <a href="{{ route('insights.show', $post->slug) }}" class="group block sr-up" data-delay="{{ ($idx % 3) * 120 }}">
                    <!-- Image -->
                    <div class="relative overflow-hidden aspect-[16/10] mb-6 rounded-sm shadow-sm group-hover:shadow-lg transition-all duration-500">
                        <img src="{{ $post->featured_image ?? asset('assets/images/insight-default.jpg') }}" alt="{{ $post->title }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700 filter grayscale-[10%]" onerror="this.style.display='none'">
                        
                        <!-- Overlay gradient bottom -->
                        <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/60 to-transparent pointer-events-none"></div>
                        
                        <!-- Category Badge -->
                        <div class="absolute inset-0 p-4 flex items-end">
                            <span class="bg-teal text-white font-mono text-[9px] uppercase tracking-widest px-3 py-1">{{ $post->category }}</span>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="px-2">
                        <div class="flex items-center space-x-4 mb-3">
                            <span class="font-serif italic text-[11px] text-muted">{{ $post->published_at->format('M d, Y') }}</span>
                            <span class="text-line w-4 block overflow-hidden">-</span>
                            <span class="font-mono text-[10px] uppercase tracking-wider text-muted">{{ $post->author }}</span>
                        </div>
                        
                        <h2 class="font-display font-bold text-[22px] leading-snug group-hover:text-teal transition-colors mb-4">{{ $post->title }}</h2>
                        
                        <p class="font-serif text-[14px] text-[#4a6080] line-clamp-3 mb-6">
                            {{ $post->excerpt }}
                        </p>
                        
                        <span class="inline-flex items-center font-mono text-[10px] uppercase tracking-widest text-teal group-hover:text-ink transition-colors pb-1 border-b border-teal group-hover:border-ink">
                            Read Article
                            <svg class="w-3 h-3 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </span>
                    </div>
                </a>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-16 border-t border-line pt-8">
                {{ $posts->links('pagination::tailwind') }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-bg border border-line border-dashed p-20 text-center max-w-2xl mx-auto rounded-xl">
                <div class="text-4xl mb-4 text-teal/50">📰</div>
                <h3 class="font-display font-bold text-2xl mb-2 text-ink">No Insights Published Yet</h3>
                <p class="font-serif text-[15px] text-muted mb-0">Our market intelligence desk is preparing new reports. Please check back later for updates on chemical pricing and supply chain trends.</p>
            </div>
        @endif
        
    </div>
</section>
@endsection

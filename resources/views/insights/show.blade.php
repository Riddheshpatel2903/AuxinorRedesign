@extends('layouts.app')

@section('title', $post->title . ' | Market Insights | ' . ($globalSettings['company_name'] ?? 'Auxinor Chemicals'))

@section('content')
<!-- Article Header -->
<header class="bg-bg border-b border-line pt-20 pb-12 px-4 md:px-8">
    <div class="max-w-[800px] mx-auto text-center">
        <div class="mb-6 flex items-center justify-center space-x-2">
            <span class="bg-teal text-white font-mono text-[10px] uppercase tracking-widest px-3 py-1">{{ $post->category }}</span>
            <span class="text-teal/30">•</span>
            <span class="font-serif italic text-[12px] text-muted">{{ $post->published_at->format('F d, Y') }}</span>
        </div>
        
        <h1 class="font-display font-extrabold text-[36px] md:text-[52px] leading-tight text-ink mb-6">
            {{ $post->title }}
        </h1>
        
        <p class="font-serif text-[18px] text-[#4a6080] italic leading-relaxed mb-8">
            {{ $post->excerpt }}
        </p>
        
        <div class="flex items-center justify-center space-x-3 border-t border-line pt-8">
            <div class="w-10 h-10 rounded-full bg-ink text-teal-2 flex items-center justify-center font-display font-bold text-lg">
                {{ substr($post->author, 0, 1) }}
            </div>
            <div class="text-left">
                <div class="font-display font-bold text-[13px] text-ink">{{ $post->author }}</div>
                <div class="font-mono text-[9px] uppercase tracking-wider text-muted">Author</div>
            </div>
        </div>
    </div>
</header>

<!-- Featured Image -->
@if($post->featured_image)
<div class="max-w-[1000px] mx-auto px-4 md:px-8 -mt-6 relative z-10 w-full">
    <div class="aspect-[21/9] w-full overflow-hidden shadow-xl border border-line bg-white p-2">
        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover filter grayscale-[10%]" onerror="this.style.display='none'">
    </div>
</div>
@endif

<!-- Article Content -->
<article class="py-16 px-4 md:px-8 bg-white font-serif max-w-[800px] mx-auto text-[16px] md:text-[18px] leading-[1.8] text-ink relative">
    
    <!-- Share sidebar logic could go here -->
    
    <div class="prose prose-lg max-w-none prose-headings:font-display prose-headings:font-bold prose-headings:text-ink prose-a:text-teal prose-a:no-underline hover:prose-a:underline prose-p:text-[#334155]">
        {!! Str::markdown($post->content) !!}
    </div>
    
    <div class="mt-16 pt-8 border-t border-line flex justify-between items-center">
        <div class="font-mono text-[11px] uppercase tracking-widest text-muted">
            Share Article
        </div>
        <div class="flex space-x-4">
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" class="w-10 h-10 border border-line flex items-center justify-center hover:border-teal hover:text-teal transition-colors rounded-full text-muted">𝕏</a>
             <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($post->title) }}" target="_blank" class="w-10 h-10 border border-line flex items-center justify-center hover:border-teal hover:text-teal transition-colors rounded-full text-muted">in</a>
        </div>
    </div>
</article>

<!-- Related Posts -->
@if($related->count() > 0)
<section class="py-20 px-4 md:px-8 bg-bg border-t border-line">
    <div class="max-w-[1400px] mx-auto">
        <div class="text-center mb-12">
            <span class="font-mono text-[10px] uppercase tracking-widest text-teal block mb-3">Keep Reading</span>
            <h2 class="font-display font-extrabold text-[32px] text-ink">Related <em class="font-serif italic font-normal text-teal">Insights</em></h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-[1200px] mx-auto">
            @foreach($related as $rel)
            <a href="{{ route('insights.show', $rel->slug) }}" class="group block bg-white border border-line hover:border-teal transition-colors h-full flex flex-col">
                <div class="relative overflow-hidden aspect-[16/9] border-b border-line">
                    <img src="{{ $rel->featured_image ?? 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&q=80&w=600' }}" alt="{{ $rel->title }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700 filter grayscale-[10%]" onerror="this.style.display='none'">
                    <div class="absolute inset-0 p-4 flex items-start">
                        <span class="bg-teal text-white font-mono text-[8px] uppercase tracking-widest px-2 py-1">{{ $rel->category }}</span>
                    </div>
                </div>
                
                <div class="p-6 flex-grow flex flex-col">
                    <span class="font-serif italic text-[11px] text-muted mb-3">{{ $rel->published_at->format('M d, Y') }}</span>
                    <h3 class="font-display font-bold text-[18px] leading-snug group-hover:text-teal transition-colors mb-3">{{ $rel->title }}</h3>
                    <div class="mt-auto">
                        <span class="inline-flex items-center font-mono text-[9px] uppercase tracking-widest text-teal">
                            Read Article <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        
        <div class="text-center mt-12">
            <a href="{{ route('insights.index') }}" class="btn-primary px-8 py-3 font-display font-bold text-xs uppercase tracking-widest inline-block border border-ink hover:bg-bg transition-colors">View All Insights</a>
        </div>
    </div>
</section>
@endif
@endsection

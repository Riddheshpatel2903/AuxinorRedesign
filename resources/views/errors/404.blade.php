@extends('layouts.app')

@section('title', '404 — Page Not Found | Auxinor Chemicals')

@section('content')
<section class="min-h-[70vh] flex items-center justify-center bg-bg px-4">
    <div class="text-center max-w-lg mx-auto">
        <div class="font-mono text-[120px] font-bold text-teal/20 leading-none select-none">404</div>
        <h1 class="font-display font-bold text-3xl text-ink mt-4 mb-3">Page Not Found</h1>
        <p class="font-serif italic text-muted text-[15px] mb-10">
            The page you're looking for doesn't exist or has been moved.
        </p>
        <a href="{{ route('home') }}" class="btn-primary inline-flex items-center gap-2 px-8 py-3 font-display font-bold text-xs uppercase tracking-widest">
            ← Return Home
        </a>
    </div>
</section>
@endsection

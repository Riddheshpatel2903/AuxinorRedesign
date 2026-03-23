@extends('layouts.app')

@section('title', '500 — Server Error | Auxinor Chemicals')

@section('content')
<section class="min-h-[70vh] flex items-center justify-center bg-bg px-4">
    <div class="text-center max-w-lg mx-auto">
        <div class="font-mono text-[120px] font-bold text-red-400/20 leading-none select-none">500</div>
        <h1 class="font-display font-bold text-3xl text-ink mt-4 mb-3">Something Went Wrong</h1>
        <p class="font-serif italic text-muted text-[15px] mb-10">
            We're experiencing a technical issue. Please try again in a moment.
        </p>
        <a href="{{ route('home') }}" class="btn-primary inline-flex items-center gap-2 px-8 py-3 font-display font-bold text-xs uppercase tracking-widest">
            ← Return Home
        </a>
    </div>
</section>
@endsection

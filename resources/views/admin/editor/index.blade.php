@extends('admin.layouts.app')
@section('header','Visual Editor')
@section('subheader','Select a page to edit on the frontend')
@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    @foreach(['home', 'about', 'products', 'industries', 'infrastructure', 'insights', 'contact'] as $page)
    <a href="{{ route('admin.editor.page', $page) }}" class="admin-card p-6 flex flex-col justify-center min-h-[140px] group hover:border-teal transition-all hover:shadow-md rounded-sm border border-gray-200 bg-white relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-teal/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative z-10 flex justify-between items-center w-full">
            <div>
                <h3 class="font-display font-semibold text-xl text-ink group-hover:text-teal transition-colors capitalize mb-1">{{ str_replace('-',' ',$page) }}</h3>
                <p class="font-mono text-[10px] text-gray-400 uppercase tracking-widest">/{{ $page === 'home' ? '' : $page }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-teal group-hover:text-white text-gray-400 transition-colors shadow-sm border border-gray-100 group-hover:border-teal">
                <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </div>
        </div>
    </a>
    @endforeach
</div>
@endsection

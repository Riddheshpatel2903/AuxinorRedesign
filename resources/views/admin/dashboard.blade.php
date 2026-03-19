@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('header', 'System Overview')
@section('subheader', 'Quick glaces at database metrics and recent activities.')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Cards -->
    <div class="admin-card p-6 flex flex-col justify-between h-32 hover:border-teal/50 transition-colors">
        <div class="flex justify-between items-start">
            <h3 class="font-mono text-[11px] uppercase tracking-wider text-gray-500">Total Enquiries</h3>
            <svg class="w-5 h-5 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
        </div>
        <div class="flex items-end justify-between">
            <span class="font-display font-bold text-4xl text-ink leading-none">{{ \App\Models\Enquiry::count() }}</span>
            <span class="text-xs text-gray-500 font-mono">{{ \App\Models\Enquiry::where('status', 'new')->count() }} New</span>
        </div>
    </div>
    
    <div class="admin-card p-6 flex flex-col justify-between h-32 hover:border-teal/50 transition-colors">
        <div class="flex justify-between items-start">
            <h3 class="font-mono text-[11px] uppercase tracking-wider text-gray-500">Products Catalogue</h3>
            <svg class="w-5 h-5 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
        </div>
        <div class="flex items-end justify-between">
            <span class="font-display font-bold text-4xl text-ink leading-none">{{ \App\Models\Product::count() }}</span>
            <span class="text-xs text-teal font-mono">{{ \App\Models\ProductCategory::count() }} Categories</span>
        </div>
    </div>
    
    <div class="admin-card p-6 flex flex-col justify-between h-32 hover:border-teal/50 transition-colors">
        <div class="flex justify-between items-start">
            <h3 class="font-mono text-[11px] uppercase tracking-wider text-gray-500">Published Insights</h3>
            <svg class="w-5 h-5 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
        </div>
        <div class="flex items-end justify-between">
            <span class="font-display font-bold text-4xl text-ink leading-none">{{ \App\Models\BlogPost::where('is_published', true)->count() }}</span>
            <span class="text-xs text-gray-500 font-mono">Live</span>
        </div>
    </div>
    
    <div class="admin-card p-6 flex flex-col justify-between h-32 hover:border-teal/50 transition-colors">
        <div class="flex justify-between items-start">
            <h3 class="font-mono text-[11px] uppercase tracking-wider text-gray-500">System Status</h3>
            <svg class="w-5 h-5 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <div class="flex items-end justify-between">
            <span class="font-display font-bold text-2xl text-ink leading-none">Optimal</span>
            <span class="text-xs text-gray-500 font-mono">Laravel {{ app()->version() }}</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Enquiries -->
    <div class="admin-card flex flex-col">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-display font-bold text-lg text-ink">Recent Enquiries</h3>
            <a href="{{ route('admin.enquiries.index') }}" class="font-mono text-[10px] uppercase tracking-wider text-teal hover:text-teal-light transition-colors">View All</a>
        </div>
        <div class="p-0 flex-grow">
            @php
                $recentEnquiries = \App\Models\Enquiry::latest()->take(5)->get();
            @endphp
            
            @if($recentEnquiries->count() > 0)
                <ul class="divide-y divide-gray-100">
                    @foreach($recentEnquiries as $enquiry)
                        <li class="p-4 hover:bg-gray-50 transition-colors cursor-pointer" onclick="window.location='{{ route('admin.enquiries.show', $enquiry->id) }}'">
                            <div class="block">
                                <div class="flex justify-between items-start mb-1">
                                    <div class="font-bold text-ink text-[14px]">{{ $enquiry->company_name ?? $enquiry->contact_person }}</div>
                                    <span class="font-mono text-[9px] uppercase tracking-wider px-2 py-0.5 rounded {{ $enquiry->status === 'new' ? 'bg-teal/10 text-teal border border-teal/20' : 'bg-gray-100 text-gray-500' }}">{{ $enquiry->status }}</span>
                                </div>
                                <div class="flex justify-between items-center mt-2">
                                    <div class="text-[12px] text-gray-600 truncate max-w-[70%] font-serif italic">{{ $enquiry->message }}</div>
                                    <div class="font-mono text-[9px] text-gray-400">{{ $enquiry->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="p-8 text-center text-gray-400 font-serif italic text-sm">
                    No enquiries received yet.
                </div>
            @endif
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="admin-card flex flex-col">
        <div class="p-6 border-b border-gray-100">
            <h3 class="font-display font-bold text-lg text-ink">Quick Actions</h3>
        </div>
        <div class="p-6 grid grid-cols-2 gap-4">
            <a href="{{ route('admin.products.create') }}" class="bg-white border border-gray-100 rounded p-6 flex flex-col items-center justify-center hover:bg-gray-50 hover:border-teal/50 transition-all text-center group shadow-sm">
                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mb-4 group-hover:bg-teal/10 group-hover:text-teal text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <span class="font-mono text-[11px] uppercase tracking-wider text-gray-700 font-medium">Add Product</span>
            </a>
            
            <a href="{{ route('admin.posts.create') }}" class="bg-white border border-gray-100 rounded p-6 flex flex-col items-center justify-center hover:bg-gray-50 hover:border-teal/50 transition-all text-center group shadow-sm">
                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mb-4 group-hover:bg-teal/10 group-hover:text-teal text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <span class="font-mono text-[11px] uppercase tracking-wider text-gray-700 font-medium">Write Post</span>
            </a>
            
            <a href="{{ route('admin.categories.create') }}" class="bg-white border border-gray-100 rounded p-6 flex flex-col items-center justify-center hover:bg-gray-50 hover:border-teal/50 transition-all text-center group shadow-sm">
                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mb-4 group-hover:bg-teal/10 group-hover:text-teal text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                </div>
                <span class="font-mono text-[11px] uppercase tracking-wider text-gray-700 font-medium">New Category</span>
            </a>
            
            <a href="{{ route('admin.settings') }}" class="bg-white border border-gray-100 rounded p-6 flex flex-col items-center justify-center hover:bg-gray-50 hover:border-teal/50 transition-all text-center group shadow-sm">
                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mb-4 group-hover:bg-teal/10 group-hover:text-teal text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <span class="font-mono text-[11px] uppercase tracking-wider text-gray-700 font-medium">Edit Settings</span>
            </a>
        </div>
    </div>
</div>
@endsection

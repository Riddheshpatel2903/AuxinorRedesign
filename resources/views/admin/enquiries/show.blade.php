@extends('admin.layouts.app')

@section('title', 'Enquiry Details')
@section('header', 'Enquiry Details')
@section('subheader', 'Received ' . $enquiry->created_at->format('F d, Y at H:i A'))

@section('actions')
<a href="{{ route('admin.enquiries.index') }}" class="text-white/50 hover:text-white transition-colors font-mono text-[11px] uppercase tracking-wider">
    ← Back to List
</a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-[2fr_1fr] gap-8">
    
    <!-- Left Col: Enquiry Content -->
    <div class="space-y-6">
        <div class="admin-card p-6 md:p-8">
            <div class="flex justify-between items-start mb-8 pb-6 border-b border-white/10">
                <div>
                    <h3 class="font-mono text-[10px] uppercase tracking-wider text-teal-2 mb-2">Company Information</h3>
                    <h2 class="font-display font-bold text-3xl text-white">{{ $enquiry->company_name ?: 'Individual (No Company)' }}</h2>
                    <p class="font-serif italic text-[16px] text-white/70">{{ $enquiry->contact_person }}</p>
                </div>
                <div>
                    @if($enquiry->status === 'new')
                        <span class="inline-block px-3 py-1.5 bg-teal/20 text-teal-2 border border-teal/50 font-mono text-[11px] uppercase tracking-widest font-bold">New Lead</span>
                    @elseif($enquiry->status === 'contacted')
                        <span class="inline-block px-3 py-1.5 bg-blue-500/20 text-blue-300 border border-blue-500/50 font-mono text-[11px] uppercase tracking-widest font-bold">Contacted</span>
                    @else
                        <span class="inline-block px-3 py-1.5 bg-white/10 text-white/50 border border-white/20 font-mono text-[11px] uppercase tracking-widest font-bold">Closed</span>
                    @endif
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-8 mb-8">
                <div>
                    <div class="font-mono text-[10px] uppercase tracking-wider text-white/40 mb-1">Email Address</div>
                    <a href="mailto:{{ $enquiry->email }}" class="font-mono text-[14px] text-teal-2 hover:text-white transition-colors">{{ $enquiry->email }}</a>
                </div>
                <div>
                    <div class="font-mono text-[10px] uppercase tracking-wider text-white/40 mb-1">Phone Number</div>
                    @if($enquiry->phone)
                        <a href="tel:{{ $enquiry->phone }}" class="font-mono text-[14px] text-white">{{ $enquiry->phone }}</a>
                    @else
                        <span class="font-mono text-[14px] text-white/30">Not provided</span>
                    @endif
                </div>
            </div>
            
            <div class="p-6 bg-ink border border-white/5 mt-4">
                <div class="flex flex-wrap gap-4 mb-4 pb-4 border-b border-white/5">
                    @if($enquiry->product_category)
                    <div>
                        <span class="font-mono text-[9px] uppercase tracking-wider text-white/40 block mb-1">Category Interest</span>
                        <span class="font-serif text-[14px] text-white">{{ $enquiry->product_category }}</span>
                    </div>
                    @endif
                    @if($enquiry->quantity)
                    <div class="pl-4 {{ $enquiry->product_category ? 'border-l border-white/10' : '' }}">
                        <span class="font-mono text-[9px] uppercase tracking-wider text-white/40 block mb-1">Product/Quantity Needed</span>
                        <span class="font-serif text-[14px] text-white">{{ $enquiry->quantity }}</span>
                    </div>
                    @endif
                </div>
                
                <h3 class="font-mono text-[10px] uppercase tracking-wider text-white/40 mb-3">Message Details</h3>
                <div class="font-serif text-[16px] text-white/90 leading-relaxed whitespace-pre-line">
                    {{ $enquiry->message }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right Col: Actions & Notes -->
    <div class="space-y-6">
        
        <!-- Status Update -->
        <div class="admin-card p-6">
            <h3 class="font-display font-bold text-lg mb-4 border-b border-white/10 pb-3 text-white">Update Status</h3>
            <form action="{{ route('admin.enquiries.update', $enquiry) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-3 mb-6">
                    <label class="flex items-center space-x-3 cursor-pointer p-3 border border-white/10 hover:border-teal/50 transition-colors {{ $enquiry->status === 'new' ? 'bg-teal/10 border-teal' : 'bg-ink' }}">
                        <input type="radio" name="status" value="new" {{ $enquiry->status === 'new' ? 'checked' : '' }} class="w-4 h-4 text-teal bg-ink border-white/20 focus:ring-teal focus:ring-2">
                        <span class="font-mono text-[12px] uppercase tracking-wider {{ $enquiry->status === 'new' ? 'text-teal-2 font-bold' : 'text-white/70' }}">New / Unread</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer p-3 border border-white/10 hover:border-blue-500/50 transition-colors {{ $enquiry->status === 'contacted' ? 'bg-blue-500/10 border-blue-500' : 'bg-ink' }}">
                        <input type="radio" name="status" value="contacted" {{ $enquiry->status === 'contacted' ? 'checked' : '' }} class="w-4 h-4 text-blue-500 bg-ink border-white/20 focus:ring-blue-500 focus:ring-2">
                        <span class="font-mono text-[12px] uppercase tracking-wider {{ $enquiry->status === 'contacted' ? 'text-blue-300 font-bold' : 'text-white/70' }}">Contacted/Replied</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer p-3 border border-white/10 hover:border-white/50 transition-colors {{ $enquiry->status === 'closed' ? 'bg-white/5 border-white/30' : 'bg-ink' }}">
                        <input type="radio" name="status" value="closed" {{ $enquiry->status === 'closed' ? 'checked' : '' }} class="w-4 h-4 text-white/50 bg-ink border-white/20 focus:ring-white/50 focus:ring-2">
                        <span class="font-mono text-[12px] uppercase tracking-wider {{ $enquiry->status === 'closed' ? 'text-white font-bold' : 'text-white/70' }}">Closed/Resolved</span>
                    </label>
                </div>
                
                <h3 class="font-display font-bold text-lg mb-4 border-b border-white/10 pb-3 text-white">Internal Notes</h3>
                <textarea name="admin_notes" rows="4" class="admin-input w-full p-3 font-serif text-[14px] mb-4" placeholder="Add private notes regarding this enquiry...">{{ old('admin_notes', $enquiry->admin_notes) }}</textarea>
                
                <button type="submit" class="w-full bg-teal hover:bg-teal-light hover:text-teal transition-colors text-white py-4 font-display font-bold text-[11px] uppercase tracking-widest text-center shadow-lg">
                    Save Changes
                </button>
            </form>
        </div>
        
        <!-- Delete action -->
        <div class="admin-card p-6 border-l-4 border-l-red-500/50">
            <h3 class="font-display font-bold text-red-400 mb-2">Danger Zone</h3>
            <p class="font-serif text-[13px] text-white/50 mb-4">Permanently remove this enquiry from the database.</p>
            <form action="{{ route('admin.enquiries.destroy', $enquiry) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this enquiry? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-transparent border border-red-500/50 text-red-400 hover:bg-red-500/10 transition-colors py-3 font-mono text-[11px] uppercase tracking-widest text-center">
                    Delete Enquiry
                </button>
            </form>
        </div>
        
    </div>
</div>
@endsection

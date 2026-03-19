@extends('admin.layouts.app')

@section('title', 'Enquiry Details')
@section('header', 'Enquiry Details')
@section('subheader', 'Received ' . $enquiry->created_at->format('F d, Y at H:i A'))

@section('actions')
<a href="{{ route('admin.enquiries.index') }}" class="text-gray-500 hover:text-ink transition-colors font-mono text-[11px] uppercase tracking-wider">
    ← Back to List
</a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-[2fr_1fr] gap-8">
    
    <!-- Left Col: Enquiry Content -->
    <div class="space-y-6">
        <div class="admin-card p-6 md:p-8">
            <div class="flex justify-between items-start mb-8 pb-6 border-b border-gray-100">
                <div>
                    <h3 class="font-mono text-[10px] uppercase tracking-wider text-teal-700 mb-2">Company Information</h3>
                    <h2 class="font-display font-bold text-3xl text-ink">{{ $enquiry->company_name ?: 'Individual (No Company)' }}</h2>
                    <p class="font-serif italic text-[16px] text-gray-600">{{ $enquiry->contact_person }}</p>
                </div>
                <div>
                    @if($enquiry->status === 'new')
                        <span class="inline-block px-3 py-1.5 bg-teal/10 text-teal-700 border border-teal/20 font-mono text-[11px] uppercase tracking-widest font-bold rounded">New Lead</span>
                    @elseif($enquiry->status === 'contacted')
                        <span class="inline-block px-3 py-1.5 bg-blue-50 text-blue-600 border border-blue-200 font-mono text-[11px] uppercase tracking-widest font-bold rounded">Contacted</span>
                    @else
                        <span class="inline-block px-3 py-1.5 bg-gray-100 text-gray-500 border border-gray-200 font-mono text-[11px] uppercase tracking-widest font-bold rounded">Closed</span>
                    @endif
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-8 mb-8">
                <div>
                    <div class="font-mono text-[10px] uppercase tracking-wider text-gray-400 mb-1">Email Address</div>
                    <a href="mailto:{{ $enquiry->email }}" class="font-mono text-[14px] text-teal hover:text-ink transition-colors">{{ $enquiry->email }}</a>
                </div>
                <div>
                    <div class="font-mono text-[10px] uppercase tracking-wider text-gray-400 mb-1">Phone Number</div>
                    @if($enquiry->phone)
                        <a href="tel:{{ $enquiry->phone }}" class="font-mono text-[14px] text-ink">{{ $enquiry->phone }}</a>
                    @else
                        <span class="font-mono text-[14px] text-gray-400">Not provided</span>
                    @endif
                </div>
            </div>
            
            <div class="p-6 bg-gray-50 border border-gray-100 rounded mt-4">
                <div class="flex flex-wrap gap-4 mb-4 pb-4 border-b border-gray-200">
                    @if($enquiry->product_category)
                    <div>
                        <span class="font-mono text-[9px] uppercase tracking-wider text-gray-500 block mb-1">Category Interest</span>
                        <span class="font-serif text-[14px] text-ink">{{ $enquiry->product_category }}</span>
                    </div>
                    @endif
                    @if($enquiry->quantity)
                    <div class="pl-4 {{ $enquiry->product_category ? 'border-l border-gray-200' : '' }}">
                        <span class="font-mono text-[9px] uppercase tracking-wider text-gray-500 block mb-1">Product/Quantity Needed</span>
                        <span class="font-serif text-[14px] text-ink">{{ $enquiry->quantity }}</span>
                    </div>
                    @endif
                </div>
                
                <h3 class="font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-3">Message Details</h3>
                <div class="font-serif text-[16px] text-gray-800 leading-relaxed whitespace-pre-line">
                    {{ $enquiry->message }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right Col: Actions & Notes -->
    <div class="space-y-6">
        
        <!-- Status Update -->
        <div class="admin-card p-6">
            <h3 class="font-display font-bold text-lg mb-4 border-b border-gray-100 pb-3 text-ink">Update Status</h3>
            <form action="{{ route('admin.enquiries.update', $enquiry) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-3 mb-6">
                    <label class="flex items-center space-x-3 cursor-pointer p-3 border border-gray-200 hover:border-teal/50 transition-colors rounded {{ $enquiry->status === 'new' ? 'bg-teal/5 border-teal/50' : 'bg-white' }}">
                        <input type="radio" name="status" value="new" {{ $enquiry->status === 'new' ? 'checked' : '' }} class="w-4 h-4 text-teal bg-white border-gray-300 focus:ring-teal focus:ring-2">
                        <span class="font-mono text-[12px] uppercase tracking-wider {{ $enquiry->status === 'new' ? 'text-teal-700 font-bold' : 'text-gray-500' }}">New / Unread</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer p-3 border border-gray-200 hover:border-blue-500/50 transition-colors rounded {{ $enquiry->status === 'contacted' ? 'bg-blue-50 border-blue-300' : 'bg-white' }}">
                        <input type="radio" name="status" value="contacted" {{ $enquiry->status === 'contacted' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-white border-gray-300 focus:ring-blue-500 focus:ring-2">
                        <span class="font-mono text-[12px] uppercase tracking-wider {{ $enquiry->status === 'contacted' ? 'text-blue-700 font-bold' : 'text-gray-500' }}">Contacted/Replied</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer p-3 border border-gray-200 hover:border-gray-400 transition-colors rounded {{ $enquiry->status === 'closed' ? 'bg-gray-50 border-gray-300' : 'bg-white' }}">
                        <input type="radio" name="status" value="closed" {{ $enquiry->status === 'closed' ? 'checked' : '' }} class="w-4 h-4 text-gray-600 bg-white border-gray-300 focus:ring-gray-600 focus:ring-2">
                        <span class="font-mono text-[12px] uppercase tracking-wider {{ $enquiry->status === 'closed' ? 'text-ink font-bold' : 'text-gray-500' }}">Closed/Resolved</span>
                    </label>
                </div>
                
                <h3 class="font-display font-bold text-lg mb-4 border-b border-gray-100 pb-3 text-ink">Internal Notes</h3>
                <textarea name="admin_notes" rows="4" class="admin-input w-full p-3 font-serif text-[14px] mb-4 text-gray-800" placeholder="Add private notes regarding this enquiry...">{{ old('admin_notes', $enquiry->admin_notes) }}</textarea>
                
                <button type="submit" class="w-full bg-teal hover:bg-teal-light hover:text-teal transition-colors text-white py-4 font-display font-bold text-[11px] uppercase tracking-widest text-center shadow-lg">
                    Save Changes
                </button>
            </form>
        </div>
        
        <!-- Delete action -->
        <div class="admin-card p-6 border-l-4 border-l-red-500/50 bg-red-50/50">
            <h3 class="font-display font-bold text-red-600 mb-2">Danger Zone</h3>
            <p class="font-serif text-[13px] text-gray-500 mb-4">Permanently remove this enquiry from the database.</p>
            <form action="{{ route('admin.enquiries.destroy', $enquiry) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this enquiry? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-white border border-red-200 text-red-500 hover:bg-red-50 hover:border-red-300 transition-colors py-3 font-mono text-[11px] uppercase tracking-widest text-center rounded">
                    Delete Enquiry
                </button>
            </form>
        </div>
        
    </div>
</div>
@endsection

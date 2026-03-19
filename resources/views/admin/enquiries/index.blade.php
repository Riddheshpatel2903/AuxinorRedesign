@extends('admin.layouts.app')

@section('title', 'Enquiries')
@section('header', 'Customer Enquiries')
@section('subheader', 'Review and process bulk and general enquiries from the website.')

@section('content')
<div class="admin-card overflow-hidden">
    <!-- Filters -->
    <div class="p-4 border-b border-white/5 bg-white/5 flex gap-4">
        <div class="flex space-x-2">
            <a href="{{ route('admin.enquiries.index') }}" class="px-4 py-2 font-mono text-[11px] uppercase tracking-wider {{ !request('status') ? 'bg-teal text-white' : 'bg-white/5 text-white/50 hover:bg-white/10 hover:text-white' }} transition-colors">
                All
            </a>
            <a href="{{ route('admin.enquiries.index', ['status' => 'new']) }}" class="px-4 py-2 font-mono text-[11px] uppercase tracking-wider {{ request('status') === 'new' ? 'bg-teal text-white' : 'bg-white/5 text-white/50 hover:bg-white/10 hover:text-white' }} transition-colors">
                New
                @php $newCount = \App\Models\Enquiry::where('status', 'new')->count(); @endphp
                @if($newCount > 0)
                    <span class="ml-1 px-1 bg-white/20 rounded">{{ $newCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.enquiries.index', ['status' => 'contacted']) }}" class="px-4 py-2 font-mono text-[11px] uppercase tracking-wider {{ request('status') === 'contacted' ? 'bg-teal text-white' : 'bg-white/5 text-white/50 hover:bg-white/10 hover:text-white' }} transition-colors">
                Contacted
            </a>
            <a href="{{ route('admin.enquiries.index', ['status' => 'closed']) }}" class="px-4 py-2 font-mono text-[11px] uppercase tracking-wider {{ request('status') === 'closed' ? 'bg-teal text-white' : 'bg-white/5 text-white/50 hover:bg-white/10 hover:text-white' }} transition-colors">
                Closed
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-white/10">
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-white/50 w-24">Date</th>
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-white/50 w-24">Status</th>
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-white/50">Company / Contact</th>
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-white/50">Request Details</th>
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-white/50 text-right w-24">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($enquiries as $enquiry)
                <tr class="{{ $enquiry->status === 'new' ? 'bg-teal/5' : '' }} hover:bg-white/5 transition-colors group">
                    <td class="px-6 py-4 font-mono text-[11px] text-white/60 whitespace-nowrap">
                        {{ $enquiry->created_at->format('d M, Y') }}
                        <div class="text-[9px] text-white/30 mt-1">{{ $enquiry->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($enquiry->status === 'new')
                            <span class="inline-block px-2 py-1 bg-teal/20 text-teal-2 border border-teal/50 font-mono text-[9px] uppercase tracking-wider rounded">New</span>
                        @elseif($enquiry->status === 'contacted')
                            <span class="inline-block px-2 py-1 bg-blue-500/20 text-blue-300 border border-blue-500/50 font-mono text-[9px] uppercase tracking-wider rounded">Contacted</span>
                        @else
                            <span class="inline-block px-2 py-1 bg-white/10 text-white/50 font-mono text-[9px] uppercase tracking-wider rounded">Closed</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-display font-bold text-[14px] text-white">{{ $enquiry->company_name ?: 'No Company' }}</div>
                        <div class="font-serif italic text-[13px] text-white/70">{{ $enquiry->contact_person }}</div>
                        <div class="font-mono text-[10px] text-white/50 mt-1">{{ $enquiry->email }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($enquiry->product_category || $enquiry->quantity)
                            <div class="font-mono text-[10px] uppercase tracking-wider text-teal-2 mb-1">
                                {{ $enquiry->product_category ?: 'General' }} 
                                @if($enquiry->quantity) | Qt: {{ $enquiry->quantity }} @endif
                            </div>
                        @else
                            <div class="font-mono text-[10px] uppercase tracking-wider text-white/30 mb-1">General Enquiry</div>
                        @endif
                        <div class="font-serif text-[13px] text-white/80 line-clamp-2">
                            {{ $enquiry->message }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="inline-flex items-center text-white/50 hover:text-teal-2 transition-colors font-mono text-[10px] uppercase tracking-wider border border-white/10 px-3 py-1.5 hover:border-teal-2 bg-ink">
                            View →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-white/40 font-serif italic text-sm">
                        No enquiries match the current filters.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t border-white/5">
        {{ $enquiries->links('pagination::tailwind') }}
    </div>
</div>
@endsection

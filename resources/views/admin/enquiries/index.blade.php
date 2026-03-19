@extends('admin.layouts.app')

@section('title', 'Enquiries')
@section('header', 'Customer Enquiries')
@section('subheader', 'Review and process bulk and general enquiries from the website.')

@section('content')
<div class="admin-card overflow-hidden">
    <!-- Filters -->
    <div class="p-4 border-b border-gray-100 bg-gray-50 flex gap-4">
        <div class="flex space-x-2">
            <a href="{{ route('admin.enquiries.index') }}" class="px-4 py-2 font-mono text-[11px] uppercase tracking-wider {{ !request('status') ? 'bg-teal text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-ink' }} transition-colors">
                All
            </a>
            <a href="{{ route('admin.enquiries.index', ['status' => 'new']) }}" class="px-4 py-2 font-mono text-[11px] uppercase tracking-wider {{ request('status') === 'new' ? 'bg-teal text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-ink' }} transition-colors">
                New
                @php $newCount = \App\Models\Enquiry::where('status', 'new')->count(); @endphp
                @if($newCount > 0)
                    <span class="ml-1 px-1 bg-white rounded text-teal mx-1 shadow-sm">{{ $newCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.enquiries.index', ['status' => 'contacted']) }}" class="px-4 py-2 font-mono text-[11px] uppercase tracking-wider {{ request('status') === 'contacted' ? 'bg-teal text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-ink' }} transition-colors">
                Contacted
            </a>
            <a href="{{ route('admin.enquiries.index', ['status' => 'closed']) }}" class="px-4 py-2 font-mono text-[11px] uppercase tracking-wider {{ request('status') === 'closed' ? 'bg-teal text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-ink' }} transition-colors">
                Closed
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-gray-500 w-24">Date</th>
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-gray-500 w-24">Status</th>
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-gray-500">Company / Contact</th>
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-gray-500">Request Details</th>
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-gray-500 text-right w-24">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($enquiries as $enquiry)
                <tr class="{{ $enquiry->status === 'new' ? 'bg-teal/5' : '' }} hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-4 font-mono text-[11px] text-gray-500 whitespace-nowrap">
                        {{ $enquiry->created_at->format('d M, Y') }}
                        <div class="text-[9px] text-gray-400 mt-1">{{ $enquiry->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($enquiry->status === 'new')
                            <span class="inline-block px-2 py-1 bg-teal/10 text-teal-700 border border-teal/20 font-mono text-[9px] uppercase tracking-wider rounded font-bold">New</span>
                        @elseif($enquiry->status === 'contacted')
                            <span class="inline-block px-2 py-1 bg-blue-50 text-blue-600 border border-blue-200 font-mono text-[9px] uppercase tracking-wider rounded">Contacted</span>
                        @else
                            <span class="inline-block px-2 py-1 bg-gray-100 text-gray-500 border border-gray-200 font-mono text-[9px] uppercase tracking-wider rounded">Closed</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-display font-bold text-[14px] text-ink">{{ $enquiry->company_name ?: 'No Company' }}</div>
                        <div class="font-serif italic text-[13px] text-gray-600">{{ $enquiry->contact_person }}</div>
                        <div class="font-mono text-[10px] text-gray-400 mt-1">{{ $enquiry->email }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($enquiry->product_category || $enquiry->quantity)
                            <div class="font-mono text-[10px] uppercase tracking-wider text-teal-700 mb-1">
                                {{ $enquiry->product_category ?: 'General' }} 
                                @if($enquiry->quantity) | Qt: {{ $enquiry->quantity }} @endif
                            </div>
                        @else
                            <div class="font-mono text-[10px] uppercase tracking-wider text-gray-400 mb-1">General Enquiry</div>
                        @endif
                        <div class="font-serif text-[13px] text-gray-600 line-clamp-2">
                            {{ $enquiry->message }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="inline-flex items-center text-gray-500 hover:text-teal transition-colors font-mono text-[10px] uppercase tracking-wider border border-gray-200 px-3 py-1.5 hover:border-teal bg-white rounded">
                            View →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 font-serif italic text-sm">
                        No enquiries match the current filters.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t border-gray-100">
        {{ $enquiries->links('pagination::tailwind') }}
    </div>
</div>
@endsection

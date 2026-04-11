@extends('admin.layouts.app')

@section('title', 'Manage Industries')
@section('header', 'Industries We Serve')
@section('subheader', 'Manage the industries displayed on the home and industry pages.')

@section('actions')
    <a href="{{ route('admin.industries.create') }}" class="ed-btn-pub">
        <span class="mr-1">+</span> Add New Industry
    </a>
@endsection

@section('content')
<div class="admin-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-gray-500">Order</th>
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-gray-500">Industry</th>
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-gray-500">Icon</th>
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-gray-500">Status</th>
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-gray-500 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($industries as $industry)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs text-gray-400">#{{ $industry->order }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($industry->image_path)
                                    <div class="w-10 h-10 rounded-sm overflow-hidden mr-3 border border-gray-100 bg-gray-100 flex-shrink-0">
                                        <img src="{{ asset($industry->image_path) }}" class="w-full h-full object-cover" alt="">
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-sm mr-3 border border-dashed border-gray-200 bg-gray-50 flex items-center justify-center flex-shrink-0">
                                        <span class="text-[10px] text-gray-300 uppercase font-mono">No Img</span>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-display font-bold text-ink">{{ $industry->name }}</div>
                                    <div class="text-[11px] text-gray-500 line-clamp-1 max-w-xs">{{ $industry->description }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xl">{{ $industry->icon }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($industry->is_active)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-teal/10 text-teal">Active</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-400">Hidden</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('admin.industries.edit', $industry) }}" class="p-2 text-gray-400 hover:text-teal transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.industries.destroy', $industry) }}" method="POST" onsubmit="return confirm('Delete this industry?');" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-300 mb-2">
                                <svg class="w-12 h-12 mx-auto mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 012-2H9a2 2 0 00-2 2v11a2 2 0 01-2 2H3"></path></svg>
                            </div>
                            <p class="font-serif italic text-gray-500">No industries found. Add your first one above.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

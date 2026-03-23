@extends('admin.layouts.app')

@section('title', 'Industries')
@section('header', 'Industries Served')
@section('subheader', 'Manage industries, descriptions, and icons displayed on the site.')

@section('actions')
<a href="{{ route('admin.industries.create') }}" class="bg-teal hover:bg-teal-light hover:text-teal transition-colors text-white px-4 py-2 font-display font-bold text-[11px] uppercase tracking-widest inline-flex items-center">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
    Add Industry
</a>
@endsection

@section('content')
<div class="admin-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left font-serif text-sm">
            <thead class="bg-gray-50 border-b border-gray-100 text-[10px] uppercase tracking-widest font-mono text-muted">
                <tr>
                    <th class="px-6 py-4 font-bold">Sort</th>
                    <th class="px-6 py-4 font-bold">Industry</th>
                    <th class="px-6 py-4 font-bold">Slug</th>
                    <th class="px-6 py-4 font-bold">Status</th>
                    <th class="px-6 py-4 font-bold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($industries as $industry)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 font-mono text-xs text-muted">{{ $industry->sort_order }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <span class="text-xl mr-3">{{ $industry->icon }}</span>
                            <div>
                                <div class="font-bold text-ink">{{ $industry->name }}</div>
                                <div class="text-[11px] text-muted line-clamp-1 max-w-xs">{{ $industry->desc }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-mono text-xs text-teal">{{ $industry->slug }}</td>
                    <td class="px-6 py-4">
                        @if($industry->is_active)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-medium bg-teal/10 text-teal">ACTIVE</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-medium bg-gray-100 text-gray-400">INACTIVE</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.industries.edit', $industry) }}" class="text-ink hover:text-teal transition-colors">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <form action="{{ route('admin.industries.destroy', $industry) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to remove this industry?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 transition-colors">
                                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

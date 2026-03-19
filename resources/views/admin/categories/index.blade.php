@extends('admin.layouts.app')

@section('title', 'Categories')
@section('header', 'Product Categories')
@section('subheader', 'Manage the distinct groups your chemicals belong to.')

@section('actions')
<a href="{{ route('admin.categories.create') }}" class="bg-teal hover:bg-teal-light hover:text-teal transition-colors text-white px-4 py-2 font-display font-bold text-[11px] uppercase tracking-widest inline-flex items-center">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
    New Category
</a>
@endsection

@section('content')
<div class="admin-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-white/10 bg-white/5">
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-white/50 w-16">Icon</th>
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-white/50">Details</th>
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-white/50 text-center w-24">Products</th>
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-white/50 text-right w-32">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($categories as $category)
                <tr class="hover:bg-white/5 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="w-10 h-10 border border-white/10 bg-ink flex items-center justify-center text-xl text-teal-2">
                            {{ $category->icon }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-display font-bold text-[15px] text-white group-hover:text-teal-2 transition-colors">{{ $category->name }}</div>
                        <div class="font-mono text-[10px] text-white/40 mt-1">/{{ $category->slug }}</div>
                        @if($category->description)
                            <div class="font-serif italic text-[12px] text-white/60 mt-1 line-clamp-1">{{ $category->description }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-block px-2 py-1 bg-white/10 text-white/80 font-mono text-[10px] rounded">{{ $category->products_count ?? $category->products()->count() }}</span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-white/40 hover:text-white transition-colors p-1" title="Edit">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this category? All associated products will NOT be deleted, but they will lose this category association.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-white/40 hover:text-red-400 transition-colors p-1" title="Delete">
                                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-white/40 font-serif italic text-sm">
                        No categories found. Create your first category to get started.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'New Category')
@section('header', 'New Category')
@section('subheader', 'Create a new product grouping.')

@section('actions')
<a href="{{ route('admin.categories.index') }}" class="text-white/50 hover:text-white transition-colors font-mono text-[11px] uppercase tracking-wider">
    ← Back to Categories
</a>
@endsection

@section('content')
<div class="admin-card p-6 md:p-8 max-w-3xl">
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        
        <div class="mb-6">
            <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Category Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="admin-input w-full p-3 font-display text-[15px]" placeholder="e.g. Aliphatic Solvents" x-data @input="$el.form.slug.value = $el.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '')">
            @error('name') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>
        
        <div class="mb-6">
            <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Slug</label>
            <input type="text" name="slug" value="{{ old('slug') }}" class="admin-input w-full p-3 font-mono text-[13px] text-white/70" placeholder="auto-generated-from-name">
            <p class="text-white/30 text-xs mt-1 font-serif italic">URL friendly identifier. Leave blank to auto-generate.</p>
            @error('slug') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>
        
        <div class="mb-6">
            <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Icon (Emoji or SVG)</label>
            <input type="text" name="icon" value="{{ old('icon', '🧪') }}" class="admin-input w-24 p-3 font-display text-2xl text-center">
            @error('icon') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>
        
        <div class="mb-8">
            <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Description</label>
            <textarea name="description" rows="4" class="admin-input w-full p-3 font-serif text-[14px] resize-y">{{ old('description') }}</textarea>
            @error('description') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>
        
        <div class="flex items-center">
            <button type="submit" class="bg-teal hover:bg-teal-light hover:text-teal transition-colors text-white px-8 py-3 font-display font-bold text-[11px] uppercase tracking-widest">
                Create Category
            </button>
        </div>
    </form>
</div>
@endsection

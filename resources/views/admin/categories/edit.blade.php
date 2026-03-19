@extends('admin.layouts.app')

@section('title', 'Edit Category')
@section('header', 'Edit Category')
@section('subheader', 'Update details for ' . $category->name)

@section('actions')
<a href="{{ route('admin.categories.index') }}" class="text-white/50 hover:text-white transition-colors font-mono text-[11px] uppercase tracking-wider">
    ← Back to Categories
</a>
@endsection

@section('content')
<div class="admin-card p-6 md:p-8 max-w-3xl">
    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-6">
            <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Category Name *</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="admin-input w-full p-3 font-display text-[15px]" placeholder="e.g. Aliphatic Solvents">
            @error('name') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>
        
        <div class="mb-6">
            <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="admin-input w-full p-3 font-mono text-[13px] text-white/70" placeholder="auto-generated-from-name">
            <p class="text-white/30 text-xs mt-1 font-serif italic">URL friendly identifier. Modifying this might break existing links.</p>
            @error('slug') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>
        
        <div class="mb-6">
            <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Icon (Emoji or SVG)</label>
            <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" class="admin-input w-24 p-3 font-display text-2xl text-center">
            @error('icon') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>
        
        <div class="mb-8">
            <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Description</label>
            <textarea name="description" rows="4" class="admin-input w-full p-3 font-serif text-[14px] resize-y">{{ old('description', $category->description) }}</textarea>
            @error('description') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>
        
        <div class="flex items-center border-t border-white/10 pt-6">
            <button type="submit" class="bg-teal hover:bg-teal-light hover:text-teal transition-colors text-white px-8 py-3 font-display font-bold text-[11px] uppercase tracking-widest mr-4">
                Update Category
            </button>
            <a href="{{ route('admin.categories.index') }}" class="text-white/50 hover:text-white font-serif text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection

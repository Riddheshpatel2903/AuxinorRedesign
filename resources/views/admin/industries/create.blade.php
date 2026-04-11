@extends('admin.layouts.app')

@section('title', 'Add New Industry')
@section('header', 'New Industry')
@section('subheader', 'Create a new industry segment to display on the website.')

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.industries.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div class="admin-card p-6 md:p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="space-y-2">
                    <label class="font-mono text-[10px] uppercase tracking-widest text-gray-400 block">Industry Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="admin-input w-full px-4 py-3 font-display font-medium text-ink"
                        placeholder="e.g. Pharmaceuticals">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Icon -->
                <div class="space-y-2">
                    <label class="font-mono text-[10px] uppercase tracking-widest text-gray-400 block">Icon String (Emoji)</label>
                    <input type="text" name="icon" value="{{ old('icon') }}" required
                        class="admin-input w-full px-4 py-3 font-display font-medium text-ink"
                        placeholder="e.g. 🧪">
                    @error('icon')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Description -->
            <div class="space-y-2">
                <label class="font-mono text-[10px] uppercase tracking-widest text-gray-400 block">Short Description</label>
                <textarea name="description" rows="3" required
                    class="admin-input w-full px-4 py-3 text-sm leading-relaxed"
                    placeholder="Brief summary of how we serve this industry...">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Products -->
            <div class="space-y-2">
                <label class="font-mono text-[10px] uppercase tracking-widest text-gray-400 block">Key Products Supplied (Comma separated)</label>
                <textarea name="products" rows="2"
                    class="admin-input w-full px-4 py-3 text-sm leading-relaxed"
                    placeholder="e.g. Acrylates, Toluene, Benzene, Acetone">{{ old('products') }}</textarea>
                @error('products')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Order -->
                <div class="space-y-2">
                    <label class="font-mono text-[10px] uppercase tracking-widest text-gray-400 block">Display Order</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" required
                        class="admin-input w-full px-4 py-3 font-mono text-sm text-ink">
                    @error('order')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Active Toggle -->
                <div class="space-y-2">
                    <label class="font-mono text-[10px] uppercase tracking-widest text-gray-400 block">Status</label>
                    <label class="flex items-center space-x-3 cursor-pointer p-3 border border-gray-100 rounded hover:bg-gray-50 transition-colors">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 text-teal border-gray-300 rounded focus:ring-teal">
                        <span class="text-sm text-gray-600 font-medium">Visible on Frontend</span>
                    </label>
                </div>
            </div>

            <!-- Image -->
            <div class="space-y-2 pt-4 border-t border-gray-50">
                <label class="font-mono text-[10px] uppercase tracking-widest text-gray-400 block">Background Image</label>
                <div class="flex items-start space-x-4">
                    <div class="flex-grow">
                        <input type="file" name="image" class="admin-input w-full p-2 text-xs file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-ink hover:file:bg-gray-200 cursor-pointer">
                        <p class="text-[10px] text-gray-400 mt-2 italic font-serif leading-tight">Recommended: 1200x800px or higher. Aspect ratio approx 3:2.</p>
                    </div>
                </div>
                @error('image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Submit -->
        <div class="flex items-center space-x-4">
            <button type="submit" class="ed-btn-pub px-8 py-3">Create Industry</button>
            <a href="{{ route('admin.industries.index') }}" class="text-gray-400 hover:text-ink font-mono text-[10px] uppercase tracking-widest transition-colors">Cancel</a>
        </div>
    </form>
</div>
@endsection

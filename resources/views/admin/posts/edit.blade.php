@extends('admin.layouts.app')

@section('title', 'Edit Post')
@section('header', 'Edit Market Insight')
@section('subheader', 'Update article details.')

@section('actions')
<div class="space-x-4">
    @if($post->is_published)
    <a href="{{ route('insights.show', $post->slug) }}" target="_blank" class="text-teal hover:text-teal-700 transition-colors font-mono text-[11px] uppercase tracking-wider">
        View Live ↗
    </a>
    @endif
    <a href="{{ route('admin.posts.index') }}" class="text-gray-500 hover:text-ink transition-colors font-mono text-[11px] uppercase tracking-wider">
        ← Back to Insights
    </a>
</div>
@endsection

@section('content')
<form action="{{ route('admin.posts.update', $post) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Col: Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <div class="admin-card p-6 md:p-8">
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Post Title *</label>
                    <input type="text" name="title" value="{{ old('title', $post->title) }}" required class="admin-input w-full p-3 font-display text-[18px] font-bold">
                    @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $post->slug) }}" class="admin-input w-full p-3 font-mono text-[13px] text-gray-600">
                    <p class="text-gray-400 text-[10px] mt-1 italic">Careful: Changing slug breaks existing links unless preserved.</p>
                    @error('slug') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Excerpt Summary</label>
                    <textarea name="excerpt" rows="3" class="admin-input w-full p-3 font-serif text-[14px]">{{ old('excerpt', $post->excerpt) }}</textarea>
                </div>
                
                <div>
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Content (Markdown Supported)</label>
                    <textarea name="content" rows="16" class="admin-input w-full p-4 font-mono text-[14px] leading-relaxed resize-y">{{ old('content', $post->content) }}</textarea>
                    @error('content') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        
        <!-- Right Col: Meta & Status -->
        <div class="space-y-6">
            <div class="admin-card p-6">
                <h3 class="font-display font-bold text-lg mb-4 border-b border-gray-100 pb-4 text-ink">Publishing Options</h3>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Category</label>
                    <input type="text" name="category" value="{{ old('category', $post->category) }}" class="admin-input w-full p-3 font-serif text-[14px]" list="categoryList">
                    <datalist id="categoryList">
                        <option value="Market Update">
                        <option value="Company News">
                        <option value="Industry Trends">
                        <option value="Supply Chain">
                    </datalist>
                </div>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Author</label>
                    <input type="text" name="author" value="{{ old('author', $post->author) }}" class="admin-input w-full p-3 font-serif text-[14px]">
                </div>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Featured Image URL</label>
                    <input type="url" name="featured_image" value="{{ old('featured_image', $post->featured_image) }}" class="admin-input w-full p-3 font-mono text-[12px]">
                    @if($post->featured_image)
                        <img src="{{ $post->featured_image }}" class="mt-2 w-full h-24 object-cover border border-gray-200">
                    @endif
                </div>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Publish Date</label>
                    <input type="datetime-local" name="published_at" value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}" class="admin-input w-full p-3 font-mono text-[13px]">
                </div>
                
                <div class="space-y-4 mb-8">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $post->is_published) ? 'checked' : '' }} class="w-4 h-4 text-teal bg-white border-gray-300 rounded focus:ring-teal focus:ring-2">
                        <span class="font-serif text-[14px] text-ink">Live Publication</span>
                    </label>
                </div>
                
                <button type="submit" class="w-full bg-teal hover:bg-teal-light hover:text-teal transition-colors text-white px-8 py-3 font-display font-bold text-[11px] uppercase tracking-widest text-center">
                    Update Article
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

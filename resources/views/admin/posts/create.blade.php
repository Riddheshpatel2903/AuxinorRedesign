@extends('admin.layouts.app')

@section('title', 'Write Post')
@section('header', 'Write Market Insight')
@section('subheader', 'Draft a new article or market report.')

@section('actions')
<a href="{{ route('admin.posts.index') }}" class="text-white/50 hover:text-white transition-colors font-mono text-[11px] uppercase tracking-wider">
    ← Back to Insights
</a>
@endsection

@section('content')
<form action="{{ route('admin.posts.store') }}" method="POST">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Col: Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <div class="admin-card p-6 md:p-8">
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Post Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="admin-input w-full p-3 font-display text-[18px] font-bold" x-data @input="$el.form.slug.value = $el.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '')">
                    @error('title') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" class="admin-input w-full p-3 font-mono text-[13px] text-white/70">
                    @error('slug') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Excerpt Summary</label>
                    <textarea name="excerpt" rows="3" class="admin-input w-full p-3 font-serif text-[14px]">{{ old('excerpt') }}</textarea>
                    <p class="text-white/30 text-xs mt-1 font-serif italic">Appears on the insight listing cards.</p>
                </div>
                
                <div>
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Content (Markdown Supported)</label>
                    <textarea name="content" rows="16" class="admin-input w-full p-4 font-mono text-[14px] leading-relaxed resize-y">{{ old('content') }}</textarea>
                    @error('content') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        
        <!-- Right Col: Meta & Status -->
        <div class="space-y-6">
            <div class="admin-card p-6">
                <h3 class="font-display font-bold text-lg mb-4 border-b border-white/10 pb-4">Publishing Options</h3>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Category</label>
                    <input type="text" name="category" value="{{ old('category', 'Market Update') }}" class="admin-input w-full p-3 font-serif text-[14px]" list="categoryList">
                    <datalist id="categoryList">
                        <option value="Market Update">
                        <option value="Company News">
                        <option value="Industry Trends">
                        <option value="Supply Chain">
                    </datalist>
                </div>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Author</label>
                    <input type="text" name="author" value="{{ old('author', Auth::user()->name ?? 'Admin') }}" class="admin-input w-full p-3 font-serif text-[14px]">
                </div>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Featured Image URL</label>
                    <input type="url" name="featured_image" value="{{ old('featured_image') }}" placeholder="https://..." class="admin-input w-full p-3 font-mono text-[12px]">
                </div>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Publish Date</label>
                    <input type="datetime-local" name="published_at" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}" class="admin-input w-full p-3 font-mono text-[13px]">
                </div>
                
                <div class="space-y-4 mb-8">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }} class="w-4 h-4 text-teal bg-ink border-white/20 rounded">
                        <span class="font-serif text-[14px] text-white">Publish immediately</span>
                    </label>
                </div>
                
                <button type="submit" class="w-full bg-teal hover:bg-teal-light hover:text-teal transition-colors text-white px-8 py-3 font-display font-bold text-[11px] uppercase tracking-widest text-center">
                    Save Article
                </button>
            </div>
            
            <div class="admin-card p-6 border-t-2 border-t-white/10 bg-white/5">
                <h4 class="font-display font-bold text-sm mb-2 text-white/70">Markdown Tips</h4>
                <ul class="font-mono text-[10px] text-white/50 space-y-2">
                    <li><code class="text-teal-2"># Heading 1</code> (Don't use, H1 is title)</li>
                    <li><code class="text-teal-2">## Heading 2</code></li>
                    <li><code class="text-teal-2">**bold text**</code></li>
                    <li><code class="text-teal-2">*italic text*</code></li>
                    <li><code class="text-teal-2">[Link Text](url)</code></li>
                    <li><code class="text-teal-2">- Bullet list item</code></li>
                </ul>
            </div>
        </div>
    </div>
</form>
@endsection

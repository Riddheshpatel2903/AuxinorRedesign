@extends('admin.layouts.app')

@section('title', 'Add Product')
@section('header', 'Add Product')
@section('subheader', 'Add a new chemical to your catalogue.')

@section('actions')
<a href="{{ route('admin.products.index') }}" class="text-white/50 hover:text-white transition-colors font-mono text-[11px] uppercase tracking-wider">
    ← Back to Products
</a>
@endsection

@section('content')
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Col: Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="admin-card p-6 md:p-8">
                <h3 class="font-display font-bold text-lg mb-6 border-b border-white/10 pb-4">Basic Information</h3>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Product Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="admin-input w-full p-3 font-display text-[15px]" x-data @input="$el.form.slug.value = $el.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '')">
                    @error('name') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" class="admin-input w-full p-3 font-mono text-[13px] text-white/70">
                    @error('slug') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">CAS Number</label>
                        <input type="text" name="cas_number" value="{{ old('cas_number') }}" class="admin-input w-full p-3 font-mono text-[13px]">
                        @error('cas_number') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Chemical Formula</label>
                        <input type="text" name="chemical_formula" value="{{ old('chemical_formula') }}" class="admin-input w-full p-3 font-mono text-[13px]">
                        @error('chemical_formula') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Short Description (Excerpt)</label>
                    <textarea name="short_description" rows="2" class="admin-input w-full p-3 font-serif text-[14px]">{{ old('short_description') }}</textarea>
                    @error('short_description') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Full Description</label>
                    <textarea name="description" rows="6" class="admin-input w-full p-3 font-serif text-[14px]">{{ old('description') }}</textarea>
                    @error('description') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="admin-card p-6 md:p-8" x-data="{ specs: [{key: '', value: ''}] }">
                <h3 class="font-display font-bold text-lg mb-6 border-b border-white/10 pb-4">Applications & Specifications</h3>
                
                <div class="mb-8">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Applications (Comma separated)</label>
                    <textarea name="applications" rows="3" class="admin-input w-full p-3 font-serif text-[14px]" placeholder="e.g. Adhesives, Coatings, Pharmaceuticals">{{ old('applications') }}</textarea>
                </div>
                
                <div>
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-4">Technical Specifications</label>
                    
                    <template x-for="(spec, index) in specs" :key="index">
                        <div class="flex gap-4 mb-3">
                            <input type="text" :name="`specifications[${index}][key]`" x-model="spec.key" placeholder="e.g. Purity" class="admin-input flex-1 p-2 font-mono text-sm">
                            <input type="text" :name="`specifications[${index}][value]`" x-model="spec.value" placeholder="e.g. > 99.5%" class="admin-input flex-1 p-2 font-mono text-sm">
                            <button type="button" @click="specs.splice(index, 1)" class="text-red-400 hover:text-red-300 px-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </template>
                    
                    <button type="button" @click="specs.push({key: '', value: ''})" class="mt-2 font-mono text-[10px] uppercase tracking-wider text-teal hover:text-white transition-colors border border-dashed border-teal/50 hover:border-white w-full py-2 text-center">
                        + Add Row
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Right Col: Meta & Status -->
        <div class="space-y-6">
            <div class="admin-card p-6">
                <h3 class="font-display font-bold text-lg mb-4 border-b border-white/10 pb-4">Publishing</h3>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Category *</label>
                    <select name="category_id" required class="admin-input w-full p-3 font-serif text-[14px]">
                        <option value="">Select a category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div class="space-y-4 mb-8">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 text-teal bg-ink border-white/20 rounded focus:ring-teal focus:ring-2">
                        <span class="font-serif text-[14px] text-white">Active Product</span>
                    </label>
                    
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4 text-teal bg-ink border-white/20 rounded focus:ring-teal focus:ring-2">
                        <span class="font-serif text-[14px] text-white">Featured Homepage Product</span>
                    </label>
                </div>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="admin-input w-full p-3 font-mono text-[13px]">
                </div>
                
                <h3 class="font-display font-bold text-lg mt-8 mb-4 border-b border-white/10 pb-4">Media</h3>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Primary Image</label>
                    <input type="file" name="image" accept="image/*" class="admin-input w-full p-3 font-mono text-[13px] text-white/70">
                    <span class="text-white/40 text-[10px] mt-1 block">Main display image for catalog.</span>
                    @error('image') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-white/50 mb-2">Image Gallery</label>
                    <input type="file" name="gallery[]" multiple accept="image/*" class="admin-input w-full p-3 font-mono text-[13px] text-white/70">
                    <span class="text-white/40 text-[10px] mt-1 block">Select multiple images for the product gallery.</span>
                    @error('gallery.*') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <button type="submit" class="w-full bg-teal hover:bg-teal-light hover:text-teal transition-colors text-white px-8 py-3 font-display font-bold text-[11px] uppercase tracking-widest text-center">
                    Save Product
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

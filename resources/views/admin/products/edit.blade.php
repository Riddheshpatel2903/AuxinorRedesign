@extends('admin.layouts.app')

@section('title', 'Edit Product')
@section('header', 'Edit Product')
@section('subheader', 'Update details for ' . $product->name)

@section('actions')
<div class="space-x-4">
    <a href="{{ route('products.show', [$product->category->slug ?? 'misc', $product->slug]) }}" target="_blank" class="text-teal hover:text-teal-700 transition-colors font-mono text-[11px] uppercase tracking-wider">
        View Live ↗
    </a>
    <a href="{{ route('admin.products.index') }}" class="text-gray-500 hover:text-ink transition-colors font-mono text-[11px] uppercase tracking-wider">
        ← Back to Products
    </a>
</div>
@endsection

@section('content')
<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Col: Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="admin-card p-6 md:p-8">
                <h3 class="font-display font-bold text-lg mb-6 border-b border-gray-100 pb-4 text-ink">Basic Information</h3>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Product Name *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="admin-input w-full p-3 font-display text-[15px]">
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="admin-input w-full p-3 font-mono text-[13px] text-gray-600">
                    <p class="text-gray-400 text-[10px] mt-1 italic">Careful: Changing slug breaks existing links unless preserved.</p>
                    @error('slug') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">CAS Number</label>
                        <input type="text" name="cas_number" value="{{ old('cas_number', $product->cas_number) }}" class="admin-input w-full p-3 font-mono text-[13px]">
                    </div>
                    <div>
                        <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Chemical Formula</label>
                        <input type="text" name="chemical_formula" value="{{ old('chemical_formula', $product->chemical_formula) }}" class="admin-input w-full p-3 font-mono text-[13px]">
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Short Description</label>
                    <textarea name="short_description" rows="2" class="admin-input w-full p-3 font-serif text-[14px]">{{ old('short_description', $product->short_description) }}</textarea>
                </div>
                
                <div>
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Full Description</label>
                    <textarea name="description" rows="6" class="admin-input w-full p-3 font-serif text-[14px]">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>
            
            <div class="admin-card p-6 md:p-8" x-data="{ specs: {{ json_encode($product->specifications ?: []) }} }">
                <h3 class="font-display font-bold text-lg mb-6 border-b border-gray-100 pb-4 text-ink">Applications & Specifications</h3>
                
                <div class="mb-8">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Applications (Comma separated)</label>
                    <textarea name="applications" rows="3" class="admin-input w-full p-3 font-serif text-[14px]">{{ old('applications', $product->applications) }}</textarea>
                </div>
                
                <div>
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-4">Technical Specifications</label>
                    
                    <template x-for="(spec, index) in specs" :key="index">
                        <div class="flex gap-4 mb-3">
                            <input type="text" :name="`specifications[${index}][key]`" x-model="spec.key" class="admin-input flex-1 p-2 font-mono text-sm">
                            <input type="text" :name="`specifications[${index}][value]`" x-model="spec.value" class="admin-input flex-1 p-2 font-mono text-sm">
                            <button type="button" @click="specs.splice(index, 1)" class="text-red-500 hover:text-red-600 px-3 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </template>
                    
                    <button type="button" @click="specs.push({key: '', value: ''})" class="mt-2 font-mono text-[10px] uppercase tracking-wider text-teal hover:text-ink transition-colors border border-dashed border-gray-300 hover:border-teal w-full py-2 text-center rounded">
                        + Add Row
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Right Col: Meta & Status -->
        <div class="space-y-6">
            <div class="admin-card p-6">
                <h3 class="font-display font-bold text-lg mb-4 border-b border-gray-100 pb-4 text-ink">Publishing</h3>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Category *</label>
                    <select name="category_id" required class="admin-input w-full p-3 font-serif text-[14px]">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="space-y-4 mb-8">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="w-4 h-4 text-teal bg-white border-gray-300 rounded focus:ring-teal focus:ring-2">
                        <span class="font-serif text-[14px] text-ink">Active Product</span>
                    </label>
                    
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="w-4 h-4 text-teal bg-white border-gray-300 rounded focus:ring-teal focus:ring-2">
                        <span class="font-serif text-[14px] text-ink">Featured Homepage Product</span>
                    </label>
                </div>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $product->sort_order) }}" class="admin-input w-full p-3 font-mono text-[13px]">
                </div>
                
                <h3 class="font-display font-bold text-lg mt-8 mb-4 border-b border-gray-100 pb-4 text-ink">Media</h3>
                
                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Primary Image</label>
                    @if($product->image)
                        <div class="mb-3">
                            <img src="{{ Storage::url($product->image) }}" alt="Current Image" class="w-full h-auto border border-gray-200 rounded-sm">
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*" class="admin-input w-full p-3 font-mono text-[13px] text-gray-600 bg-white">
                    <span class="text-gray-400 text-[10px] mt-1 block">Upload new to replace existing image.</span>
                    @error('image') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label class="block font-mono text-[10px] uppercase tracking-wider text-gray-500 mb-2">Image Gallery</label>
                    @if($product->gallery && count($product->gallery) > 0)
                        <div class="grid grid-cols-3 gap-2 mb-3">
                            @foreach($product->gallery as $galImg)
                                <img src="{{ Storage::url($galImg) }}" alt="Gallery image" class="w-full h-24 object-cover border border-gray-200 rounded-sm shadow-sm">
                            @endforeach
                        </div>
                    @endif
                    <input type="file" name="gallery[]" multiple accept="image/*" class="admin-input w-full p-3 font-mono text-[13px] text-gray-600 bg-white">
                    <span class="text-gray-400 text-[10px] mt-1 block">Upload new to append to existing gallery.</span>
                    @error('gallery.*') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <button type="submit" class="w-full bg-teal hover:bg-teal-light hover:text-teal transition-colors text-white px-8 py-3 font-display font-bold text-[11px] uppercase tracking-widest text-center">
                    Update Product
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

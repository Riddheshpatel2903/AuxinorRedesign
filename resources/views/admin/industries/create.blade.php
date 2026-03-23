@extends('admin.layouts.app')

@section('title', 'Add Industry')
@section('header', 'New Industry')
@section('subheader', 'Add a new sector to the industries served by Auxinor.')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="admin-card p-8">
        <form action="{{ route('admin.industries.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div class="space-y-6">
                    <div>
                        <label class="admin-label">Industry Name *</label>
                        <input type="text" name="name" required value="{{ old('name') }}" class="admin-input p-3 w-full font-display font-bold text-lg" placeholder="e.g. Petrochemicals">
                        @error('name') <p class="text-red-500 text-[10px] mt-1 uppercase font-mono">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="admin-label">Slug (URL Name)</label>
                        <input type="text" name="slug" value="{{ old('slug') }}" class="admin-input p-3 w-full font-mono text-xs" placeholder="auto-generated from name if empty">
                        @error('slug') <p class="text-red-500 text-[10px] mt-1 uppercase font-mono">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-6">
                        <div class="flex-1">
                            <label class="admin-label">Icon (Emoji or SVG)</label>
                            <input type="text" name="icon" value="{{ old('icon') }}" class="admin-input p-3 w-full text-center text-2xl" placeholder="🧪">
                        </div>
                        <div class="w-32">
                            <label class="admin-label">Sort Order</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="admin-input p-3 w-full text-center font-mono">
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-gray-50 border border-line">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 text-teal focus:ring-teal border-gray-300">
                        <label for="is_active" class="ml-3 block text-xs font-mono uppercase tracking-widest text-ink font-bold">Visible on Website</label>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="admin-label">Description</label>
                        <textarea name="desc" rows="6" class="admin-input p-4 w-full font-serif text-sm leading-relaxed" placeholder="Briefly describe the industry and our service scope...">{{ old('desc') }}</textarea>
                        @error('desc') <p class="text-red-500 text-[10px] mt-1 uppercase font-mono">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="admin-label">Industry Image</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-100 border-dashed hover:border-teal/30 transition-colors cursor-pointer relative">
                            <input type="file" name="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewImage(event)">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-300" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="text-xs text-muted font-mono tracking-tight">
                                    <span class="text-teal font-bold uppercase">Upload File</span>
                                    <p class="mt-1">PNG, JPG, WEBP up to 5MB</p>
                                </div>
                            </div>
                            <img id="preview" class="absolute inset-0 w-full h-full object-cover hidden z-0 opacity-50">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t border-line pt-8">
                <a href="{{ route('admin.industries.index') }}" class="px-6 py-3 font-display font-bold text-[10px] uppercase tracking-widest text-muted hover:text-ink transition-colors">Cancel</a>
                <button type="submit" class="bg-ink text-white px-8 py-3 font-display font-bold text-[11px] uppercase tracking-widest hover:bg-teal transition-all shadow-lg">Save Industry</button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const preview = document.getElementById('preview');
        preview.src = reader.result;
        preview.classList.remove('hidden');
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>
@endsection

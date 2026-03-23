@extends('admin.layouts.app')
@section('header','Hero Slideshow')
@section('subheader','Manage background images shown in the hero section')
@section('content')

<div class="space-y-8">
  <!-- PART A — Existing slides grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="slides-grid">
    @foreach($slides as $slide)
      <div class="admin-card bg-ink2 border border-white/10 rounded-sm p-4 text-white relative flex flex-col group" data-id="{{ $slide->id }}">
        
        <div class="flex justify-between mb-3 items-center">
            <span class="text-white/50 cursor-grab hover:text-white transition-colors drag-handle">⠿ Drag</span>
            <form action="{{ route('admin.hero-slides.destroy', $slide) }}" method="POST" class="inline" onsubmit="return confirm('Delete slide?')">
              @csrf @method('DELETE')
              <button class="bg-red-500/20 text-red-400 hover:bg-red-500 hover:text-white px-2 py-1 text-xs rounded-sm transition-colors">Delete</button>
            </form>
        </div>

        <img src="{{ $slide->image_url }}" class="w-full h-32 object-cover mb-4 rounded-sm border border-white/5">
        
        <div class="mt-auto space-y-4">
            <form action="{{ route('admin.hero-slides.update', $slide) }}" method="POST" class="flex flex-col gap-2">
                @csrf @method('PATCH')
                <input type="hidden" name="image_url" value="{{ $slide->image_url }}">
                
                <div class="flex items-center justify-between">
                    <label class="font-mono text-[10px] uppercase text-teal">Active</label>
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" onchange="this.form.submit()" class="w-4 h-4 accent-teal bg-ink border-white/20" {{ $slide->is_active ? 'checked' : '' }}>
                </div>
                
                <div class="flex flex-col gap-1">
                    <label class="font-mono text-[10px] uppercase text-teal">Alt Text</label>
                    <input type="text" name="image_alt" value="{{ $slide->image_alt ?? '' }}"
                           placeholder="Describe this image..." onchange="this.form.submit()"
                           class="bg-ink border border-white/10 text-white text-xs px-2 py-1 rounded-sm w-full">
                </div>
                
                <div class="flex items-center justify-between gap-3">
                    <label class="font-mono text-[10px] uppercase text-teal whitespace-nowrap">Overlay (<span id="val-{{ $slide->id }}">{{ $slide->overlay_opacity }}</span>)</label>
                    <input type="range" name="overlay_opacity" min="0" max="1" step="0.05" value="{{ $slide->overlay_opacity }}" oninput="document.getElementById('val-{{ $slide->id }}').innerText = this.value" onchange="this.form.submit()" class="w-full accent-teal">
                </div>
            </form>
        </div>
      </div>
    @endforeach
  </div>

  <!-- PART B — Add new slide form -->
  <div class="admin-card bg-ink2 border border-white/10 rounded-sm p-6 max-w-2xl text-white">
    <h3 class="font-display font-medium text-lg text-white mb-6 border-b border-white/10 pb-4">Add New Slide</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative items-center">
        <!-- Option 1: Image URL -->
        <form action="{{ route('admin.hero-slides.store') }}" method="POST" class="flex flex-col gap-4">
            @csrf
            <div>
                <label class="font-mono text-[10px] uppercase tracking-wider text-teal block mb-2">Option 1: Image URL</label>
                <input type="text" name="image_url" id="image_url_input" value="{{ old('image_url') }}" required placeholder="https://..." class="w-full bg-ink border border-white/10 text-white font-serif text-[13px] px-3 py-2 focus:border-teal outline-none rounded-sm">
                @error('image_url') <span class="text-red-400 text-[10px] mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="font-mono text-[10px] uppercase tracking-wider text-teal block mb-2">Overlay Opacity</label>
                <div class="flex items-center gap-3">
                    <input type="range" name="overlay_opacity" min="0" max="1" step="0.05" value="0.5" id="new_overlay" oninput="document.getElementById('new_val').innerText = this.value" class="w-full accent-teal">
                    <span id="new_val" class="font-mono text-[10px]">0.5</span>
                </div>
            </div>
            <button type="submit" class="bg-teal text-white hover:bg-teal-dark font-mono text-[11px] uppercase tracking-widest px-4 py-2 mt-2 transition-colors rounded-sm">Add Slide</button>
        </form>

        <!-- Spacer / Divider -->
        <div class="hidden md:flex absolute inset-y-0 left-1/2 -translate-x-1/2 items-center justify-center">
            <div class="w-px h-full bg-white/10 relative">
                <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-ink2 text-white/50 text-xs px-2 font-mono uppercase">OR</span>
            </div>
        </div>

        <!-- Option 2: Upload File -->
        <div class="flex flex-col gap-4">
            <label class="font-mono text-[10px] uppercase tracking-wider text-teal block mb-2">Option 2: Upload Image</label>
            <input type="file" id="file_upload" accept="image/*" class="w-full bg-ink border border-white/10 text-white font-serif text-[13px] px-3 py-2 cursor-pointer focus:border-teal outline-none rounded-sm file:mr-4 file:py-1 file:px-3 file:rounded-sm file:border-0 file:text-[10px] file:font-mono file:uppercase file:bg-white/10 file:text-white hover:file:bg-white/20">
            <button type="button" id="upload_btn" class="bg-ink border border-teal text-teal hover:bg-teal hover:text-white font-mono text-[11px] uppercase tracking-widest px-4 py-2 mt-auto transition-colors rounded-sm flex justify-center items-center">
                <span>Upload & Use</span>
            </button>
            <div id="upload_status" class="text-xs font-mono text-white/60 hidden text-center mt-2"></div>
        </div>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var grid = document.getElementById('slides-grid');
    if (grid && grid.children.length > 0) {
        new Sortable(grid, {
            animation: 150,
            handle: '.drag-handle',
            onEnd: function (evt) {
                var order = Array.from(grid.children).map(el => el.getAttribute('data-id'));
                fetch('{{ route('admin.hero-slides.reorder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order: order })
                });
            }
        });
    }

    const fileInput = document.getElementById('file_upload');
    const uploadBtn = document.getElementById('upload_btn');
    const status = document.getElementById('upload_status');
    const urlInput = document.getElementById('image_url_input');

    uploadBtn.addEventListener('click', async () => {
        if (!fileInput.files.length) return alert('Select an image');
        let data = new FormData();
        data.append('image', fileInput.files[0]);
        data.append('_token', '{{ csrf_token() }}');
        
        const originalText = uploadBtn.innerHTML;
        uploadBtn.innerHTML = 'Uploading...';
        status.innerText = '';
        status.classList.remove('hidden', 'text-red-400', 'text-teal');
        
        try {
            let res = await fetch('{{ route('admin.hero-slides.upload') }}', {
                method: 'POST',
                body: data
            });
            let json = await res.json();
            if (res.ok && json.url) {
                urlInput.value = json.url;
                status.innerText = 'Uploaded! Saving slide...';
                status.classList.remove('hidden');
                status.classList.add('text-teal');
                fileInput.value = '';
                
                // Auto-submit
                urlInput.closest('form').submit();
            } else {
                throw new Error(json.message || 'Upload failed');
            }
        } catch(e) {
            status.innerText = e.message;
            status.classList.remove('hidden');
            status.classList.add('text-red-400');
        } finally {
            if (uploadBtn.innerHTML === 'Uploading...') {
                uploadBtn.innerHTML = originalText;
            }
        }
    });
});
</script>
@endsection

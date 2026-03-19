@extends('admin.layouts.app')

@section('title', 'Products')
@section('header', 'Products Catalogue')
@section('subheader', 'Manage all chemical products, specifications, and availability.')

@section('actions')
<a href="{{ route('admin.products.create') }}" class="bg-teal hover:bg-teal-light hover:text-teal transition-colors text-white px-4 py-2 font-display font-bold text-[11px] uppercase tracking-widest inline-flex items-center">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
    Add Product
</a>
@endsection

@section('content')
<div class="admin-card overflow-hidden" x-data="adminProductSearch()" x-init="attachPaginationListeners()">
    <!-- Filter Bar -->
    <div class="p-4 border-b border-white/5 bg-white/5 flex gap-4">
        <form @submit.prevent="fetchData(null)" class="flex flex-1 gap-4 items-center">
            <input type="text" x-model="search" placeholder="Search name or CAS..." class="admin-input px-3 py-2 w-64 text-sm font-serif" @input.debounce.500ms="fetchData(null)">
            
            <select x-model="category" @change="fetchData(null)" class="admin-input px-3 py-2 w-48 text-sm font-serif">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            
            <button type="button" @click="search=''; category=''; fetchData(null)" x-show="search || category" class="text-teal mx-2 text-[10px] font-mono uppercase tracking-wider hover:text-white transition-colors">Clear</button>
            <span x-show="loading" class="text-teal text-[10px] font-mono uppercase tracking-widest ml-auto blink">Refreshing...</span>
        </form>
    </div>

    <!-- Table Container -->
    <div id="ajax-table-container" :class="loading ? 'opacity-50 pointer-events-none' : 'opacity-100'" class="transition-opacity duration-300">
        @include('admin.products._table')
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('adminProductSearch', () => ({
        search: new URLSearchParams(window.location.search).get('search') || '',
        category: new URLSearchParams(window.location.search).get('category') || '',
        loading: false,

        async fetchData(pageUrl) {
            this.loading = true;
            try {
                let url = pageUrl || '{{ route('admin.products.index') }}';
                let params = new URLSearchParams();
                if (this.search) params.append('search', this.search);
                if (this.category) params.append('category', this.category);
                
                let finalUrl = pageUrl ? url : url + '?' + params.toString();
                
                const response = await fetch(finalUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                
                if (response.ok) {
                    const html = await response.text();
                    document.getElementById('ajax-table-container').innerHTML = html;
                    window.history.pushState({}, '', finalUrl);
                    this.attachPaginationListeners();
                }
            } catch (error) {
                console.error("AJAX Error:", error);
            } finally {
                this.loading = false;
            }
        },
        
        attachPaginationListeners() {
            this.$nextTick(() => {
                const links = document.querySelectorAll('#ajax-table-container nav a');
                links.forEach(link => {
                    link.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.fetchData(link.href);
                    });
                });
            });
        }
    }));
});
</script>
@endsection

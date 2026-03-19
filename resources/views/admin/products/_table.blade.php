    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-white/10">
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-white/50 w-16">Status</th>
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-white/50">Product Name / CAS</th>
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-white/50">Category</th>
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-white/50 text-right w-32">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($products as $product)
                <tr class="hover:bg-white/5 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="w-3 h-3 rounded-full {{ $product->is_active ? 'bg-teal' : 'bg-red-500' }}" title="{{ $product->is_active ? 'Active' : 'Inactive' }}"></div>
                        @if($product->is_featured)
                            <svg class="w-4 h-4 text-teal-2 mt-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-display font-bold text-[15px] text-white group-hover:text-teal-2 transition-colors">{{ $product->name }}</div>
                        <div class="font-mono text-[10px] text-white/40 mt-1 uppercase">
                            CAS: {{ $product->cas_number ?: 'N/A' }} 
                            @if($product->chemical_formula) | Formula: {{ $product->chemical_formula }} @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-block px-2 py-1 bg-white/10 text-white/80 font-mono text-[10px] rounded uppercase tracking-wider">{{ $product->category->name ?? 'Uncategorized' }}</span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-white/40 hover:text-white transition-colors p-1" title="Edit">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <a href="{{ route('products.show', [$product->category->slug ?? 'misc', $product->slug]) }}" target="_blank" class="text-white/40 hover:text-teal transition-colors p-1" title="View on Frontend">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-white/40 hover:text-red-400 transition-colors p-1" title="Delete">
                                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-white/40 font-serif italic text-sm">
                        No products found matching your criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t border-white/5">
        {{ $products->links('pagination::tailwind') }}
    </div>

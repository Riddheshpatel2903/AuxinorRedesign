@props(['data'])
@php
    $title = $data['title'] ?? 'Our Catalogue';
    $catalogueType = $data['catalogue_type'] ?? 'industries';
    $layout = $data['layout'] ?? 'grid';

    $items = collect();
    try {
        if ($catalogueType === 'industries') {
            $items = \App\Models\Industry::active()->ordered()->get();
        } elseif ($catalogueType === 'products') {
            $items = \App\Models\Product::active()->featured()->ordered()->limit(8)->get();
        } elseif ($catalogueType === 'categories') {
            $items = \App\Models\ProductCategory::active()->ordered()->get();
        } elseif ($catalogueType === 'insights') {
            $items = \App\Models\BlogPost::published()->latest('published_at')->limit(6)->get();
        }
    } catch (\Exception $e) {
        // Fallback if models or tables don't exist
    }
@endphp

<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        @if($title)
            <h2 class="text-3xl font-bold mb-12 text-center text-gray-900">{{ $title }}</h2>
        @endif

        @if($items->isNotEmpty())
            @if($layout === 'carousel')
                <div class="relative overflow-hidden group">
                    <div class="flex space-x-6 overflow-x-auto pb-8 snap-x" style="scrollbar-width: none; -ms-overflow-style: none;">
                        @foreach($items as $item)
                            <div class="flex-shrink-0 w-80 bg-gray-50 rounded-2xl p-6 border border-gray-100 hover:shadow-2xl hover:border-teal-500/20 transition-all duration-300 snap-start">
                                @php
                                    $image = $item->image ?? ($item->gallery?->first()?->image_path ?? null);
                                    $name = $item->name ?? $item->title;
                                    $desc = $item->description ?? ($item->excerpt ?? '');
                                @endphp
                                @if($image)
                                    <div class="overflow-hidden rounded-xl mb-6">
                                        <img src="{{ asset($image) }}" alt="{{ $name }}" class="w-full h-48 object-cover hover:scale-110 transition-transform duration-500">
                                    </div>
                                @endif
                                <h3 class="text-xl font-bold mb-3 text-gray-900">{{ $name }}</h3>
                                @if($desc)
                                    <p class="text-gray-600 text-sm leading-relaxed">{{ Str::limit(strip_tags($desc), 120) }}</p>
                                @endif
                                <div class="mt-6">
                                    <a href="{{ $item->url ?? '#' }}" class="text-teal-600 font-bold text-sm flex items-center gap-1 hover:gap-2 transition-all">
                                        View Details <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($items as $item)
                        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 hover:shadow-2xl hover:border-teal-500/20 transition-all duration-300">
                            @php
                                $image = $item->image ?? ($item->gallery?->first()?->image_path ?? null);
                                $name = $item->name ?? $item->title;
                                $desc = $item->description ?? ($item->excerpt ?? '');
                            @endphp
                            @if($image)
                                <div class="overflow-hidden rounded-xl mb-6">
                                    <img src="{{ asset($image) }}" alt="{{ $name }}" class="w-full h-48 object-cover hover:scale-110 transition-transform duration-500">
                                </div>
                            @endif
                            <h3 class="text-xl font-bold mb-3 text-gray-900">{{ $name }}</h3>
                            @if($desc)
                                <p class="text-gray-600 text-sm leading-relaxed">{{ Str::limit(strip_tags($desc), 120) }}</p>
                            @endif
                            <div class="mt-6">
                                <a href="{{ $item->url ?? '#' }}" class="text-teal-600 font-bold text-sm flex items-center gap-1 hover:gap-2 transition-all">
                                    View Details <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <div class="text-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                <p class="text-gray-500 font-medium italic">No entries found for {{ $catalogueType }} at the moment.</p>
            </div>
        @endif
    </div>
</section>
@props(['data'])
<section class="py-16 bg-teal-900 text-white">
    <div class="max-w-6xl mx-auto px-6">
        @if($data['title'] ?? false)
            <h2 class="text-2xl font-bold mb-12 text-center text-teal-300 uppercase tracking-widest" data-element-key="title">{{ $data['title'] }}</h2>
        @endif

        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            @foreach($data['items'] ?? [] as $i => $item)
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-extrabold mb-2 text-white tabular-nums" data-element-key="items.{{ $i }}.number">
                        {{ $item['number'] ?? ($item['value'] ?? '0') }}
                    </div>
                    <div class="text-teal-200 text-sm font-medium uppercase tracking-wider" data-element-key="items.{{ $i }}.label">
                        {{ $item['label'] ?? '' }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

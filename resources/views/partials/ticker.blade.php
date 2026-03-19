<style>
    @keyframes ticker {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    .ticker-track {
        display: flex;
        width: max-content;
        animation: ticker 30s linear infinite;
    }
    .ticker-wrap:hover .ticker-track {
        animation-play-state: paused;
    }
</style>
<div class="h-[36px] bg-teal overflow-hidden flex items-center ticker-wrap relative">
    <div class="ticker-track">
        @php
            $tickerItems = [];
            if(isset($globalCategories)) {
                foreach($globalCategories as $cat) {
                    $tickerItems[] = $cat->name;
                    foreach($cat->products->take(2) ?? [] as $prod) {
                        $tickerItems[] = $prod->name;
                    }
                }
            }
            if(empty($tickerItems)) {
                $tickerItems = ['Butyl Acrylate', 'Toluene', 'Mono Ethylene Glycol', 'Iso Butanol', 'Acetone'];
            }
            // Duplicate for seamless scroll
            $items = array_merge($tickerItems, $tickerItems, $tickerItems, $tickerItems);
        @endphp
        
        @foreach($items as $item)
            <div class="font-mono text-[10px] tracking-[2px] text-white/70 uppercase px-4 whitespace-nowrap">
                {{ $item }}
                <span class="text-white/30 ml-4">◆</span>
            </div>
        @endforeach
    </div>
</div>

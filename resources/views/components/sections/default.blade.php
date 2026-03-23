<section data-section-id="{{ $section->id }}" data-section-key="{{ $section->section_key }}" style="{{ $section->getStyleString() }}" class="section-default">
    <div class="max-w-6xl mx-auto px-8 py-12">
        <h2 class="text-3xl font-bold">{{ $section->section_label ?? ucfirst(str_replace('_', ' ', $section->section_key)) }}</h2>
        <div class="prose mt-4">
            @foreach($section->sectionContents as $content)
                @if($content->type === 'html')
                    <div data-element-id="{{ $content->key }}">{!! $content->getValue() !!}</div>
                @elseif($content->type === 'text')
                    <p data-element-id="{{ $content->key }}">{{ $content->getValue() }}</p>
                @elseif($content->type === 'image')
                    <img src="{{ $content->getImageSrc() }}" alt="{{ $content->getImageMeta()['alt'] ?? '' }}" class="w-full" data-element-id="{{ $content->key }}" />
                @endif
            @endforeach
        </div>
    </div>
</section>

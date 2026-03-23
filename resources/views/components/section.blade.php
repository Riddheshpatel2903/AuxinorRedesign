 {{-- Generic section wrapper (fallback) -- }}
<section data-section-id="{{ $section->id }}" data-section-key="{{ $section->section_key }}" class="section-{{ $section->section_key }}" style="{{ $section->getStyleString() }}">
    @if($section->sectionContents->isNotEmpty())
        @foreach($section->sectionContents as $content)
            <div data-key="{{ $content->key }}" class="section-content {{ $content->type }}">
                @if($content->type === 'image')
                    <img src="{{ $content->getImageSrc() }}" alt="{{ $content->getImageMeta()['alt'] ?? '' }}" />
                @elseif($content->type === 'url')
                    <a href="{{ $content->getLinkMeta()['href'] ?? '#' }}">{{ $content->getLinkMeta()['text'] ?? $content->getValue() }}</a>
                @else
                    {!! $content->getValue() !!}
                @endif
            </div>
        @endforeach
    @else
        <div class="legacy-content">
            @foreach($section->elements as $element)
                <div data-key="{{ $element->element_key }}" class="element {{ $element->element_type }}">
                    {!! $element->content !!}
                </div>
            @endforeach
        </div>
    @endif
</section>

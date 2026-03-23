@extends('layouts.app')

@section('content')
    <main class="page-builder">
        @foreach($content ?? [] as $index => $section)
            @php $type = str_replace('_', '-', $section['type']); @endphp
            <div data-section-index="{{ $index }}" data-section-type="{{ $section['type'] }}">
                <x-dynamic-component :component="'sections.' . $type" :data="$section['props']" :index="$index" />
            </div>
        @endforeach
    </main>
@endsection

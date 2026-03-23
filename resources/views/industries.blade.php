@extends('layouts.app')

@section('content')
    @foreach($content ?? [] as $index => $section)
        @php $type = str_replace('_', '-', $section['type']); @endphp
        <x-dynamic-component 
            :component="'sections.' . $type" 
            :data="$section['props']" 
            :index="$index"
        />
    @endforeach
@endsection

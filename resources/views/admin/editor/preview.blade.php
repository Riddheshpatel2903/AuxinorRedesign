@extends('layouts.app')

@section('title', $page->title . ' - Preview')

@section('content')
    <div class="bg-gray-100 min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-6">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h1 class="text-2xl font-bold mb-6 text-center">{{ $page->title }} - Preview</h1>

                <div class="border rounded-lg p-6 bg-gray-50">
                    @foreach($sections as $sec)
                        <x-section-renderer :section="$sec" :global-settings="$settings" :stats="$stats" />
                    @endforeach
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('admin.editor.page', $page->slug) }}" class="btn-secondary">Back to Editor</a>
                </div>
            </div>
        </div>
    </div>
@endsection
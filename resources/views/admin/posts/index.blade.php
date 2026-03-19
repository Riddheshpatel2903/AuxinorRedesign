@extends('admin.layouts.app')

@section('title', 'Insights')
@section('header', 'Market Insights')
@section('subheader', 'Manage blog posts, market reports, and internal news.')

@section('actions')
<a href="{{ route('admin.posts.create') }}" class="bg-teal hover:bg-teal-light hover:text-teal transition-colors text-white px-4 py-2 font-display font-bold text-[11px] uppercase tracking-widest inline-flex items-center">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
    Write Post
</a>
@endsection

@section('content')
<div class="admin-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-gray-500 w-24">Date</th>
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-gray-500 w-24">Status</th>
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-gray-500">Post Title</th>
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-gray-500">Category / Author</th>
                    <th class="px-6 py-4 font-mono text-[10px] uppercase tracking-wider text-gray-500 text-right w-32">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($posts as $post)
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-4 font-mono text-[11px] text-gray-500 whitespace-nowrap">
                        {{ $post->published_at ? $post->published_at->format('d M, Y') : 'Not Set' }}
                    </td>
                    <td class="px-6 py-4">
                        @if($post->is_published)
                            <span class="inline-block px-2 py-1 bg-teal/10 text-teal-700 border border-teal/20 font-mono text-[9px] uppercase tracking-wider rounded font-bold">Published</span>
                        @else
                            <span class="inline-block px-2 py-1 bg-gray-100 text-gray-500 border border-gray-200 font-mono text-[9px] uppercase tracking-wider rounded">Draft</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-display font-bold text-[15px] text-ink group-hover:text-teal transition-colors line-clamp-1">{{ $post->title }}</div>
                        <div class="font-mono text-[10px] text-gray-400 mt-1">/{{ $post->slug }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-mono text-[10px] uppercase tracking-wider text-ink">{{ $post->category }}</div>
                        <div class="font-serif italic text-[12px] text-gray-600 mt-1">By {{ $post->author ?: 'Admin' }}</div>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.posts.edit', $post) }}" class="text-gray-400 hover:text-ink transition-colors p-1" title="Edit">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <a href="{{ route('insights.show', $post->slug) }}" target="_blank" class="text-gray-400 hover:text-teal transition-colors p-1" title="View on Frontend">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors p-1" title="Delete">
                                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-serif italic text-sm">
                        No market insights published yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t border-gray-100">
        {{ $posts->links('pagination::tailwind') }}
    </div>
</div>
@endsection

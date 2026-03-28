@extends('admin.grapesjs.layout')
@section('title', 'All Pages')

@push('styles')
<style>
    .pages-container { max-width: 900px; margin: 40px auto; padding: 0 24px; }
    .pages-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
    .pages-header h1 { font-size: 22px; font-weight: 600; }
    .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none; border: none; transition: background 0.15s; }
    .btn-primary { background: #6366f1; color: #fff; }
    .btn-primary:hover { background: #5457e0; }
    .pages-table { width: 100%; border-collapse: collapse; }
    .pages-table th { text-align: left; padding: 10px 16px; font-size: 11px; color: #666; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #2a2a2a; }
    .pages-table td { padding: 14px 16px; font-size: 14px; border-bottom: 1px solid #1e1e1e; }
    .pages-table tr:hover td { background: #1a1a1a; }
    .badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: 11px; font-weight: 500; }
    .badge-published { background: #14532d; color: #4ade80; }
    .badge-draft { background: #292524; color: #78716c; }
    .edit-link { color: #818cf8; text-decoration: none; font-size: 13px; }
    .edit-link:hover { color: #a5b4fc; }
    .new-page-form { display: flex; gap: 10px; margin-top: 32px; padding: 20px; background: #1a1a1a; border-radius: 12px; }
    .new-page-form input { flex: 1; padding: 8px 14px; background: #111; border: 1px solid #333; border-radius: 8px; color: #fff; font-size: 14px; }
    .new-page-form input:focus { outline: none; border-color: #6366f1; }
</style>
@endpush

@section('content')
<div class="pages-container">
    <div class="pages-header"><h1>GrapesJS Managed Pages</h1></div>
    <table class="pages-table">
        <thead>
            <tr>
                <th>Page</th><th>Slug</th><th>Status</th><th>Last edited</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pages as $page)
            <tr>
                <td>{{ $page->page_title }}</td>
                <td><code style="font-size:12px;color:#888">/{{ $page->page_slug }}</code></td>
                <td><span class="badge {{ $page->is_published ? 'badge-published' : 'badge-draft' }}">{{ $page->is_published ? 'Published' : 'Draft' }}</span></td>
                <td style="color:#666;font-size:13px">{{ $page->last_edited_at ? $page->last_edited_at->diffForHumans() : 'Never' }}{{ $page->last_edited_by ? ' by '.$page->last_edited_by : '' }}</td>
                <td><a href="{{ route('admin.grapesjs.editor.show', $page->page_slug) }}" class="edit-link">Edit →</a></td>
            </tr>
            @empty
            <tr><td colspan="5" style="color:#666;text-align:center;padding:40px">No GrapesJS pages yet. Create one below.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="new-page-form">
        <input type="text" id="new-slug" placeholder="Enter page slug (e.g. services, portfolio, custom-page)" />
        <button class="btn btn-primary" onclick="openNewPage()">+ New Page</button>
    </div>
</div>
<script>
function openNewPage() {
    const slug = document.getElementById('new-slug').value.trim().toLowerCase().replace(/\s+/g,'-').replace(/[^a-z0-9-]/g,'');
    if (!slug) { alert('Please enter a page slug'); return; }
    window.location.href = '/admin/grapesjs/editor/' + slug;
}
</script>
@endsection

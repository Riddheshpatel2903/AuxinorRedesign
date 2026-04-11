@extends('admin.grapesjs.layout')
@section('title', 'Editing: ' . $page->page_title)

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/grapesjs/dist/css/grapes.min.css">
<link rel="stylesheet" href="https://unpkg.com/grapesjs-preset-webpage/dist/grapesjs-preset-webpage.min.css">
<style>
    body { overflow: hidden; }
    #editor-wrapper { display: flex; flex-direction: column; height: calc(100vh - 52px); }
    #editor-toolbar { display: flex; align-items: center; gap: 12px; padding: 0 16px; height: 48px; background: #161616; border-bottom: 1px solid #2a2a2a; flex-shrink: 0; }
    .toolbar-title { font-size: 14px; font-weight: 500; color: #ccc; margin-right: auto; }
    .toolbar-btn { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 7px; font-size: 13px; font-weight: 500; cursor: pointer; border: none; transition: all 0.15s; }
    .toolbar-btn-ghost { background: transparent; color: #888; border: 1px solid #333; }
    .toolbar-btn-ghost:hover { background: #222; color: #ccc; }
    .toolbar-btn-save { background: #6366f1; color: #fff; }
    .toolbar-btn-save:hover { background: #5457e0; }
    .toolbar-btn-publish { background: #166534; color: #4ade80; }
    .toolbar-btn-unpublish { background: #3f3f46; color: #a1a1aa; }
    #save-status { font-size: 12px; color: #555; min-width: 140px; }
    #gjs { flex: 1; overflow: hidden; }
    .gjs-one-bg { background-color: #111 !important; }
    .gjs-two-color { color: #ddd !important; }
    .gjs-three-bg { background-color: #1a1a1a !important; }
    .gjs-four-color, .gjs-four-color-h:hover { color: #aaa !important; }
    .gjs-pn-panels { background: #161616; }
    .gjs-pn-panel { background: #1a1a1a; }
</style>
@endpush

@section('content')
<div id="editor-wrapper">
    <div id="editor-toolbar">
        <span class="toolbar-title">{{ $page->page_title }}</span>
        <span id="save-status">All changes saved</span>
        <button class="toolbar-btn toolbar-btn-ghost" onclick="previewPage()">Preview</button>
        <button
            id="publish-btn"
            class="toolbar-btn {{ $page->is_published ? 'toolbar-btn-unpublish' : 'toolbar-btn-publish' }}"
            onclick="togglePublish()"
        >{{ $page->is_published ? 'Unpublish' : 'Publish' }}</button>
        <button class="toolbar-btn toolbar-btn-save" onclick="saveNow()">Save</button>
    </div>
    <div id="gjs"></div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/grapesjs@0.21.12/dist/grapes.min.js"></script>
<script src="https://unpkg.com/grapesjs-preset-webpage@1.0.2/dist/grapesjs-preset-webpage.min.js"></script>
<script>
const SLUG = '{{ $slug }}';
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
let isPublished = {{ $page->is_published ? 'true' : 'false' }};
let saveTimeout = null;

const editor = grapesjs.init({
    container: '#gjs',
    fromElement: false,
    height: '100%',
    width: 'auto',
    plugins: ['gjs-preset-webpage'],
    storageManager: { autoload: 0, type: 'none' },
    assetManager: {
        uploadText: 'Drop images here or click to upload',
        upload: '/admin/grapesjs/upload/image',
        uploadName: 'files',
        headers: { 'X-CSRF-TOKEN': CSRF },
        multiUpload: true,
        autoAdd: true,
    },
    styleManager: {
        sectors: [
            {
                name: 'General', open: false,
                buildProps: ['float', 'display', 'position', 'top', 'right', 'left', 'bottom'],
            },
            {
                name: 'Layout', open: false,
                buildProps: ['width', 'height', 'max-width', 'min-height', 'margin', 'padding'],
            },
            {
                name: 'Typography', open: true,
                buildProps: ['font-family', 'font-size', 'font-weight', 'letter-spacing', 'color', 'line-height', 'text-align', 'text-shadow'],
                properties: [
                    { property: 'text-align', list: [{ value: 'left', name: 'Left' }, { value: 'center', name: 'Center' }, { value: 'right', name: 'Right' }, { value: 'justify', name: 'Justify' }] },
                ],
            },
            {
                name: 'Decorations', open: false,
                buildProps: ['background-color', 'background-image', 'background-repeat', 'background-attachment', 'background-position', 'background-size', 'border-radius', 'border', 'box-shadow'],
                properties: [
                    {
                        property: 'background-image',
                        type: 'file',
                        functionName: 'url',
                    },
                ],
            },
            {
                name: 'Animations & Transforms', open: false,
                buildProps: ['transition', 'perspective', 'transform'],
                properties: [
                    {
                        property: 'transition',
                        type: 'stack',
                        properties: [
                            { property: 'transition-property', type: 'select', list: [{ value: 'all' }, { value: 'opacity' }, { value: 'transform' }, { value: 'background-color' }] },
                            { property: 'transition-duration', type: 'integer', units: ['s', 'ms'], defaults: '0.3s' },
                            { property: 'transition-timing-function', type: 'select', list: [{ value: 'linear' }, { value: 'ease' }, { value: 'ease-in' }, { value: 'ease-out' }, { value: 'ease-in-out' }] },
                        ],
                    },
                    {
                        property: 'transform',
                        type: 'stack',
                        properties: [
                            { property: 'transform-type', type: 'select', list: [{ value: 'rotate' }, { value: 'scale' }, { value: 'translate' }] },
                            { property: 'transform-x', type: 'integer', units: ['px', '%', 'deg'] },
                            { property: 'transform-y', type: 'integer', units: ['px', '%', 'deg'] },
                        ],
                    },
                ],
            },
        ],
    },
    layerManager: {
        appendTo: '.gjs-pn-views-container',
    },
    traitManager: {
        appendTo: '.gjs-pn-views-container',
    },
    selectorManager: {
        appendTo: '.gjs-pn-views-container',
    },
    deviceManager: {
        devices: [
            { name: 'Desktop', width: '' },
            { name: 'Tablet', width: '768px', widthMedia: '992px' },
            { name: 'Mobile', width: '375px', widthMedia: '480px' },
        ],
    },
    blockManager: {
        blocks: [
            { id: 'hero', label: 'Hero Section', category: 'Sections',
              content: `<section style="padding:80px 40px;text-align:center;background:#1a1a2e"><h1 style="font-size:48px;font-weight:700;color:#fff;margin-bottom:16px">Your Headline Here</h1><p style="font-size:18px;color:#aaa;margin-bottom:32px;max-width:600px;margin-left:auto;margin-right:auto">A compelling subheadline that explains your value proposition.</p><a href="#" style="display:inline-block;padding:14px 32px;background:#6366f1;color:#fff;border-radius:8px;font-size:16px;font-weight:600;text-decoration:none">Get Started</a></section>` },
            { id: 'two-col', label: '2 Columns', category: 'Layout',
              content: `<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;padding:40px"><div style="padding:24px;background:#1a1a1a;border-radius:12px"><h3 style="color:#fff;margin-bottom:12px">Column 1</h3><p style="color:#888">Edit this content</p></div><div style="padding:24px;background:#1a1a1a;border-radius:12px"><h3 style="color:#fff;margin-bottom:12px">Column 2</h3><p style="color:#888">Edit this content</p></div></div>` },
            { id: 'three-col', label: '3 Columns', category: 'Layout',
              content: `<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:24px;padding:40px"><div style="padding:24px;background:#1a1a1a;border-radius:12px"><h3 style="color:#fff;margin-bottom:8px">Feature 1</h3><p style="color:#888;font-size:14px">Description</p></div><div style="padding:24px;background:#1a1a1a;border-radius:12px"><h3 style="color:#fff;margin-bottom:8px">Feature 2</h3><p style="color:#888;font-size:14px">Description</p></div><div style="padding:24px;background:#1a1a1a;border-radius:12px"><h3 style="color:#fff;margin-bottom:8px">Feature 3</h3><p style="color:#888;font-size:14px">Description</p></div></div>` },
            { id: 'text-block', label: 'Text Block', category: 'Content',
              content: `<div style="padding:40px;max-width:800px;margin:0 auto"><h2 style="font-size:32px;font-weight:700;margin-bottom:16px;color:#fff">Section Heading</h2><p style="font-size:16px;line-height:1.7;color:#aaa">Your paragraph text goes here. Click to edit this text directly in the editor.</p></div>` },
            { id: 'image-block', label: 'Image', category: 'Media',
              content: '<img src="https://placehold.co/1200x600/1a1a1a/666?text=Click+to+change" style="width:100%;display:block;border-radius:12px" alt=""/>' },
            { id: 'cta', label: 'Call to Action', category: 'Sections',
              content: `<section style="padding:80px 40px;text-align:center;background:#18181b;border-top:1px solid #27272a"><h2 style="font-size:36px;font-weight:700;color:#fff;margin-bottom:12px">Ready to get started?</h2><p style="color:#888;margin-bottom:28px">Join thousands of customers using our product.</p><a href="#" style="display:inline-block;padding:14px 32px;background:#6366f1;color:#fff;border-radius:8px;font-size:15px;font-weight:600;text-decoration:none;margin-right:12px">Get Started</a><a href="#" style="display:inline-block;padding:14px 32px;background:transparent;color:#aaa;border:1px solid #333;border-radius:8px;font-size:15px;font-weight:600;text-decoration:none">Learn More</a></section>` },
            { id: 'testimonial', label: 'Testimonial', category: 'Content',
              content: `<div style="padding:40px;max-width:700px;margin:0 auto;text-align:center"><blockquote style="font-size:20px;color:#ddd;font-style:italic;line-height:1.6;margin-bottom:24px">"This product transformed how our team works. Highly recommended!"</blockquote><p style="font-size:14px;font-weight:600;color:#888">— Jane Smith, CEO at Company</p></div>` },
            { id: 'navbar', label: 'Navbar', category: 'Sections',
              content: `<nav style="display:flex;align-items:center;justify-content:space-between;padding:16px 40px;background:#111;border-bottom:1px solid #222"><a href="/" style="font-size:20px;font-weight:700;color:#fff;text-decoration:none">Auxinor</a><div style="display:flex;gap:24px"><a href="#" style="color:#aaa;text-decoration:none;font-size:14px">Home</a><a href="#" style="color:#aaa;text-decoration:none;font-size:14px">About</a><a href="#" style="color:#aaa;text-decoration:none;font-size:14px">Services</a><a href="#" style="color:#aaa;text-decoration:none;font-size:14px">Contact</a></div><a href="#" style="padding:8px 18px;background:#6366f1;color:#fff;border-radius:7px;font-size:13px;font-weight:600;text-decoration:none">Get Started</a></nav>` },
            { id: 'divider', label: 'Divider', category: 'Layout',
              content: '<hr style="border:none;border-top:1px solid #27272a;margin:40px 0"/>' },
            { id: 'spacer', label: 'Spacer', category: 'Layout',
              content: '<div style="height:60px"></div>' },
        ],
    },
    canvasCss: '* { box-sizing: border-box; } body { font-family: system-ui, sans-serif; margin: 0; }',
    deviceManager: {
        devices: [
            { name: 'Desktop', width: '' },
            { name: 'Tablet', width: '768px', widthMedia: '992px' },
            { name: 'Mobile', width: '375px', widthMedia: '480px' },
        ],
    },
});

// Load saved content and assets
async function loadContent() {
    try {
        // Load page data
        const res = await fetch(`/admin/grapesjs/editor/${SLUG}/load`, { headers: { 'X-CSRF-TOKEN': CSRF } });
        const data = await res.json();
        if (data.components && data.components !== '[]') {
            editor.loadData({ pages: [{ component: JSON.parse(data.components) }] });
        }
        if (data.styles && data.styles !== '[]') {
            editor.setStyle(JSON.parse(data.styles));
        }

        // Load existing images
        const assetRes = await fetch('/admin/grapesjs/upload/images', { headers: { 'X-CSRF-TOKEN': CSRF } });
        const assets = await assetRes.json();
        editor.AssetManager.add(assets);
    } catch(e) { console.error('Load failed:', e); }
}
loadContent();

// Save
async function saveNow() {
    setSaveStatus('Saving...');
    try {
        const components = JSON.stringify(editor.getComponents());
        const styles = JSON.stringify(editor.getStyle());
        const css = editor.getCss();
        const html = `<!DOCTYPE html><html><head><meta charset="UTF-8"><style>${css}</style></head><body>${editor.getHtml()}</body></html>`;
        const res = await fetch(`/admin/grapesjs/editor/${SLUG}/save`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ components, styles, html }),
        });
        const data = await res.json();
        setSaveStatus(data.status === 'saved' ? 'Saved at ' + new Date().toLocaleTimeString() : 'Save failed');
    } catch(e) { setSaveStatus('Error saving'); console.error(e); }
}

// Auto-save after 3s inactivity
editor.on('change:changesCount', () => {
    setSaveStatus('Unsaved changes...');
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(saveNow, 3000);
});

// Sidebar Buttons for Layers/Traits/Blocks
const pn = editor.Panels;
const config = editor.getConfig();
pn.addButton('views', [
    { id: 'open-sm', className: 'fa fa-paint-brush', command: 'open-sm', active: true, attributes: { title: 'Style Manager' } },
    { id: 'open-layers', className: 'fa fa-bars', command: 'open-layers', attributes: { title: 'Layers' } },
    { id: 'open-traits', className: 'fa fa-cog', command: 'open-traits', attributes: { title: 'Traits' } },
    { id: 'open-blocks', className: 'fa fa-th-large', command: 'open-blocks', attributes: { title: 'Blocks' } },
]);

// Publish toggle
async function togglePublish() {
    await saveNow();
    const res = await fetch(`/admin/grapesjs/editor/${SLUG}/publish`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
    });
    const data = await res.json();
    isPublished = data.is_published;
    const btn = document.getElementById('publish-btn');
    btn.textContent = isPublished ? 'Unpublish' : 'Publish';
    btn.className = 'toolbar-btn ' + (isPublished ? 'toolbar-btn-unpublish' : 'toolbar-btn-publish');
}

// Preview
function previewPage() {
    saveNow().then(() => window.open(`/${SLUG}`, '_blank'));
}

function setSaveStatus(msg) {
    document.getElementById('save-status').textContent = msg;
}

// Ctrl+S / Cmd+S shortcut
document.addEventListener('keydown', (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key === 's') { e.preventDefault(); saveNow(); }
});
</script>
@endpush

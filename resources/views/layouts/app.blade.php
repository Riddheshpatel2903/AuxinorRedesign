<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Auxinor Chemicals — Chemical Trading & Distribution')</title>
    <meta name="description" content="@yield('meta_desc', 'Specialists in procurement, bulk distribution, and surplus chemical trading — serving India\'s top industrial sectors since 2017.')">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@400;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased min-h-screen flex flex-col">
    @include('partials.topbar')
    @include('partials.navbar')
    
    <main class="flex-grow">
        @yield('content')
    </main>
    
    @include('partials.footer')
    
    @stack('scripts')
    
    @if(auth()->check())
        <!-- Admin CMS Controls -->
        <div class="fixed bottom-6 right-6 z-[9999] flex flex-col gap-3">
            <a href="{{ route('admin.editor.index') }}" class="bg-teal text-ink font-mono text-[10px] uppercase font-bold tracking-widest px-5 py-3 rounded-sm shadow-[0_10px_40px_rgba(0,0,0,0.5)] border border-teal-light flex items-center hover:scale-105 transition-transform">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Visual Editor
            </a>
            <a href="{{ route('admin.products.index') }}" class="bg-ink text-white font-mono text-[10px] uppercase font-bold tracking-widest px-5 py-3 rounded-sm shadow-[0_10px_40px_rgba(0,0,0,0.5)] border border-white/20 flex items-center hover:bg-white hover:text-ink transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Manage Products
            </a>
        </div>
        
        @if(!request()->has('editor_mode'))
        <style>
            /* Frontend Admin Visual Editor */
            .cms-editable {
                position: relative;
                transition: outline 0.2s;
            }
            .cms-editable:hover {
                outline: 2px dashed #00ffd5;
                outline-offset: -4px;
                cursor: pointer;
            }
            .cms-editable:hover::after {
                content: attr(data-cms-label);
                position: absolute;
                top: 8px;
                right: 8px;
                background: #00ffd5;
                color: #0b1118;
                font-family: monospace;
                font-size: 10px;
                font-weight: bold;
                text-transform: uppercase;
                letter-spacing: 1px;
                padding: 6px 10px;
                border-radius: 2px;
                z-index: 50;
                pointer-events: none;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const editorUrl = '{{ route('admin.editor.page', request()->path() === '/' ? 'home' : request()->path()) }}';
                document.querySelectorAll('.cms-editable').forEach(el => {
                    if(!el.hasAttribute('data-cms-label')) {
                        el.setAttribute('data-cms-label', 'Open Visual Editor');
                    }
                    el.addEventListener('click', (e) => {
                        // Let specific inner links work, otherwise capture click to edit
                        if(e.target.tagName.toLowerCase() !== 'a' && !e.target.closest('a')) {
                            e.preventDefault();
                            e.stopPropagation();
                            window.location.href = editorUrl;
                        }
                    });
                });
            });
        </script>
        @endif
        @if(request()->has('editor_mode') && auth()->check())
            <script src="{{ asset('js/editor-receiver.js') }}"></script>
            <script>
                document.addEventListener('click', e => {
                    const link = e.target.closest('a');
                    // Prevent navigation on links inside the editor, but let editor JS handle the rest
                    if (link && !link.hasAttribute('target')) {
                        e.preventDefault();
                    }
                });
            </script>
            <style>
                body.editor-mode [data-section-id] {
                    position: relative;
                    outline: 2px solid transparent;
                    outline-offset: -2px;
                    transition: outline-color .15s;
                    min-height: 40px;
                }
                body.editor-mode [data-element-id] {
                    outline: 1px dashed transparent;
                    outline-offset: 2px;
                    transition: outline-color .15s;
                    cursor: text;
                }
                body.editor-mode [data-section-id]:hover::before {
                    content: attr(data-section-label);
                    position: absolute; top: 0; left: 0;
                    background: #f59e0b; color: #000;
                    font-family: monospace; font-size: 9px;
                    letter-spacing: 1px; padding: 2px 8px;
                    z-index: 9999; pointer-events: none;
                    text-transform: uppercase;
                }
            </style>
        @endif
    @endif
    
    @php
        $cmsSections = \App\Models\PageSection::forPage(request()->path() === '/' ? 'home' : request()->path())->get();
    @endphp
    @if($cmsSections->count() > 0)
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cmsData = @json($cmsSections);
            cmsData.forEach(sec => {
                const secEl = document.querySelector(`[data-section-id="${sec.id}"]`);
                if (!secEl) return;
                
                // Visible?
                if (sec.is_visible === 0) secEl.style.display = 'none';
                
                // Section Background?
                if (sec.content && sec.content.bg_image_url) {
                    secEl.style.backgroundImage = `url('${sec.content.bg_image_url}')`;
                    secEl.style.backgroundSize = 'cover';
                    secEl.style.backgroundPosition = 'center';
                    if (sec.content.bg_overlay !== undefined) {
                        let ov = secEl.querySelector('.ed-bg-overlay');
                        if (!ov) {
                            ov = document.createElement('div');
                            ov.className = 'ed-bg-overlay';
                            ov.style.cssText = 'position:absolute;inset:0;pointer-events:none;z-index:1';
                            secEl.style.position = 'relative';
                            secEl.prepend(ov);
                        }
                        ov.style.background = `rgba(13,17,23,${sec.content.bg_overlay})`;
                    }
                }
                
                // Section Styles
                if (sec.styles) Object.assign(secEl.style, sec.styles);
                
                // Loop Content Nodes for elements
                if (sec.content) {
                    Object.keys(sec.content).forEach(key => {
                        if (key.startsWith('el_href_')) {
                            const elId = key.replace('el_href_', '');
                            const node = secEl.querySelector(`[data-element-id="${elId}"]`);
                            if (node) node.href = sec.content[key];
                        } else if (key.startsWith('el_style_')) {
                            const elId = key.replace('el_style_', '');
                            const node = secEl.querySelector(`[data-element-id="${elId}"]`);
                            if (node && typeof sec.content[key] === 'object') {
                                Object.assign(node.style, sec.content[key]);
                            }
                        } else if (key.startsWith('el_')) {
                            const elId = key.replace('el_', '');
                            const node = secEl.querySelector(`[data-element-id="${elId}"]`);
                            if (node && key !== 'el_href_'+elId && key !== 'el_style_'+elId) {
                                // Important: We ONLY overwrite innerHTML if there's no nested content we're worried about destroying.
                                // Actually yes, if it's a simple text element we just set it.
                                node.innerHTML = sec.content[key];
                            }
                        }
                    });
                }
            });
        });
    </script>
    @endif
</body>
</html>

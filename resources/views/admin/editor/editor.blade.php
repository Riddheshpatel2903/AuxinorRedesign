  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/editor.css') }}">
    <title>Editor — {{ ucfirst($pageSlug) }}</title>
  </head>
  <body>
  <script>
    const CSRF = '{{ csrf_token() }}'
    const PAGE_SLUG = '{{ $pageSlug }}'
    const ROUTES = {
      style:      '{{ route("admin.editor.style") }}',
      content:    '{{ route("admin.editor.content") }}',
      visibility: '{{ route("admin.editor.visibility") }}',
      reorder:    '{{ route("admin.editor.reorder") }}',
      publish:    '{{ route("admin.editor.publish", $pageSlug) }}',
      upload:     '{{ route("admin.editor.upload-image") }}',
    }
    const SETTINGS = @json($globalSettings);
    @php
        $secArr = [];
        foreach($sections as $s) {
            $secArr[] = [
                'id' => $s->id,
                'key' => $s->section_key,
                'label' => $s->section_label,
                'visible' => $s->is_visible
            ];
        }
    @endphp
    const SECTIONS = {!! json_encode($secArr) !!};
  </script>

  <div class="ed-shell">

    <!-- LEFT TOOLBAR -->
    <aside class="ed-bar">
      <div class="ed-logo">AC</div>
      <div class="ed-tools">
        <button class="ed-tool active" data-tool="select" title="Select">↖</button>
        <button class="ed-tool" data-tool="text"   title="Text">T</button>
        <button class="ed-tool" data-tool="image"  title="Image">🖼</button>
      </div>
      <div class="ed-sep"></div>
      <div class="ed-tools">
        <button class="ed-tool" id="undoBtn" title="Undo">↩</button>
        <button class="ed-tool" id="previewBtn" title="Preview">👁</button>
      </div>
      <a href="{{ route('admin.dashboard') }}" class="ed-back" title="Admin">←</a>
    </aside>

    <!-- CANVAS -->
    <main class="ed-canvas">
      <div class="ed-topbar">
        <span class="ed-page-name">Editing: <strong>{{ ucfirst(str_replace('-',' ',$pageSlug)) }}</strong></span>
        <div class="ed-pages">
          @foreach(['home','about','products','industries','infrastructure','insights','contact'] as $pg)
          <a href="{{ route('admin.editor.page',$pg) }}"
             class="ed-page-link {{ $pageSlug===$pg?'active':'' }}">{{ $pg }}</a>
          @endforeach
        </div>
        <div class="ed-vps">
          <button class="ed-vp active" data-w="100%">🖥 Desktop</button>
          <button class="ed-vp" data-w="768px">⬛ Tablet</button>
          <button class="ed-vp" data-w="390px">📱 Mobile</button>
        </div>
        <div class="ed-actions">
          <button class="ed-btn-ghost" id="undoTopBtn">↩ Undo</button>
          <button class="ed-btn-save"  id="saveBtn">Save</button>
          <button class="ed-btn-pub"   id="publishBtn">Publish →</button>
        </div>
      </div>

      <div class="ed-frame-wrap">
        <iframe id="pageFrame"
          src="{{ $pageSlug === 'home' ? route('home') : url('/'.$pageSlug) }}?editor_mode=1&_t={{ time() }}"
          class="ed-iframe">
        </iframe>
      </div>

      <div class="ed-status">
        <span id="sbSection">No section selected</span>
        <span id="sbEl"></span>
        <span id="sbSaved" class="sb-right">Ready</span>
      </div>
    </main>

    <!-- RIGHT PANEL -->
    <aside class="ed-panel">
      <div class="ed-ptabs">
        <button class="ed-ptab active" data-tab="style">Style</button>
        <button class="ed-ptab" data-tab="content">Content</button>
        <button class="ed-ptab" data-tab="layout">Layout</button>
        <button class="ed-ptab" data-tab="layers">Layers</button>
      </div>

      <div class="ed-pbody" id="tab-style">
        <div class="ed-empty" id="styleEmpty">↖ Click a section or element</div>
        <div id="styleForm" style="display:none">

          <div class="ep-group">
            <div class="ep-title">Typography</div>
            <div class="ep-row2">
              <div class="ep-field"><label>Size</label>
                <input class="ep-in sctrl" type="text" data-prop="fontSize" id="c-fontSize"></div>
              <div class="ep-field"><label>Weight</label>
                <select class="ep-in sctrl" data-prop="fontWeight" id="c-fontWeight">
                  <option value="300">Light</option><option value="400">Regular</option>
                  <option value="500">Medium</option><option value="600">Semi Bold</option>
                  <option value="700">Bold</option><option value="800">Extra Bold</option>
                </select></div>
            </div>
            <div class="ep-field"><label>Family</label>
              <select class="ep-in sctrl" data-prop="fontFamily" id="c-fontFamily">
                <option value="'Outfit',sans-serif">Outfit — Display</option>
                <option value="'Lora',serif">Lora — Serif</option>
                <option value="'JetBrains Mono',monospace">JetBrains Mono</option>
                <option value="'Inter',sans-serif">Inter — UI</option>
              </select></div>
            <div class="ep-row2">
              <div class="ep-field"><label>Line Height</label>
                <input class="ep-in sctrl" type="text" data-prop="lineHeight" id="c-lineHeight"></div>
              <div class="ep-field"><label>Letter Spacing</label>
                <input class="ep-in sctrl" type="text" data-prop="letterSpacing" id="c-letterSpacing"></div>
            </div>
          </div>

          <div class="ep-group">
            <div class="ep-title">Color</div>
            <div class="ep-field"><label>Text</label>
              <div class="ep-color-row">
                <input type="color" class="ep-color sctrl" data-prop="color" id="c-color">
                <input type="text" class="ep-in" id="c-color-hex" placeholder="#ffffff">
              </div></div>
            <div class="ep-field"><label>Background</label>
              <div class="ep-color-row">
                <input type="color" class="ep-color sctrl" data-prop="backgroundColor" id="c-bg">
                <input type="text" class="ep-in" id="c-bg-hex" placeholder="#0d1117">
              </div></div>
            <div class="ep-swatches">
              <div class="ep-sw" style="background:#0d1117" data-c="#0d1117"></div>
              <div class="ep-sw" style="background:#1c2333" data-c="#1c2333"></div>
              <div class="ep-sw" style="background:#0e7c6e" data-c="#0e7c6e"></div>
              <div class="ep-sw" style="background:#12a08e" data-c="#12a08e"></div>
              <div class="ep-sw" style="background:#ffffff" data-c="#ffffff"></div>
              <div class="ep-sw" style="background:#f8fafc" data-c="#f8fafc"></div>
              <div class="ep-sw" style="background:#64748b" data-c="#64748b"></div>
              <div class="ep-sw" style="background:#d4840a" data-c="#d4840a"></div>
            </div>
          </div>

          <div class="ep-group">
            <div class="ep-title">Spacing</div>
            <div class="ep-bm">
              <div class="ep-bm-outer">
                <input class="ep-in ep-bm-val" type="text" data-prop="marginTop" placeholder="0">
                <div class="ep-bm-mid">
                  <input class="ep-in ep-bm-val" type="text" data-prop="marginLeft" placeholder="0">
                  <div class="ep-bm-inner">
                    <input class="ep-in ep-bm-val" type="text" data-prop="paddingTop" placeholder="60px">
                    <div class="ep-bm-row">
                      <input class="ep-in ep-bm-val" type="text" data-prop="paddingLeft" placeholder="52px">
                      <span>Content</span>
                      <input class="ep-in ep-bm-val" type="text" data-prop="paddingRight" placeholder="52px">
                    </div>
                    <input class="ep-in ep-bm-val" type="text" data-prop="paddingBottom" placeholder="60px">
                  </div>
                  <input class="ep-in ep-bm-val" type="text" data-prop="marginRight" placeholder="0">
                </div>
                <input class="ep-in ep-bm-val" type="text" data-prop="marginBottom" placeholder="0">
              </div>
            </div>
          </div>

          <div class="ep-group">
            <div class="ep-title">Size</div>
            <div class="ep-row2">
              <div class="ep-field"><label>Width</label>
                <input class="ep-in sctrl" type="text" data-prop="width" placeholder="auto"></div>
              <div class="ep-field"><label>Min Height</label>
                <input class="ep-in sctrl" type="text" data-prop="minHeight" placeholder="auto"></div>
            </div>
          </div>

          <div class="ep-group">
            <div class="ep-title">Visibility</div>
            <div class="ep-toggle-row">
              <span>Hide Section</span>
              <div class="ep-toggle" id="vis-hide"></div>
            </div>
          </div>

          <button class="ep-apply" id="applyStylesBtn" style="margin-top:15px">Apply Style</button>
        </div>
      </div>

      <div class="ed-pbody" id="tab-content" style="display:none">
        <div class="ep-group">
          <div class="ep-title">Text</div>
          <textarea class="ep-in ep-ta" id="c-text" placeholder="Select a text element..."></textarea>
          <div class="ep-row2">
            <div class="ep-field"><label>Tag</label>
              <select class="ep-in" id="c-tag">
                <option>div</option><option>h1</option><option>h2</option><option>h3</option>
                <option>p</option><option>span</option><option>a</option><option>button</option>
              </select></div>
            <div class="ep-field" id="hrefField" style="display:none"><label>Link</label>
              <input class="ep-in" type="text" id="c-href" placeholder="/products"></div>
          </div>
          <button class="ep-apply" id="applyTextBtn">Apply Text</button>
        </div>
        <div class="ep-group">
          <div class="ep-title">Section Background</div>
          <div class="ep-field"><label>Image Upload</label>
            <input type="file" class="ep-in" id="c-bgUpload" accept="image/*">
            <input type="hidden" id="c-bgUrl" placeholder="https://...">
            <div id="uploadStatus" style="font-size:9px;color:var(--ed-teal2);margin-top:4px"></div>
          </div>
          <div class="ep-field"><label>Overlay (0–1)</label>
            <input class="ep-in" type="range" id="c-bgOverlay" min="0" max="1" step="0.05" value="0.5">
            <span id="bgOverlayVal">0.5</span></div>
          <button class="ep-apply" id="applyBgBtn">Apply Background</button>
        </div>
        <div class="ep-group" id="heroSlidesGroup" style="display:none">
          <div class="ep-title">Hero Backgrounds</div>
          @for($i=1; $i<=4; $i++)
          <div class="ep-field" style="margin-top:6px; border-top:1px dashed var(--ed-border); padding-top:6px;">
            <label style="color:var(--ed-teal);">Slide {{ $i }} Image Upload</label>
            <input type="file" id="heroUpload{{ $i }}" class="ep-in" accept="image/*">
            <input type="hidden" id="c-hero_bg_{{ $i }}">
            <div id="heroStatus{{ $i }}" style="font-size:9px;color:var(--ed-teal);margin-top:4px"></div>
          </div>
          <div class="ep-field">
            <label>Slide {{ $i }} Overlay</label>
            <input class="ep-in" type="range" id="c-hero_bg_overlay_{{ $i }}" min="0" max="1" step="0.05" value="0.5">
          </div>
          @endfor
          <button class="ep-apply" id="applyHeroSlidesBtn" style="margin-top:15px">Save Header Slides</button>
        </div>
      </div>

      <div class="ed-pbody" id="tab-layout" style="display:none">
        <div class="ep-group">
          <div class="ep-title">Display</div>
          <div class="ep-field"><label>Type</label>
            <select class="ep-in sctrl" data-prop="display" id="c-display">
              <option>block</option><option>flex</option><option>grid</option><option>none</option>
            </select></div>
          <div class="ep-field"><label>Grid Columns</label>
            <input class="ep-in sctrl" type="text" data-prop="gridTemplateColumns" placeholder="1fr 1fr"></div>
          <div class="ep-field"><label>Gap</label>
            <input class="ep-in sctrl" type="text" data-prop="gap" placeholder="0px"></div>
          <div class="ep-field"><label>Align Items</label>
            <select class="ep-in sctrl" data-prop="alignItems">
              <option>flex-start</option><option>center</option><option>flex-end</option><option>stretch</option>
            </select></div>
        </div>
        <div class="ep-group">
          <div class="ep-title">Animation</div>
          <div class="ep-field"><label>Type</label>
            <select class="ep-in" id="c-anim">
              <option value="">None</option>
              <option value="sr">Fade Up</option>
              <option value="sr-l">Slide Left</option>
              <option value="sr-r">Slide Right</option>
              <option value="sr-bounce">Spring Drop</option>
              <option value="sr-stagger">Stagger Children</option>
            </select></div>
          <div class="ep-row2">
            <div class="ep-field"><label>Duration</label>
              <input class="ep-in" type="text" id="c-animDur" value="0.8s"></div>
            <div class="ep-field"><label>Delay</label>
              <input class="ep-in" type="text" id="c-animDelay" value="0ms"></div>
          </div>
          <button class="ep-apply" id="applyAnimBtn">Apply Animation</button>
        </div>
      </div>

      <div class="ed-pbody" id="tab-layers" style="display:none">
        <div class="ep-group">
          <div class="ep-title">Page Sections</div>
          <div id="layerTree"></div>
        </div>
      </div>

    </aside>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
  <script src="{{ asset('js/editor.js') }}"></script>
  </body>
  </html>

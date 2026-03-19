const state = {
  sectionId: null, elementId: null, sectionKey: null,
  pending: { styles: {}, content: {} }, undoStack: [], dirty: false
}

function post(action, payload) {
  document.getElementById('pageFrame').contentWindow
    .postMessage({ src: 'auxinor-editor', action, payload }, '*')
}

function api(url, data) {
  return fetch(url, {
    method: 'POST',
    headers: { 'Content-Type':'application/json',
               'X-CSRF-TOKEN': CSRF, 'Accept':'application/json' },
    body: JSON.stringify(data)
  }).then(r => r.json())
}

window.addEventListener('message', e => {
  if (e.data?.src !== 'auxinor-site') return
  const { action, payload } = e.data
  if (action === 'section-click') {
    state.sectionId = payload.id
    state.sectionKey = payload.key
    state.elementId = null
    document.getElementById('sbSection').textContent = 'Section: ' + payload.label
    document.getElementById('styleEmpty').style.display = 'none'
    document.getElementById('styleForm').style.display = 'block'
    populateControls(payload.styles)
    updateLayers(payload.id)
    document.getElementById('heroSlidesGroup').style.display =
      payload.key === 'hero' ? 'block' : 'none'
  }
  if (action === 'element-click') {
    state.elementId = payload.id
    document.getElementById('sbEl').textContent = 'El: ' + payload.key
    document.getElementById('c-text').value = payload.content
    document.getElementById('c-tag').value = payload.tag
    document.getElementById('c-href').value = payload.href || ''
    
    const hrefField = document.getElementById('hrefField');
    if(hrefField) {
      hrefField.style.display = (payload.tag === 'a' || payload.href) ? 'flex' : 'none';
    }

    populateControls(payload.styles)
  }
  if (action === 'deselect') {
    state.sectionId = null; state.elementId = null
    document.getElementById('sbSection').textContent = 'No section selected'
    document.getElementById('sbEl').textContent = ''
  }
})

function applyProp(prop, value) {
  if (!state.sectionId) return;
  state.undoStack.push({ sectionId: state.sectionId,
                         elementId: state.elementId, prop, value });
  post('apply-style', { sectionId: state.sectionId,
                        elementId: state.elementId, prop, value });
  
  if (state.elementId) {
    if (!state.pending.content) state.pending.content = {};
    if (!state.pending.content[state.sectionId]) state.pending.content[state.sectionId] = {};
    if (!state.pending.content[state.sectionId][`el_style_${state.elementId}`]) {
      state.pending.content[state.sectionId][`el_style_${state.elementId}`] = {};
    }
    state.pending.content[state.sectionId][`el_style_${state.elementId}`][prop] = value;
  } else {
    if (!state.pending.styles) state.pending.styles = {};
    if (!state.pending.styles[state.sectionId]) state.pending.styles[state.sectionId] = {};
    state.pending.styles[state.sectionId][prop] = value;
  }
  markDirty();
}

document.querySelectorAll('.sctrl').forEach(el => {
  el.addEventListener(el.tagName === 'SELECT' ? 'change' : 'input',
    () => applyProp(el.dataset.prop, el.value))
})
document.querySelectorAll('.ep-bm-val').forEach(el => {
  el.addEventListener('change', () => applyProp(el.dataset.prop, el.value))
})
document.querySelectorAll('.ep-sw').forEach(sw => {
  sw.addEventListener('click', () => {
    applyProp('color', sw.dataset.c)
    document.getElementById('c-color').value = sw.dataset.c
    document.getElementById('c-color-hex').value = sw.dataset.c
  })
})

document.getElementById('c-color').addEventListener('input', function() {
  document.getElementById('c-color-hex').value = this.value
  applyProp('color', this.value)
})
document.getElementById('c-color-hex').addEventListener('change', function() {
  document.getElementById('c-color').value = this.value
  applyProp('color', this.value)
})
document.getElementById('c-bg').addEventListener('input', function() {
  document.getElementById('c-bg-hex').value = this.value
  applyProp('backgroundColor', this.value)
})

document.getElementById('c-text').addEventListener('input', () => {
  const content = document.getElementById('c-text').value
  const href    = document.getElementById('c-href').value
  if (!state.elementId) return
  post('apply-content', { elementId: state.elementId, content, href })
  api(ROUTES.content, { section_id: state.sectionId,
    content: { ['el_'+state.elementId]: content, ['el_href_'+state.elementId]: href } })
  markDirty()
})

document.getElementById('c-href').addEventListener('input', () => {
  const content = document.getElementById('c-text').value
  const href    = document.getElementById('c-href').value
  if (!state.elementId) return
  post('apply-content', { elementId: state.elementId, content, href })
  api(ROUTES.content, { section_id: state.sectionId,
    content: { ['el_'+state.elementId]: content, ['el_href_'+state.elementId]: href } })
  markDirty()
})

document.getElementById('applyTextBtn').addEventListener('click', function() {
  this.textContent = 'Text Applied ✓'
  setTimeout(() => this.textContent = 'Apply Text', 2000)
})

document.getElementById('applyStylesBtn')?.addEventListener('click', function() {
  this.textContent = 'Styles Applied ✓'
  setTimeout(() => this.textContent = 'Apply Style', 2000)
})

document.getElementById('c-bgUpload')?.addEventListener('change', async function() {
  if (!this.files[0]) return;
  const status = document.getElementById('uploadStatus');
  status.textContent = 'Uploading...';
  const formData = new FormData();
  formData.append('image', this.files[0]);
  try {
    const res = await fetch(ROUTES.upload, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      body: formData
    }).then(r => r.json());
    if (res.ok && res.url) {
      document.getElementById('c-bgUrl').value = res.url;
      status.textContent = 'Uploaded successfully';
      setTimeout(() => status.textContent = '', 2000);
    } else {
      status.textContent = 'Upload failed';
      status.style.color = 'red';
    }
  } catch (err) {
    status.textContent = 'Error uploading';
    status.style.color = 'red';
  }
});

document.getElementById('c-bgUrl').addEventListener('input', () => {
  const url = document.getElementById('c-bgUrl').value
  const op  = document.getElementById('c-bgOverlay').value
  post('apply-bg', { sectionId: state.sectionId, url, opacity: op })
  api(ROUTES.content, { section_id: state.sectionId,
    content: { bg_image_url: url, bg_overlay: op } })
  markDirty()
})

document.getElementById('applyBgBtn').addEventListener('click', function() {
  this.textContent = 'Background Applied ✓'
  setTimeout(() => this.textContent = 'Apply Background', 2000)
})

document.getElementById('c-bgOverlay').addEventListener('input', function() {
  document.getElementById('bgOverlayVal').textContent = this.value
  const url = document.getElementById('c-bgUrl').value
  post('apply-bg', { sectionId: state.sectionId, url, opacity: this.value })
  api(ROUTES.content, { section_id: state.sectionId,
    content: { bg_image_url: url, bg_overlay: this.value } })
  markDirty()
})

document.getElementById('applyAnimBtn').addEventListener('click', () => {
  const cls = document.getElementById('c-anim').value
  post('apply-anim', { sectionId: state.sectionId, animClass: cls })
  markDirty()
})

document.getElementById('vis-hide').addEventListener('click', function() {
  this.classList.toggle('on')
  const visible = !this.classList.contains('on')
  post('toggle-vis', { sectionId: state.sectionId, visible })
  api(ROUTES.visibility, { section_id: state.sectionId, is_visible: visible })
})

document.getElementById('saveBtn').addEventListener('click', async () => {
  document.getElementById('saveBtn').textContent = 'Saving...';
  
  const saves = [];
  // Save section styles
  if (state.pending.styles) {
    for (const [secId, styles] of Object.entries(state.pending.styles)) {
      if (Object.keys(styles).length) {
        saves.push(api(ROUTES.style, { section_id: secId, styles }));
      }
    }
  }
  
  // Save element styles via content merge
  if (state.pending.content) {
    for (const [secId, content] of Object.entries(state.pending.content)) {
      if (Object.keys(content).length) {
        saves.push(api(ROUTES.content, { section_id: secId, content }));
      }
    }
  }

  await Promise.all(saves);
  state.pending = { styles: {}, content: {} };
  markClean();
});

document.getElementById('publishBtn').addEventListener('click', async () => {
  if (!confirm('Publish all changes live?')) return
  document.getElementById('publishBtn').textContent = 'Publishing...'
  await api(ROUTES.publish, {})
  document.getElementById('publishBtn').textContent = '✓ Published'
  setTimeout(() => document.getElementById('publishBtn').textContent = 'Publish →', 2500)
})

document.getElementById('undoBtn').addEventListener('click', () => {
  if (!state.undoStack.length) return
  const h = state.undoStack.pop()
  post('apply-style', { sectionId: h.sectionId, elementId: h.elementId,
                        prop: h.prop, value: '' })
})
document.getElementById('undoTopBtn').addEventListener('click', () => {
  document.getElementById('undoBtn').click()
})

document.querySelectorAll('.ed-vp').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.ed-vp').forEach(b => b.classList.remove('active'))
    btn.classList.add('active')
    document.getElementById('pageFrame').style.width = btn.dataset.w
  })
})

document.querySelectorAll('.ed-ptab').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.ed-ptab').forEach(b => b.classList.remove('active'))
    document.querySelectorAll('.ed-pbody').forEach(p => p.style.display = 'none')
    btn.classList.add('active')
    document.getElementById('tab-'+btn.dataset.tab).style.display = 'block'
  })
})

function buildLayers() {
  const tree = document.getElementById('layerTree')
  tree.innerHTML = SECTIONS.map(s => `
    <div class="ep-layer-item" data-id="${s.id}" data-key="${s.key}"
         onclick="clickLayer(${s.id},'${s.key}','${s.label}')">
      <span class="ep-layer-drag">⠿</span>
      <span>▦</span>
      <span class="ep-layer-label">${s.label}</span>
      <span class="ep-layer-vis ${s.visible?'on':''}"
            onclick="event.stopPropagation();toggleLayerVis(${s.id},this)">👁</span>
    </div>
  `).join('')
  new Sortable(tree, {
    animation: 150, handle: '.ep-layer-drag',
    onEnd: () => {
      const order = [...tree.querySelectorAll('.ep-layer-item')]
        .map(el => el.dataset.id)
      api(ROUTES.reorder, { order })
    }
  })
}

function clickLayer(id, key, label) {
  post('highlight-section', { sectionId: id })
  state.sectionId = id; state.sectionKey = key
  document.getElementById('sbSection').textContent = 'Section: ' + label
  updateLayers(id)
}

function toggleLayerVis(id, btn) {
  btn.classList.toggle('on')
  const visible = btn.classList.contains('on')
  post('toggle-vis', { sectionId: id, visible })
  api(ROUTES.visibility, { section_id: id, is_visible: visible })
}

function updateLayers(activeId) {
  document.querySelectorAll('.ep-layer-item').forEach(el =>
    el.classList.toggle('active', el.dataset.id == activeId))
}

function populateControls(styles) {
  if (!styles) return
  Object.keys(styles).forEach(p => {
    const ctrl = document.getElementById('c-'+p) ||
                 document.querySelector('[data-prop="'+p+'"]')
    if (ctrl) ctrl.value = styles[p] || ''
  })
}

function markDirty() {
  state.dirty = true
  document.getElementById('sbSaved').textContent = '● Unsaved'
  document.getElementById('sbSaved').style.color = '#f59e0b'
}

function markClean() {
  state.dirty = false
  document.getElementById('saveBtn').textContent = 'Save'
  document.getElementById('sbSaved').textContent = '✓ Saved'
  document.getElementById('sbSaved').style.color = '#12a08e'
}

window.addEventListener('DOMContentLoaded', () => {
  for(let i=1; i<=4; i++) {
     const url = SETTINGS['hero_bg_'+i];
     const overlay = SETTINGS['hero_bg_overlay_'+i];
     if (document.getElementById('c-hero_bg_'+i) && url) {
         document.getElementById('c-hero_bg_'+i).value = url;
         document.getElementById('heroStatus'+i).textContent = 'Current: ' + url.split('/').pop().substring(0,15) + '...';
     }
     if (document.getElementById('c-hero_bg_overlay_'+i) && overlay) {
         document.getElementById('c-hero_bg_overlay_'+i).value = overlay;
     }
  }
});

for (let i = 1; i <= 4; i++) {
  document.getElementById(`heroUpload${i}`)?.addEventListener('change', async function() {
    if (!this.files[0]) return;
    const status = document.getElementById(`heroStatus${i}`);
    status.textContent = 'Uploading...';
    const formData = new FormData();
    formData.append('image', this.files[0]);
    try {
      const res = await fetch(ROUTES.upload, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }, body: formData }).then(r => r.json());
      if (res.ok && res.url) {
        document.getElementById(`c-hero_bg_${i}`).value = res.url;
        status.textContent = 'Uploaded successfully';
        document.getElementById('applyHeroSlidesBtn').click();
      } else {
        status.textContent = 'Upload failed'; status.style.color = 'red';
      }
    } catch (err) {
      status.textContent = 'Error uploading'; status.style.color = 'red';
    }
  });
}

document.getElementById('applyHeroSlidesBtn')?.addEventListener('click', () => {
  const contentUpdates = {};
  for(let i=1; i<=4; i++) {
    const url = document.getElementById(`c-hero_bg_${i}`).value;
    const overlay = document.getElementById(`c-hero_bg_overlay_${i}`).value;
    if (url) contentUpdates[`el_setting:hero_bg_${i}`] = url;
    if (overlay) contentUpdates[`el_setting:hero_bg_overlay_${i}`] = overlay;
  }
  api(ROUTES.content, { section_id: state.sectionId, content: contentUpdates });
  document.getElementById('applyHeroSlidesBtn').textContent = "Saved";
  setTimeout(() => document.getElementById('applyHeroSlidesBtn').textContent = "Save Header Slides", 2000);
  
  // Reload iframe gracefully to immediately pop the changes globally on Hero section Alpine state
  document.getElementById('pageFrame').contentWindow.location.reload();
  
  markDirty();
});

buildLayers()

let data = typeof PAGE_CONTENT !== 'undefined' ? PAGE_CONTENT : [];
const schemas = typeof SCHEMAS !== 'undefined' ? SCHEMAS : {};
const state = {
    activeSectionIdx: null,
    elementKey: null,
    dirty: false
};

// ============================================================
// COMMUNICATION UTILS
// ============================================================

function postToFrame(action, payload) {
    const frame = document.getElementById('pageFrame');
    if (frame && frame.contentWindow) {
        frame.contentWindow.postMessage({ src: 'auxinor-editor', action, payload }, '*');
    }
}

async function api(url, payload, method = 'POST') {
    try {
        const isFormData = payload instanceof FormData;
        const options = {
            method: method,
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        };
        if (method !== 'GET' && !isFormData) {
            options.headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(payload);
        } else if (isFormData) {
            options.body = payload;
        }

        const r = await fetch(url, options);
        const res = await r.json();
        if (!r.ok) throw new Error(res.message || 'Server Error');
        return res;
    } catch (err) {
        setStatus('⚠ Error: ' + err.message, '#ef4444');
        throw err;
    }
}

function setStatus(msg, color = '#12a08e') {
    const el = document.getElementById('sbSaved');
    if (el) {
        el.textContent = msg;
        el.style.color = color;
    }
}

function markDirty() {
    state.dirty = true;
    setStatus('● Unsaved', '#f59e0b');
}

function markClean() {
    state.dirty = false;
    setStatus('✓ Saved', '#12a08e');
}

// ============================================================
// EDITOR CORE LOGIC
// ============================================================

window.addEventListener('message', async e => {
    if (e.data?.src !== 'auxinor-site') return;
    const { action, payload } = e.data;

    if (action === 'section-click' || action === 'element-click') {
        const idx = findSectionIndex(payload.id);
        if (idx === -1) return;

        state.activeSectionIdx = idx;
        const section = data[idx];
        const schema = schemas[section.type] || {};

        document.getElementById('sbSection').textContent = 'Section: ' + (schema.label || section.type);
        
        if (action === 'element-click') {
            state.elementKey = payload.key;
            document.getElementById('sbEl').textContent = 'El: ' + payload.key;
        } else {
            state.elementKey = null;
            document.getElementById('sbEl').textContent = '';
        }

        renderFields(schema, section.props);
        filterStyleControls(schema.styles || []);
        updateLayersUI();
        switchTab('content');

        if (action === 'element-click') {
            highlightField(payload.key);
        }
    }

    if (action === 'deselect') {
        state.activeSectionIdx = null;
        document.getElementById('sbSection').textContent = 'No section selected';
        document.getElementById('sbEl').textContent = '';
        updateLayersUI();
    }
});

function findSectionIndex(id) {
    // In the new system, we might use indices or data-attributes in the preview
    // If the preview is still using component data-section-id, we need a mapping.
    // For now, let's assume the preview sends the index as ID if we updated the preview components.
    // Actually, I'll update the loop in page.blade.php to provide index.
    return parseInt(id); 
}

function renderFields(schema, props) {
    const container = document.getElementById('fieldsContainer');
    const empty = document.getElementById('contentEmpty');
    if (!container) return;

    container.innerHTML = '';
    if (empty) empty.style.display = 'none';

    const fields = schema.fields || {};
    // New simplified schema format is key => type
    Object.entries(fields).forEach(([key, type]) => {
        const value = props[key] || '';
        const fieldHtml = createFieldMarkup(key, type, value);
        container.insertAdjacentHTML('beforeend', fieldHtml);
    });

    // Bind listeners
    container.querySelectorAll('.dynamic-input').forEach(el => {
        el.addEventListener('input', debounce(e => {
            updateValue(e.target.dataset.key, e.target.type === 'checkbox' ? e.target.checked : e.target.value);
        }, 500));
    });

    container.querySelectorAll('.image-upload').forEach(el => {
        el.addEventListener('change', async e => {
            if (!e.target.files.length) return;
            const res = await uploadImage(e.target.files[0]);
            if (res && res.url) {
                const key = e.target.dataset.key;
                const textInput = e.target.closest('.ep-group').querySelector('input[type="text"]');
                if (textInput) textInput.value = res.url;
                updateValue(key, res.url);
                const prev = e.target.closest('.ep-group').querySelector('.prev-img');
                if (prev) {
                    prev.src = res.url;
                    prev.parentElement.style.display = 'flex';
                }
            }
        });
    });
}

function createFieldMarkup(key, type, value) {
    let inputHtml = '';
    let label = key.charAt(0).toUpperCase() + key.slice(1).replace(/_/g, ' ');

    switch(type) {
        case 'html':
        case 'textarea':
            inputHtml = `<textarea class="ep-in ep-ta dynamic-input" data-key="${key}">${value}</textarea>`;
            break;
        case 'select':
            // Logic for options would go here
            inputHtml = `<input type="text" class="ep-in dynamic-input" data-key="${key}" value="${value}">`;
            break;
        case 'image':
            inputHtml = `
                <div class="ep-img-prev" style="${value ? 'display:flex' : 'display:none'}">
                    <img src="${value}" class="prev-img">
                </div>
                <div class="ep-row2">
                    <input type="file" class="ep-in image-upload" data-key="${key}" accept="image/*" style="width:50%">
                    <input type="text" class="ep-in dynamic-input" data-key="${key}" value="${value}" style="width:50%">
                </div>`;
            break;
        default:
            inputHtml = `<input type="text" class="ep-in dynamic-input" data-key="${key}" value="${value}">`;
    }

    return `<div class="ep-group" data-field-key="${key}"><div class="ep-title">${label}</div>${inputHtml}</div>`;
}

function updateValue(key, val) {
    if (state.activeSectionIdx === null) return;
    
    const props = data[state.activeSectionIdx].props;
    if (key.includes('.')) {
        const parts = key.split('.');
        let current = props;
        for (let i = 0; i < parts.length - 1; i++) {
            if (!current[parts[i]]) current[parts[i]] = {};
            current = current[parts[i]];
        }
        current[parts[parts.length - 1]] = val;
    } else {
        props[key] = val;
    }

    markDirty();
    postToFrame('apply-content', { index: state.activeSectionIdx, key, val });
}

function updateStyle(prop, val) {
    if (state.activeSectionIdx === null) return;
    if (!data[state.activeSectionIdx].props.styles) data[state.activeSectionIdx].props.styles = {};
    data[state.activeSectionIdx].props.styles[prop] = val;
    markDirty();
    postToFrame('apply-style', { index: state.activeSectionIdx, prop, val });
}

async function uploadImage(file) {
    const fd = new FormData();
    fd.append('image', file);
    return api(ROUTES.upload, fd);
}

// ============================================================
// LAYERS & UI
// ============================================================

function updateLayersUI() {
    const tree = document.getElementById('layerTree');
    if (!tree) return;
    
    tree.innerHTML = data.map((s, i) => `
        <div class="ep-layer-item ${state.activeSectionIdx === i ? 'active' : ''}" data-index="${i}">
            <span class="ep-layer-drag">⠿</span>
            <span class="ep-layer-label" onclick="selectLayer(${i})">${schemas[s.type]?.label || s.type}</span>
            <span class="ep-layer-del" onclick="deleteSection(${i})">×</span>
        </div>
    `).join('');

    new Sortable(tree, {
        animation: 150, handle: '.ep-layer-drag',
        onEnd: (e) => {
            const item = data.splice(e.oldIndex, 1)[0];
            data.splice(e.newIndex, 0, item);
            state.activeSectionIdx = e.newIndex;
            markDirty();
            updateLayersUI();
            postToFrame('reload-preview');
        }
    });
}

function selectLayer(idx) {
    state.activeSectionIdx = idx;
    const s = data[idx];
    const schema = schemas[s.type] || {};
    renderFields(schema, s.props);
    updateLayersUI();
    postToFrame('highlight-section', { index: idx });
}

function deleteSection(idx) {
    if(confirm('Delete this section?')) {
        data.splice(idx, 1);
        state.activeSectionIdx = null;
        markDirty();
        updateLayersUI();
        postToFrame('reload-preview');
    }
}

function highlightField(key) {
    setTimeout(() => {
        const el = document.querySelector(`[data-field-key="${key}"]`);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            el.classList.add('highlight-flash');
            setTimeout(() => el.classList.remove('highlight-flash'), 1000);
        }
    }, 100);
}

// ============================================================
// ACTIONS
// ============================================================

document.getElementById('saveBtn')?.addEventListener('click', async () => {
    const btn = document.getElementById('saveBtn');
    btn.textContent = 'Saving...';
    btn.disabled = true;
    try {
        await api(ROUTES.save, { content: data });
        markClean();
    } catch (e) {
        btn.textContent = 'Error';
        setTimeout(() => { btn.textContent = 'Save'; btn.disabled = false; }, 2000);
    } finally {
        btn.disabled = false;
    }
});

document.getElementById('publishBtn')?.addEventListener('click', async () => {
    const btn = document.getElementById('publishBtn');
    btn.textContent = 'Publishing...';
    await api(ROUTES.publish, {});
    btn.textContent = '✓ Published';
    setTimeout(() => btn.textContent = 'Publish →', 2500);
});

// Tab switching
document.querySelectorAll('.ed-ptab').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.ed-ptab').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.ed-pbody').forEach(p => p.style.display = 'none');
        btn.classList.add('active');
        document.getElementById(`tab-${btn.dataset.tab}`).style.display = 'block';
    });
});

function switchTab(tab) {
    const btn = document.querySelector(`.ed-ptab[data-tab="${tab}"]`);
    if (btn) btn.click();
}

function filterStyleControls(allowed) {
    document.querySelectorAll('[data-style-group]').forEach(g => {
        const name = g.dataset.styleGroup;
        g.style.display = (!allowed || allowed.length === 0 || allowed.includes(name)) ? 'block' : 'none';
    });
    const form = document.getElementById('styleForm');
    if (form) form.style.display = 'block';
    const empty = document.getElementById('styleEmpty');
    if (empty) empty.style.display = 'none';
}

// Bind style controls
document.querySelectorAll('.sctrl').forEach(el => {
    el.addEventListener('input', () => {
        updateStyle(el.dataset.prop, el.value);
    });
});

function debounce(func, wait) {
    let timeout;
    return function() {
        const context = this, args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), wait);
    };
}

// Initialize
updateLayersUI();

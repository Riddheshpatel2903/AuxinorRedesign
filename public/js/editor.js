/**
 * Auxinor Visual Editor (Refactored)
 * State-driven, modular logic.
 */

// Initialize Store & Bridge
const store = new EditorStore({
    sections: SECTIONS,
    settings: SETTINGS
});

const bridge = new EditorBridge('pageFrame');

/**
 * UI Interactions: Tab Switching
 */
document.querySelectorAll('.ed-ptab').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.ed-ptab').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.ed-pbody').forEach(b => b.style.display = 'none');
        btn.classList.add('active');
        const targetTab = document.getElementById('tab-' + btn.dataset.tab);
        if (targetTab) targetTab.style.display = 'block';
    });
});

/**
 * UI Sync: Update Sidebar when State changes
 */
store.subscribe((prop, value, state) => {
    // 1. Sync Iframe
    if (prop === 'batch' || prop === 'dirty') {
        bridge.syncState(state);
    }

    // 2. Update Status Bar
    if (state.active.sectionId) {
        const section = store.getSection(state.active.sectionId);
        document.getElementById('sbSection').textContent = 'Section: ' + (section?.label || state.active.sectionId);
    } else {
        document.getElementById('sbSection').textContent = 'No section selected';
    }

    if (state.active.elementId) {
        document.getElementById('sbEl').textContent = 'El: ' + state.active.elementId;
    } else {
        document.getElementById('sbEl').textContent = '';
    }

    // 3. Update Save Button Style
    const saveBtn = document.getElementById('sbSaved');
    if (state.dirty) {
        saveBtn.textContent = '● Unsaved';
        saveBtn.style.color = '#f59e0b';
    } else {
        saveBtn.textContent = '✓ Saved';
        saveBtn.style.color = '#12a08e';
    }
});

/**
 * Bridge Receiver: Handle messages from Iframe
 */
window.addEventListener('message', e => {
    if (e.data?.src !== 'auxinor-site') return;
    const { action, payload } = e.data;
    if (action === 'section-click') {
        store.state.active = { sectionId: payload.id, elementId: null, key: payload.key, tag: 'section' };
        document.getElementById('styleEmpty').style.display = 'none';
        document.getElementById('styleForm').style.display = 'block';
        
        const isHero = store.state.active.key && store.state.active.key.includes('hero');
        const isSlideshow = store.state.active.key === 'home_hero';
        
        document.getElementById('heroSlidesGroup').style.display = (isHero && isSlideshow) ? 'block' : 'none';
        document.getElementById('staticHeroGroup').style.display = (isHero && !isSlideshow) ? 'block' : 'none';
        
        if (isHero && isSlideshow) populateHeroSlides();
        if (isHero && !isSlideshow) populateStaticHero(payload.styles);
        
        // Populate controls from payload styles
        populateControls(payload.styles);
        
        // Auto-switch to appropriate Tab
        if (isHero) {
            const contentTab = document.querySelector('[data-tab="content"]');
            if (contentTab) contentTab.click();
        } else {
            const styleTab = document.querySelector('[data-tab="style"]');
            if (styleTab) styleTab.click();
        }
            
        updateLayersUI(payload.id);
    }
    if (action === 'element-click') {
        const sectionDef = store.getSection(payload.sectionId);
        const sectionKey = sectionDef ? sectionDef.key : null;
        
        store.state.active = { 
            sectionId: payload.sectionId, 
            elementId: payload.id,
            key: sectionKey,
            tag: payload.tag
        };

        // If clicking an element INSIDE a hero section, still show hero controls
        const isHero = sectionKey && sectionKey.includes('hero');
        const isSlideshow = sectionKey === 'home_hero';
        document.getElementById('heroSlidesGroup').style.display = (isHero && isSlideshow) ? 'block' : 'none';
        document.getElementById('staticHeroGroup').style.display = (isHero && !isSlideshow) ? 'block' : 'none';
        
        if (isHero && isSlideshow) populateHeroSlides();
        if (isHero && !isSlideshow) populateStaticHero(payload.styles);
        
        // Sync Sidebar Fields
        document.getElementById('c-text').value = payload.content || '';
        document.getElementById('c-tag').value = payload.tag || 'div';
        document.getElementById('c-href').value = payload.href || '';
        
        const hrefField = document.getElementById('hrefField');
        if (hrefField) hrefField.style.display = (payload.tag === 'a' || payload.href) ? 'flex' : 'none';
        
        const imgGroup = document.getElementById('imageGroup');
        const metaImgGroup = document.getElementById('metaImageGroup');
        const metaImgLabel = document.getElementById('metaImageLabel');
        
        if (imgGroup) imgGroup.style.display = (payload.tag === 'img' && !payload.id.includes('setting:')) ? 'block' : 'none';
        
        if (metaImgGroup) {
            const isBranding = payload.id.includes('logo_url');
            const isMeta = isBranding || (payload.id.includes('setting:') && (payload.tag === 'img' || payload.id.includes('bg')));
            metaImgGroup.style.display = isMeta ? 'block' : 'none';
            if (isMeta && metaImgLabel) {
                if (isBranding) metaImgLabel.textContent = 'Site Logo (Global)';
                else metaImgLabel.textContent = payload.id.includes('about') ? 'Left Side Image' : 
                                               (payload.id.includes('infra') ? 'Right Side Image' : 'Item Image');
            }
        }

        // --- MODEL AWARENESS HINT ---
        if (payload.modelType) {
            alert(`Note: This ${payload.modelType} is part of your dynamic database. To change it permanently, please use the "Manage ${payload.modelType}s" panel in your Admin dashboard.`);
        }

        // Auto-switch to Content Tab
        const contentTab = document.querySelector('[data-tab="content"]');
        if (contentTab) contentTab.click();
    }

    if (action === 'hero-slide-switch') {
        const i = payload.index + 1;
        const group = document.getElementById(`heroUpload${i}`)?.closest('div[style*="background"]');
        if (group) {
            group.style.borderColor = 'var(--ed-teal)';
            group.style.boxShadow = '0 0 10px rgba(18,160,142,0.2)';
            setTimeout(() => {
                group.style.borderColor = 'var(--ed-border)';
                group.style.boxShadow = 'none';
            }, 1500);
        }
    }

    if (action === 'deselect') {
        store.state.active = { sectionId: null, elementId: null };
        document.getElementById('styleForm').style.display = 'none';
        document.getElementById('styleEmpty').style.display = 'block';
    }
});

/**
 * Event Listeners: Update Store from UI
 */
function applyProp(prop, value) {
    const { sectionId, elementId } = store.state.active;
    if (!sectionId) return;

    if (elementId) {
        // Element Style Update
        const section = store.getSection(sectionId);
        if (!section.styles) section.styles = {};
        if (!section.styles[`el_style_${elementId}`]) section.styles[`el_style_${elementId}`] = {};
        section.styles[`el_style_${elementId}`][prop] = value;
        bridge.applyStyle(sectionId, elementId, prop, value);
    } else {
        // Section Style Update
        store.updateSectionStyle(sectionId, prop, value);
        bridge.applyStyle(sectionId, null, prop, value);
    }
}

// Bind standard controls
document.querySelectorAll('.sctrl, .ep-bm-val').forEach(el => {
    const event = el.tagName === 'SELECT' ? 'change' : 'input';
    el.addEventListener(event, () => applyProp(el.dataset.prop || el.id.replace('c-', ''), el.value));
});

// Bind Color Swatches
document.querySelectorAll('.ep-sw').forEach(sw => {
    sw.addEventListener('click', () => {
        applyProp('color', sw.dataset.c);
        if (document.getElementById('c-color')) {
            document.getElementById('c-color').value = sw.dataset.c;
            document.getElementById('c-color-hex').value = sw.dataset.c;
        }
    });
});

// Text & Link Updates
const updateContent = () => {
    const { sectionId, elementId } = store.state.active;
    if (!elementId) return;
    
    const content = document.getElementById('c-text').value;
    const href = document.getElementById('c-href').value;
    
    store.updateElementContent(sectionId, elementId, content, href);
    bridge.applyContent(elementId, content, href);
};

document.getElementById('c-text').addEventListener('input', updateContent);
document.getElementById('c-href').addEventListener('input', updateContent);
document.getElementById('applyTextBtn')?.addEventListener('click', updateContent);

// Background Updates
document.getElementById('applyBgBtn')?.addEventListener('click', () => {
    const { sectionId, elementId, tag } = store.state.active;
    if (!sectionId) return;
    const opacity = document.getElementById('c-bgOverlay').value;
    const url = document.getElementById('c-bgUrl').value;
    
    const escapedUrl = url ? url.replace(/"/g, '%22') : null;
    const sec = store.getSection(sectionId);
    if (!sec) return;
    if (!sec.styles || Array.isArray(sec.styles)) sec.styles = {};

    if (elementId && tag !== 'section') {
        const styleKey = `el_style_${elementId}`;
        if (!sec.styles[styleKey] || Array.isArray(sec.styles[styleKey])) sec.styles[styleKey] = {};
        if (escapedUrl) sec.styles[styleKey].backgroundImage = `url("${escapedUrl}")`;
        sec.styles[styleKey].overlayOpacity = opacity;
        store.state.dirty = true;
        bridge.applyBackground(sectionId, url, opacity, elementId);
    } else {
        if (escapedUrl) sec.styles.backgroundImage = `url("${escapedUrl}")`;
        sec.styles.overlayOpacity = opacity;
        bridge.applyBackground(sectionId, url, opacity, null);
        store.markDirty(sectionId);
    }
});

document.getElementById('removeBgBtn')?.addEventListener('click', () => {
    const { sectionId, elementId, tag } = store.state.active;
    if (!sectionId) return;
    
    document.getElementById('c-bgUrl').value = '';
    const opacity = document.getElementById('c-bgOverlay').value;
    
    const sec = store.getSection(sectionId);
    if (sec && sec.styles) {
        if (elementId && tag !== 'section') {
            const styleKey = `el_style_${elementId}`;
            if (sec.styles[styleKey]) delete sec.styles[styleKey].backgroundImage;
            store.state.dirty = true;
            bridge.applyBackground(sectionId, null, opacity, elementId);
        } else {
            delete sec.styles.backgroundImage;
            bridge.applyBackground(sectionId, null, opacity, null);
            store.markDirty(sectionId);
        }
    }
});

// Image Uploads (Background & Element)
async function uploadToService(file) {
    const formData = new FormData();
    formData.append('image', file);
    try {
        const res = await fetch(ROUTES.upload, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: formData
        }).then(r => r.json());
        return res;
    } catch (e) {
        return { ok: false, message: 'Network error during upload' };
    }
}

document.getElementById('c-imageUpload')?.addEventListener('change', async function() {
    if (!this.files[0] || !store.state.active.elementId) return;
    const status = document.getElementById('imageUploadStatus');
    status.textContent = 'Processing...';
    
    const res = await uploadToService(this.files[0]);
    if (res.ok && res.url) {
        status.textContent = 'Uploaded ✓';
        this.dataset.lastUrl = res.url;
        setTimeout(() => status.textContent = '', 2000);
    } else {
        status.textContent = 'Failed';
    }
});

document.getElementById('applyImageBtn')?.addEventListener('click', () => {
    const url = document.getElementById('c-imageUpload').dataset.lastUrl;
    const { sectionId, elementId } = store.state.active;
    if (url && sectionId && elementId) {
        bridge.applyContent(elementId, null, null, url);
        const isSetting = elementId.includes('setting:');
        store.updateElementContent(sectionId, elementId, isSetting ? url : null, null, url);
        if (isSetting) {
            const settingKey = elementId.replace('el_setting:', '');
            store.state.settings[settingKey] = url;
        }
        store.state.dirty = true;
        
        const btn = document.getElementById('applyImageBtn');
        btn.textContent = 'Applied ✓';
        setTimeout(() => btn.textContent = 'Apply Image', 2000);
    }
});

document.getElementById('removeImageBtn')?.addEventListener('click', () => {
    const { sectionId, elementId } = store.state.active;
    if (!sectionId || !elementId) return;
    
    bridge.applyContent(elementId, null, null, '');
    store.updateElementContent(sectionId, elementId, null, null, '');
    store.state.dirty = true;
});

document.getElementById('c-bgUpload')?.addEventListener('change', async function() {
    if (!this.files[0] || !store.state.active.sectionId) return;
    const status = document.getElementById('uploadStatus');
    status.textContent = 'Processing...';
    
    const res = await uploadToService(this.files[0]);
    if (res.ok && res.url) {
        status.textContent = 'Uploaded ✓';
        document.getElementById('c-bgUrl').value = res.url;
        document.getElementById('applyBgBtn')?.click(); // Apply immediately
        setTimeout(() => status.textContent = '', 2000);
    } else {
        status.textContent = 'Failed';
    }
});

for (let i = 1; i <= 4; i++) {
    document.getElementById(`heroUpload${i}`)?.addEventListener('change', async function() {
        if (!this.files[0]) return;
        const status = document.getElementById(`heroStatus${i}`);
        status.textContent = 'Processing...';
        
        const res = await uploadToService(this.files[0]);
        if (res.ok && res.url) {
            status.textContent = 'Uploaded ✓';
            document.getElementById(`c-hero_bg_${i}`).value = res.url;
            
            // Real-time update in Iframe
            bridge.updateHeroSlide(i-1, res.url);
            
            // Auto-trigger save to persist (you can also just leave it for the Save button)
            document.getElementById('applyHeroSlidesBtn')?.click(); 
            setTimeout(() => status.textContent = '', 2000);
        } else {
            status.textContent = 'Failed';
        }
    });

    document.getElementById(`c-hero_bg_overlay_${i}`)?.addEventListener('input', function() {
        const val = this.value;
        bridge.updateHeroSlide(i-1, undefined, val);
    });

    document.getElementById(`removeHeroBg${i}`)?.addEventListener('click', () => {
        document.getElementById(`c-hero_bg_${i}`).value = '';
        document.getElementById(`heroUpload${i}`).value = '';
        bridge.updateHeroSlide(i-1, ''); // Clear in iframe
        document.getElementById('applyHeroSlidesBtn')?.click(); 
    });
}

// Meta Settings Image Uploads (Side images, etc)
document.getElementById('c-metaImageUpload')?.addEventListener('change', async function() {
    if (!this.files[0] || !store.state.active.elementId) return;
    const status = document.getElementById('metaImageUploadStatus');
    status.textContent = 'Processing...';
    
    const res = await uploadToService(this.files[0]);
    if (res.ok && res.url) {
        status.textContent = 'Uploaded ✓';
        this.dataset.lastUrl = res.url;
        setTimeout(() => status.textContent = '', 2000);
    } else {
        status.textContent = 'Failed';
    }
});

document.getElementById('applyMetaImageBtn')?.addEventListener('click', async () => {
    const url = document.getElementById('c-metaImageUpload').dataset.lastUrl;
    const { sectionId, elementId } = store.state.active;
    if (!url || !elementId) return;

    const isSetting = elementId.includes('setting:');

    if (isSetting) {
        // Save DIRECTLY to the Settings table
        const settingKey = elementId.includes(':') ? elementId.split(':').slice(1).join(':') : elementId;
        const saved = await saveSettingsDirect({ [settingKey]: url });
        if (saved) {
            // CRITICAL: Also update the store's section content. 
            // If we don't, clicking the main 'Save' button later will send the OLD content 
            // and overwrite our direct DB update!
            store.updateElementContent(sectionId, elementId, url, null, url);
            
            // Update the live preview in the iframe
            bridge.applyContent(elementId, null, null, url);
            
            // Update local settings state
            store.state.settings[settingKey] = url;
            
            const btn = document.getElementById('applyMetaImageBtn');
            btn.textContent = 'Saved to DB ✓';
            setTimeout(() => btn.textContent = 'Apply Decoration', 2000);
        }
    } else {
        // Non-setting element
        bridge.applyContent(elementId, null, null, url);
        store.updateElementContent(sectionId, elementId, null, null, url);
        store.state.dirty = true;
        const btn = document.getElementById('applyMetaImageBtn');
        btn.textContent = 'Applied ✓';
        setTimeout(() => btn.textContent = 'Apply Decoration', 2000);
    }
});

/**
 * Helper: Directly save key/value settings to the DB without section lookup.
 * Used for global settings like service card backgrounds.
 */
async function saveSettingsDirect(settings) {
    try {
        const res = await fetch(ROUTES.saveSettings, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ settings })
        });
        const result = await res.json().catch(() => ({ ok: false }));
        if (!res.ok || !result.ok) {
            console.error('saveSettingsDirect failed', result);
            return false;
        }
        return true;
    } catch (e) {
        console.error('saveSettingsDirect error', e);
        return false;
    }
}


/**
 * Centralized Save Logic
 */
async function saveAll(extraSettings = null) {
    const btn = document.getElementById('saveBtn');
    const originalText = btn.textContent;
    btn.textContent = 'Saving...';
    btn.disabled = true;

    try {
        // If extraSettings are provided (from sidebar), push them into the hero section in the store
        if (extraSettings) {
            const heroSection = store.state.sections.find(s => s.key === 'home_hero' || s.key === 'about_hero');
            if (heroSection) {
                if (!heroSection.content) heroSection.content = {};
                for (const [k, v] of Object.entries(extraSettings)) {
                    heroSection.content[`el_setting:${k}`] = v;
                }
                store.markDirty(heroSection.id);
            }
        }

        // Optimization: Delta Save (only send dirty sections)
        const payload = store.state.sections.filter(s => store.state.dirtySections[s.id]);
        
        if (payload.length === 0 && !store.state.dirty) {
            btn.textContent = 'No changes';
            setTimeout(() => btn.textContent = originalText, 2000);
            btn.disabled = false;
            return { ok: true };
        }

        const res = await fetch(ROUTES.saveAll, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ sections: payload })
        });
        
        const result = await res.json().catch(() => ({ message: 'Invalid response body' }));
        if (res.ok && result.ok) {
            store.state.dirty = false;
            store.state.dirtySections = {};
            btn.textContent = 'Saved ✓';
            setTimeout(() => btn.textContent = originalText, 2000);
            return { ok: true };
        } else {
            throw new Error(result.message || 'Server error');
        }
    } catch (e) {
        console.error('Save error:', e);
        alert('Save Failed: ' + e.message);
        btn.textContent = 'Failed';
        setTimeout(() => btn.textContent = originalText, 2000);
        return { ok: false };
    } finally {
        btn.disabled = false;
    }
}

// Main Actions
document.getElementById('saveBtn').addEventListener('click', () => saveAll());

// HEARTBEAT
setInterval(async () => {
    try {
        await fetch(ROUTES.saveAll, { method: 'HEAD', headers: { 'X-CSRF-TOKEN': CSRF } });
        console.log('Pulse ✓');
    } catch (e) {}
}, 300000);

document.getElementById('publishBtn')?.addEventListener('click', async () => {
    const btn = document.getElementById('publishBtn');
    if (store.state.dirty) {
        // Auto-save without blocking flow
        const saved = await saveAll();
        if (!saved.ok) return;
    }
    
    const originalText = btn.textContent;
    btn.textContent = 'Publishing...';
    btn.disabled = true;
    
    try {
        const res = await fetch(ROUTES.publish, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF }
        });
        const result = await res.json().catch(() => ({ ok: false }));
        
        if (res.ok && result.ok) {
            btn.textContent = 'Published ✓';
            setTimeout(() => btn.textContent = originalText, 3000);
            
            // Show a success message with a link instead of a forced popup
            const statusEl = document.getElementById('sbSaved');
            const originalStatus = statusEl.innerHTML;
            statusEl.innerHTML = '<span style="color:#12a08e;font-weight:bold;">Live Site Updated! <a href="/" target="_blank" style="color:#12a08e;text-decoration:underline;margin-left:5px;">View Site</a></span>';
            setTimeout(() => statusEl.innerHTML = originalStatus, 10000);
        } else {
            throw new Error(result.message || 'Publish server error');
        }
    } catch (e) {
        console.error('Publish error:', e);
        alert('Publish Failed: ' + e.message);
        btn.textContent = 'Error';
        setTimeout(() => btn.textContent = originalText, 2000);
    } finally {
        btn.disabled = false;
    }
});

document.getElementById('undoBtn').addEventListener('click', () => store.undo());
document.getElementById('undoTopBtn').addEventListener('click', () => store.undo());

/**
 * UI Helpers
 */
function rgbToHex(rgb) {
    if (!rgb || !rgb.startsWith('rgb')) return rgb;
    const parts = rgb.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*([\d.]+))?\)$/);
    if (!parts) return rgb;
    const r = parseInt(parts[1]).toString(16).padStart(2, '0');
    const g = parseInt(parts[2]).toString(16).padStart(2, '0');
    const b = parseInt(parts[3]).toString(16).padStart(2, '0');
    return `#${r}${g}${b}`;
}

function populateControls(styles) {
    if (!styles) return;
    Object.keys(styles).forEach(p => {
        const ctrl = document.getElementById('c-' + p) || document.querySelector(`[data-prop="${p}"]`);
        if (ctrl) {
            let val = styles[p] || '';
            if (ctrl.type === 'color') val = rgbToHex(val);
            ctrl.value = val;
        }
    });
    
    if (styles.backgroundImage) {
        const url = styles.backgroundImage.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
        document.getElementById('c-bgUrl').value = url;
    }
    if (styles.overlayOpacity !== undefined) {
        document.getElementById('c-bgOverlay').value = styles.overlayOpacity;
        document.getElementById('bgOverlayVal').textContent = styles.overlayOpacity;
    }
}

function updateLayersUI(activeId) {
    document.querySelectorAll('.ep-layer-item').forEach(el =>
        el.classList.toggle('active', el.dataset.id == activeId));
}

document.getElementById('layerTree')?.addEventListener('click', e => {
    const item = e.target.closest('.ep-layer-item');
    if (item) {
        const id = item.dataset.id;
        const section = store.getSection(id);
        if (section) {
            store.state.active = { sectionId: id, elementId: null };
            bridge.highlight(id);
            document.querySelector('[data-tab="style"]').click();
        }
    }
});

function buildLayers() {
    const tree = document.getElementById('layerTree');
    if (!tree) return;
    tree.innerHTML = store.state.sections.map(s => `
        <div class="ep-layer-item" data-id="${s.id}" data-key="${s.key}">
            <span class="ep-layer-drag">⠿</span>
            <span class="ep-layer-label">${s.label}</span>
            <span class="ep-layer-vis ${s.visible ? 'on' : ''}">👁</span>
        </div>
    `).join('');

    new Sortable(tree, {
        animation: 150, handle: '.ep-layer-drag',
        onEnd: () => {
            const order = [...tree.querySelectorAll('.ep-layer-item')].map(el => el.dataset.id);
            fetch(ROUTES.reorder, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ order })
            });
            bridge.post('reorder-sections', { order });
        }
    });
}

document.getElementById('vis-hide')?.addEventListener('click', function() {
    const { sectionId } = store.state.active;
    if (!sectionId) return;
    const section = store.getSection(sectionId);
    if (!section) return;
    section.visible = !section.visible;
    this.classList.toggle('active', !section.visible);
    bridge.toggleVisibility(sectionId, section.visible);
});

document.getElementById('c-bgOverlay')?.addEventListener('input', function() {
    document.getElementById('bgOverlayVal').textContent = this.value;
    const { sectionId } = store.state.active;
    if (sectionId) {
        const sec = store.getSection(sectionId);
        if (sec) {
            if (!sec.styles) sec.styles = {};
            sec.styles.overlayOpacity = this.value;
            store.state.dirty = true;
        }
        bridge.applyBackground(sectionId, null, this.value);
    }
});

document.getElementById('applyAnimBtn')?.addEventListener('click', () => {
    const { sectionId } = store.state.active;
    if (!sectionId) return;
    const anim = document.getElementById('c-anim').value;
    const dur = document.getElementById('c-animDur').value;
    const delay = document.getElementById('c-animDelay').value;
    applyProp('animation', `${anim} ${dur} ${delay} forwards`);
});

window.addEventListener('DOMContentLoaded', () => {
    buildLayers();
});

function populateHeroSlides() {
    for (let i = 1; i <= 4; i++) {
        const urlInput = document.getElementById(`c-hero_bg_${i}`);
        const overlayInput = document.getElementById(`c-hero_bg_overlay_${i}`);
        const thumb = document.getElementById(`heroThumb${i}`);
        
        // Use hidden input value if set, else fallback to something?
        // Actually, the blade already initializes these if they exist.
    }
}

function populateStaticHero(styles) {
    const thumb = document.getElementById('staticHeroThumb');
    const hiddenInput = document.getElementById('c-hero_bg_subpage');
    const overlayInput = document.getElementById('c-hero_bg_subpage_overlay');
    
    if (styles.backgroundImage && styles.backgroundImage !== 'none') {
        const url = styles.backgroundImage.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
        hiddenInput.value = url;
        thumb.innerHTML = `<img src="${url}" style="width:100%;height:100%;object-fit:cover;">`;
    }
    
    if (styles.overlayOpacity) {
        overlayInput.value = styles.overlayOpacity;
    }
}

// Add the event listeners for hero configuration
window.addEventListener('DOMContentLoaded', () => {
    const applyHeroSlidesBtn = document.getElementById('applyHeroSlidesBtn');
    if (applyHeroSlidesBtn) {
        applyHeroSlidesBtn.addEventListener('click', async () => {
             const updates = {};
             for(let i=1; i<=4; i++) {
                 updates[`hero_bg_${i}`] = document.getElementById(`c-hero_bg_${i}`).value;
                 updates[`hero_bg_overlay_${i}`] = document.getElementById(`c-hero_bg_overlay_${i}`).value;
             }
             await saveAll(updates);
        });
    }

    document.querySelectorAll('[id^="heroUpload"]').forEach((input, idx) => {
        const i = idx + 1;
        input.addEventListener('change', async function() {
            if (!this.files || !this.files[0]) return;
            try {
                const res = await uploadToService(this.files[0]);
                if (res.ok) {
                    document.getElementById(`c-hero_bg_${i}`).value = res.url;
                    document.getElementById(`heroThumb${i}`).innerHTML = `<img src="${res.url}" style="width:100%;height:100%;object-fit:cover;">`;
                    bridge.updateHeroSlide(i - 1, res.url);
                }
            } catch (err) { console.error(err); }
        });
    });

    const applyStaticHeroBtn = document.getElementById('applyStaticHeroBtn');
    if (applyStaticHeroBtn) {
        applyStaticHeroBtn.addEventListener('click', async () => {
            const url = document.getElementById('c-hero_bg_subpage').value;
            const overlay = document.getElementById('c-hero_bg_subpage_overlay').value;
            await saveAll({ hero_bg_subpage: url, hero_bg_subpage_overlay: overlay });
            bridge.applyBackground(store.state.active.sectionId, url, overlay);
        });
    }

    const staticHeroUpload = document.getElementById('staticHeroUpload');
    if (staticHeroUpload) {
        staticHeroUpload.addEventListener('change', async function() {
            if (!this.files || !this.files[0]) return;
            try {
                const res = await uploadToService(this.files[0]);
                if (res.ok) {
                    document.getElementById('c-hero_bg_subpage').value = res.url;
                    document.getElementById('staticHeroThumb').innerHTML = `<img src="${res.url}" style="width:100%;height:100%;object-fit:cover;">`;
                    bridge.applyBackground(store.state.active.sectionId, res.url, document.getElementById('c-hero_bg_subpage_overlay').value);
                }
            } catch (err) { console.error(err); }
        });
    }
});

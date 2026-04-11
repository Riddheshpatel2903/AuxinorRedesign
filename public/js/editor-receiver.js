/**
 * Auxinor Editor Receiver (Refactored)
 * Light-weight Renderer for the Iframe.
 */

document.body.classList.add('editor-mode');

function emit(action, payload) {
    window.parent.postMessage({ src: 'auxinor-site', action, payload }, '*');
}

/**
 * Alpine Sync: Watch for state changes to notify editor
 */
document.addEventListener('alpine:init', () => {
    Alpine.effect(() => {
        const hero = document.querySelector('[data-section-key="home_hero"]');
        if (hero && window.Alpine) {
            const data = window.Alpine.$data(hero);
            if (data && data.activeIndex !== undefined) {
                emit('hero-slide-switch', { index: data.activeIndex });
            }
        }
    });
});

/**
 * Helper: Extract basic computed styles for the sidebar
 */
function getStyles(el) {
    const cs = window.getComputedStyle(el);
    const props = ['fontSize', 'fontWeight', 'fontFamily', 'color', 'backgroundColor', 'backgroundImage', 'lineHeight', 'letterSpacing', 'borderRadius', 'boxShadow', 'width', 'height', 'paddingTop', 'paddingBottom', 'paddingLeft', 'paddingRight'];
    
    const styles = props.reduce((acc, p) => ({ ...acc, [p]: cs[p] }), {});
    
    // Also try to extract overlay opacity from the child if it exists
    const overlay = el.querySelector('.ed-bg-overlay');
    if (overlay) {
        const ocs = window.getComputedStyle(overlay);
        const rgba = ocs.backgroundColor.match(/rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*([\d.]+))?\)/);
        if (rgba && rgba[4]) styles.overlayOpacity = rgba[4];
    }
    
    return styles;
}

/**
 * Auto-tagging: Ensure all meaningful elements have IDs
 */
function autoTag() {
    document.querySelectorAll('[data-section-id]').forEach(sec => {
        const sid = sec.dataset.sectionId;
        const tags = ['h1','h2','h3','h4','h5','p','span','a','button','img','li','label','div'];
        let counter = 0;
        
        const tagNode = (node) => {
            if (node.nodeType !== 1 || node.classList.contains('ed-bg-overlay') || node.classList.contains('ed-section-tab')) return;
            if (tags.includes(node.tagName.toLowerCase())) {
                if (!node.hasAttribute('data-element-id')) {
                    node.dataset.elementId = `auto_${sid}_${node.tagName.toLowerCase()}_${counter++}`;
                    node.dataset.elementType = node.tagName.toLowerCase();
                    node.dataset.elementKey = `${node.tagName.toLowerCase()} ${counter}`;
                }
                
                // Add hover effects
                node.style.cursor = 'pointer';
                node.addEventListener('mouseenter', e => {
                    e.stopPropagation();
                    node.style.outline = '1px dashed rgba(18,160,142,0.8)';
                    node.style.outlineOffset = '2px';
                });
                node.addEventListener('mouseleave', e => {
                    e.stopPropagation();
                    node.style.outline = 'transparent';
                });
            }
            Array.from(node.children).forEach(tagNode);
        };

        // Add Section Selection Tab
        if (!sec.querySelector('.ed-section-tab')) {
            const tab = document.createElement('div');
            tab.className = 'ed-section-tab';
            tab.innerHTML = `<span>Section: ${sec.dataset.sectionLabel || sid}</span>`;
            // Position tab slightly inside to avoid being cut off by header/overflow
            tab.style.cssText = 'position:absolute; top:10px; left:10px; background:#12a08e; color:#fff; font-family:monospace; font-size:9px; z-index:99999; padding:4px 10px; cursor:pointer; text-transform:uppercase; letter-spacing:1px; display:none; pointer-events:auto;';
            sec.style.position = (window.getComputedStyle(sec).position === 'static') ? 'relative' : sec.style.position;
            sec.prepend(tab);

            sec.addEventListener('mouseenter', () => { tab.style.display = 'block'; sec.style.outline = '2px solid #12a08e'; });
            sec.addEventListener('mouseleave', () => { tab.style.display = 'none'; sec.style.outline = 'transparent'; });
            tab.addEventListener('click', (e) => {
                e.stopPropagation();
                e.preventDefault();
                emit('section-click', {
                    id: sec.dataset.sectionId,
                    key: sec.dataset.sectionKey,
                    label: sec.dataset.sectionLabel,
                    styles: getStyles(sec)
                });
            });
        }

        Array.from(sec.children).forEach(tagNode);
    });
}

/**
 * State Renderer: Patch the DOM based on incoming state
 */
function renderState(state) {
    if (!state.sections) return;
    autoTag(); // Re-tag after sync if any new nodes added
    state.sections.forEach(secData => {
        const sec = document.querySelector(`[data-section-id="${secData.id}"]`);
        if (!sec) return;

        // 1. Visibility
        sec.style.display = secData.visible !== false ? '' : 'none';

        // 2. Section Styles
        if (secData.styles) {
            Object.assign(sec.style, secData.styles);
            if (secData.styles.backgroundImage) {
                sec.style.backgroundSize = 'cover';
                sec.style.backgroundPosition = 'center';
                sec.style.backgroundRepeat = 'no-repeat';
            }
            if (secData.styles.overlayOpacity !== undefined && secData.styles.backgroundImage) {
                let overlay = sec.querySelector('.ed-bg-overlay');
                if (!overlay) {
                    overlay = document.createElement('div');
                    overlay.className = 'ed-bg-overlay';
                    overlay.style.cssText = 'position:absolute; inset:0; z-index:0; pointer-events:none;';
                    sec.style.position = 'relative';
                    sec.prepend(overlay);
                }
                overlay.style.backgroundColor = `rgba(0,0,0,${secData.styles.overlayOpacity})`;
                overlay.style.display = 'block';
            } else {
                let overlay = sec.querySelector('.ed-bg-overlay');
                if (overlay) overlay.style.display = 'none';
            }
        }

        // 3. Section Content (Foreground Images & Text)
        if (secData.content) {
            Object.entries(secData.content).forEach(([key, val]) => {
                if (key.startsWith('el_')) {
                    const elId = key.replace('el_', '').replace('href_', '').replace('img_', '').replace('setting:', '');
                    const el = sec.querySelector(`[data-element-id*="${elId}"]`);
                    if (el) {
                        if (key.startsWith('el_href_')) {
                            el.href = val;
                        } else if (key.startsWith('el_img_')) {
                            if (el.tagName === 'IMG') el.src = val;
                            else el.style.backgroundImage = `url("${val}")`;
                        } else if (key.startsWith('el_setting:') && (typeof val === 'string' && (val.startsWith('http') || val.startsWith('/')))) {
                            if (el.tagName === 'IMG') el.src = val;
                            else el.style.backgroundImage = `url("${val}")`;
                        } else if (!key.includes('style_')) {
                            el.innerHTML = val;
                        }
                    }
                }
            });
        }
        
        // 4. Element Specific Styles (if stored in section.styles)
        if (secData.styles) {
            Object.entries(secData.styles).forEach(([key, styles]) => {
                if (key.startsWith('el_style_')) {
                    const elId = key.replace('el_style_', '');
                    const el = sec.querySelector(`[data-element-id="${elId}"]`);
                    if (el) Object.assign(el.style, styles);
                }
            });
        }
    });

    // Handle reorder
    const firstSec = document.querySelector('[data-section-id]');
    if (firstSec && state.sections.length) {
        const parent = firstSec.parentElement;
        const currentIds = [...parent.children].filter(c => c.dataset.sectionId).map(c => c.dataset.sectionId);
        const targetIds = state.sections.map(s => String(s.id));
        
        if (JSON.stringify(currentIds) !== JSON.stringify(targetIds)) {
            const fragment = document.createDocumentFragment();
            targetIds.forEach(id => {
                const el = parent.querySelector(`[data-section-id="${id}"]`);
                if (el) fragment.appendChild(el);
            });
            parent.appendChild(fragment);
        }
    }
}

/**
 * Global Event Listeners
 */
document.addEventListener('click', e => {
    // 1. Block default nav
    const link = e.target.closest('a');
    if (link && !link.hasAttribute('target')) e.preventDefault();
    const btn = e.target.closest('button, input[type="submit"]');
    if (btn) e.preventDefault();

    // 2. Handle Selection
    const element = e.target.closest('[data-element-id]');
    if (element) {
        e.stopPropagation();
        const section = element.closest('[data-section-id]');
        emit('element-click', {
            id: element.dataset.elementId,
            sectionId: section?.dataset.sectionId,
            tag: element.tagName.toLowerCase(),
            content: element.innerHTML,
            href: element.getAttribute('href') || '',
            styles: getStyles(element)
        });
        return;
    }

    const section = e.target.closest('[data-section-id]');
    if (section) {
        e.stopPropagation();
        emit('section-click', {
            id: section.dataset.sectionId,
            key: section.dataset.sectionKey,
            label: section.dataset.sectionLabel,
            styles: getStyles(section)
        });
        return;
    }

    emit('deselect', {});
}, true);

// Window Message Listener
window.addEventListener('message', e => {
    if (e.data?.src !== 'auxinor-editor') return;
    const { action, payload } = e.data;

    if (action === 'sync-state') {
        renderState(payload.state);
    } else if (action === 'apply-style') {
        const target = payload.elementId 
            ? document.querySelector(`[data-element-id="${payload.elementId}"]`)
            : document.querySelector(`[data-section-id="${payload.sectionId}"]`);
        if (target) target.style[payload.prop] = payload.value;
    } else if (action === 'apply-content') {
        const el = document.querySelector(`[data-element-id="${payload.elementId}"]`);
        if (el) {
            if (payload.src) {
                if (el.tagName === 'IMG') {
                    el.src = payload.src;
                } else if (el.dataset.elementId && el.dataset.elementId.includes('setting:') && el.dataset.elementId.includes('bg')) {
                    // Service card backgrounds and similar bg-div elements: update CSS background-image
                    el.style.backgroundImage = `url('${payload.src}')`;
                    el.style.backgroundSize = 'cover';
                    el.style.backgroundPosition = 'center';
                } else if (el.dataset.elementId && el.dataset.elementId.includes('logo')) {
                    el.innerHTML = `<img src="${payload.src}" alt="Logo" style="height:44px; width:auto;">`;
                } else {
                    el.innerHTML = `<img src="${payload.src}" alt="Image" style="max-width:100%;height:auto;">`;
                }
            }
            if (payload.content !== null && payload.content !== undefined) el.innerHTML = payload.content;
            if (payload.href !== null && payload.href !== undefined) el.href = payload.href;
        }

    } else if (action === 'apply-bg') {
        const target = payload.elementId
            ? document.querySelector(`[data-element-id="${payload.elementId}"]`)
            : document.querySelector(`[data-section-id="${payload.sectionId}"]`);
        if (target) {
            let overlay = target.querySelector(':scope > .ed-bg-overlay');
            
            // Core background target: if it's a section and has an overlay, use the overlay
            const bgTarget = (!payload.elementId && overlay) ? overlay : target;

            if (payload.url) {
                bgTarget.style.backgroundImage = `url('${payload.url}')`;
                bgTarget.style.backgroundSize = 'cover';
                bgTarget.style.backgroundPosition = 'center';
                bgTarget.style.backgroundRepeat = 'no-repeat';
                
                // If it's the section itself, but we have an overlay, ensure target image isn't hidden
                if (bgTarget === target && overlay) {
                    overlay.style.backgroundColor = 'transparent';
                    overlay.style.backgroundImage = 'none';
                }
            } else {
                bgTarget.style.backgroundImage = '';
            }

            if (payload.url && payload.opacity !== undefined) {
                if (!overlay) {
                    overlay = document.createElement('div');
                    overlay.className = 'ed-bg-overlay';
                    overlay.style.cssText = 'position:absolute; inset:0; z-index:0; pointer-events:none;';
                    const pos = window.getComputedStyle(target).position;
                    if (pos === 'static') target.style.position = 'relative';
                    target.prepend(overlay);
                }
                overlay.style.backgroundColor = `rgba(0,0,0,${payload.opacity})`;
                overlay.style.display = 'block';
            } else if (overlay && !payload.url) {
                overlay.style.display = 'none';
            }
        }
    } else if (action === 'toggle-vis') {
        const sec = document.querySelector(`[data-section-id="${payload.sectionId}"]`);
        if (sec) sec.style.display = payload.visible ? '' : 'none';
    } else if (action === 'highlight-section') {
        const sec = document.querySelector(`[data-section-id="${payload.sectionId}"]`);
        if (sec) sec.scrollIntoView({ behavior: 'smooth', block: 'center' });
    } else if (action === 'update-hero-slide') {
        const hero = document.querySelector('[data-section-key="home_hero"]');
        if (hero && window.Alpine) {
            const data = window.Alpine.$data(hero);
            if (data && data.images && data.images[payload.index]) {
                if (payload.url !== undefined) data.images[payload.index].url = payload.url;
                if (payload.overlay !== undefined) data.images[payload.index].overlay = payload.overlay;
                
                // Switch to this slide to show the change
                data.activeIndex = payload.index;
            }
        }
    } else if (action === 'reload-request') {
        window.location.reload();
    }
});

autoTag(); // Initial tag

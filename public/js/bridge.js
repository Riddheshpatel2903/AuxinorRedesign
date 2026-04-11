/**
 * Auxinor Editor Bridge
 * Standardized postMessage communication.
 */

class EditorBridge {
    constructor(iframeId) {
        this.iframeId = iframeId;
    }

    get frame() {
        return document.getElementById(this.iframeId)?.contentWindow;
    }

    post(action, payload = {}) {
        if (!this.frame) return;
        this.frame.postMessage({ src: 'auxinor-editor', action, payload }, '*');
    }

    // Unified commands
    applyStyle(sectionId, elementId, prop, value) {
        this.post('apply-style', { sectionId, elementId, prop, value });
    }

    applyContent(elementId, content, href = null, src = null) {
        this.post('apply-content', { elementId, content, href, src });
    }

    applyBackground(sectionId, url, opacity, elementId = null) {
        this.post('apply-bg', { sectionId, url, opacity, elementId });
    }

    syncState(state) {
        // Deep clone the state to strip Proxies, which cannot be cloned by postMessage
        const cleanState = JSON.parse(JSON.stringify(state));
        this.post('sync-state', { state: cleanState });
    }

    highlight(sectionId) {
        this.post('highlight-section', { sectionId });
    }

    toggleVisibility(sectionId, visible) {
        this.post('toggle-vis', { sectionId, visible });
    }

    updateHeroSlide(index, url, overlay) {
        this.post('update-hero-slide', { index, url, overlay });
    }
}

window.EditorBridge = EditorBridge;

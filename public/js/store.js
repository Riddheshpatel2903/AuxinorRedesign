/**
 * Auxinor Editor Store
 * Centralized, reactive state management using Proxies.
 */

class EditorStore {
    constructor(initialState = {}) {
        this.history = [];
        this.redoStack = [];
        this.maxHistory = 50;
        this.listeners = [];
        
        this.state = this._createReactiveProxy({
            sections: initialState.sections || [],
            settings: initialState.settings || {},
            active: { sectionId: null, elementId: null },
            dirty: false,
            dirtySections: {}, // Track section IDs that actually changed
            ...initialState
        });
    }

    _createReactiveProxy(data) {
        const self = this;
        return new Proxy(data, {
            set(target, prop, value) {
                const oldValue = target[prop];
                if (oldValue === value && typeof value !== 'object') return true;
                
                target[prop] = value;
                
                // Track history and dirty state globally
                if (prop !== 'active' && prop !== 'dirty' && prop !== '_isProxy') {
                    // Bubble up dirty flag to the root state
                    if (self.state) self.state.dirty = true;
                    self._pushHistory();
                }
                
                self._notify(prop, value);
                return true;
            },
            get(target, prop) {
                const value = target[prop];
                if (value && typeof value === 'object' && !value._isProxy && prop !== 'active') {
                    target[prop] = self._createReactiveProxy(value);
                    target[prop]._isProxy = true;
                }
                return value;
            }
        });
    }

    markDirty(sectionId) {
        if (!sectionId) return;
        this.state.dirtySections[sectionId] = true;
        this.state.dirty = true;
    }

    _pushHistory() {
        const snapshot = JSON.stringify(this.state);
        if (this.history.length > 0 && this.history[this.history.length - 1] === snapshot) return;
        
        this.history.push(snapshot);
        if (this.history.length > this.maxHistory) this.history.shift();
        this.redoStack = []; // Clear redo on new action
    }

    undo() {
        if (this.history.length <= 1) return;
        this.redoStack.push(this.history.pop());
        const lastState = JSON.parse(this.history[this.history.length - 1]);
        this._batchUpdate(lastState);
        this.state.dirty = true;
    }

    redo() {
        if (this.redoStack.length === 0) return;
        const nextState = JSON.parse(this.redoStack.pop());
        this.history.push(JSON.stringify(nextState));
        this._batchUpdate(nextState);
    }

    _batchUpdate(newState) {
        // Silently update state without triggering individual proxy sets for every key
        Object.assign(this.state, newState);
        this._notify('batch', newState);
    }

    subscribe(callback) {
        this.listeners.push(callback);
        return () => {
            this.listeners = this.listeners.filter(l => l !== callback);
        };
    }

    _notify(prop, value) {
        this.listeners.forEach(l => l(prop, value, this.state));
    }

    // Helpers
    getSection(id) {
        return this.state.sections.find(s => s.id == id);
    }

    updateSectionStyle(id, prop, value) {
        const section = this.getSection(id);
        if (!section) return;
        if (!section.styles) section.styles = {};
        section.styles[prop] = value;
        this.markDirty(id);
    }

    updateElementContent(sectionId, elementId, content, href = null, src = null) {
        const section = this.getSection(sectionId);
        if (!section) return;
        if (!section.content) section.content = {};
        this.markDirty(sectionId);
        
        // Fix: Don't double-prefix if already prefixed
        const key = elementId.startsWith('el_') ? elementId : `el_${elementId}`;
        const hrefKey = elementId.startsWith('el_') ? `el_href_${elementId.replace('el_', '')}` : `el_href_${elementId}`;
        const imgKey = elementId.startsWith('el_') ? `el_img_${elementId.replace('el_', '')}` : `el_img_${elementId}`;

        if (content !== null) section.content[key] = content;
        if (href !== null) section.content[hrefKey] = href;
        if (src !== null) section.content[imgKey] = src;
    }
}

window.EditorStore = EditorStore;

document.body.classList.add('editor-mode')

function emit(action, payload) {
  window.parent.postMessage({ src: 'auxinor-site', action, payload }, '*')
}

// Global click interceptor
document.addEventListener('click', e => {
  if (!document.body.classList.contains('editor-mode')) return;

  // Intercept links and buttons
  const link = e.target.closest('a');
  if (link && !link.hasAttribute('target')) e.preventDefault();
  const btn = e.target.closest('button, input[type="submit"]');
  if (btn) e.preventDefault();

  // Handle Element Selection
  const el = e.target.closest('[data-element-key]');
  if (el) {
    e.stopPropagation();
    const sec = el.closest('[data-section-index]');
    emit('element-click', {
      id: sec?.dataset.sectionIndex, // Treat index as the ID for editor.js compatibility
      key: el.dataset.elementKey,
      type: el.dataset.elementType || 'text',
      index: sec?.dataset.sectionIndex
    });
    highlightElement(el);
    return;
  }

  // Handle Section Selection
  const sec = e.target.closest('[data-section-index]');
  if (sec) {
    e.stopPropagation();
    emit('section-click', {
      id: sec.dataset.sectionIndex,
      type: sec.dataset.sectionType,
      label: sec.dataset.sectionType
    });
    highlightSection(sec);
    return;
  }

  // Deselect if clicking background
  emit('deselect', {});
}, true);

function highlightElement(el) {
    document.querySelectorAll('[data-editing]').forEach(e => e.removeAttribute('data-editing'));
    el.setAttribute('data-editing', 'true');
}

function highlightSection(sec) {
    document.querySelectorAll('.ed-sel').forEach(s => {
        s.style.outlineColor = 'transparent'; 
        s.classList.remove('ed-sel');
    });
    sec.style.outlineColor = '#f59e0b';
    sec.classList.add('ed-sel');
    sec.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

window.addEventListener('message', e => {
  if (e.data?.src !== 'auxinor-editor') return
  const { action, payload } = e.data
  
  if (action === 'apply-style') {
    const sec = document.querySelector(`[data-section-index="${payload.index}"]`);
    const target = payload.key 
        ? sec?.querySelector(`[data-element-key="${payload.key}"]`)
        : sec;
    
    if (target) {
        const kebab = payload.prop.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();
        target.style.setProperty(kebab, payload.value);
    }
  }

  if (action === 'apply-content') {
    const sec = document.querySelector(`[data-section-index="${payload.index}"]`);
    const el = sec?.querySelector(`[data-element-key="${payload.val.key || payload.key}"]`);
    if (el) {
        if (el.tagName === 'IMG') {
            el.src = payload.val;
        } else {
            el.innerHTML = payload.val;
        }
    }
  }

  if (action === 'reload-preview') {
      window.location.reload();
  }

  if (action === 'highlight-section') {
      const sec = document.querySelector(`[data-section-index="${payload.index}"]`);
      if (sec) highlightSection(sec);
  }
});

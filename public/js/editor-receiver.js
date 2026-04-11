document.body.classList.add('editor-mode')

function emit(action, payload) {
  window.parent.postMessage({ src: 'auxinor-site', action, payload }, '*')
}

// Intercept all links and buttons globally in editor mode
document.addEventListener('click', e => {
  const link = e.target.closest('a');
  if (link && !link.hasAttribute('target')) e.preventDefault();
  const btn = e.target.closest('button, input[type="submit"]');
  if (btn) e.preventDefault();
}, true);

function getStyles(el) {
  const cs = window.getComputedStyle(el)
  return ['fontSize','fontWeight','fontFamily','color','backgroundColor',
    'paddingTop','paddingBottom','paddingLeft','paddingRight',
    'marginTop','marginBottom','width','height','minHeight',
    'display','alignItems','justifyContent','gridTemplateColumns',
    'gap','letterSpacing','lineHeight','borderRadius','boxShadow'].reduce((o,p) => ({...o,[p]:cs[p]}),{})
}

// Make sections selectable
document.querySelectorAll('[data-section-id]').forEach(sec => {
  sec.style.cursor = 'pointer'
  sec.style.outline = '2px solid transparent'
  sec.style.outlineOffset = '-2px'
  sec.style.transition = 'outline-color .15s'

  sec.addEventListener('mouseenter', () => {
    if (!sec.classList.contains('ed-sel'))
      sec.style.outlineColor = 'rgba(18,160,142,0.4)'
  })
  sec.addEventListener('mouseleave', () => {
    if (!sec.classList.contains('ed-sel'))
      sec.style.outlineColor = 'transparent'
  })
  sec.addEventListener('click', e => {
    if (e.target.closest('[data-element-id]')) return
    e.stopPropagation()
    document.querySelectorAll('[data-section-id]').forEach(s => {
      s.style.outlineColor = 'transparent'; s.classList.remove('ed-sel')
    })
    sec.style.outlineColor = '#f59e0b'
    sec.classList.add('ed-sel')
    emit('section-click', {
      id: sec.dataset.sectionId, key: sec.dataset.sectionKey,
      label: sec.dataset.sectionLabel, styles: getStyles(sec)
    })
  })
})

// Auto-tag all meaningful child elements
document.querySelectorAll('[data-section-id]').forEach(sec => {
  const sid = sec.dataset.sectionId;
  const tags = ['h1','h2','h3','h4','h5','p','span','a','button','img','div','li','label'];
  let counter = 0;
  
  // Recursively tag elements, avoiding editor UI elements
  const tagNode = (node) => {
    if (node.nodeType !== 1) return;
    if (node.classList.contains('ed-bg-overlay')) return;
    
    // Only tag meaningful visual elements
    if (tags.includes(node.tagName.toLowerCase())) {
      if (!node.hasAttribute('data-element-id')) {
        node.dataset.elementId = `auto_${sid}_${node.tagName.toLowerCase()}_${counter++}`;
        node.dataset.elementType = node.tagName.toLowerCase();
        node.dataset.elementKey = `${node.tagName.toLowerCase()} ${counter}`;
      }
    }
    
    Array.from(node.children).forEach(tagNode);
  };
  
  Array.from(sec.children).forEach(tagNode);
});

// Make elements selectable
document.querySelectorAll('[data-element-id]').forEach(el => {
  el.style.cursor = 'pointer' // changed to pointer to imply clickability
  el.addEventListener('mouseenter', (e) => {
    e.stopPropagation();
    el.style.outline = '1px dashed rgba(18,160,142,0.8)';
    el.style.outlineOffset = '2px';
  })
  el.addEventListener('mouseleave', (e) => {
    e.stopPropagation();
    el.style.outline = 'transparent';
  })
  el.addEventListener('click', e => {
    e.stopPropagation()
    e.preventDefault()
    const sec = el.closest('[data-section-id]');
    emit('element-click', {
      sectionId: sec?.dataset.sectionId,
      id: el.dataset.elementId, key: el.dataset.elementKey,
      type: el.dataset.elementType || 'text',
      content: el.innerText, tag: el.tagName.toLowerCase(),
      src: el.src || '',
      href: el.href || el.getAttribute('href') || '', styles: getStyles(el)
    })
  })
})

// Deselect on body click
document.addEventListener('click', e => {
  if (!e.target.closest('[data-section-id]')) emit('deselect', {})
})

// Receive commands from parent editor
window.addEventListener('message', e => {
  if (e.data?.src !== 'auxinor-editor') return
  const { action, payload } = e.data

  if (action === 'apply-style') {
    const t = payload.elementId
      ? document.querySelector('[data-element-id="'+payload.elementId+'"]')
      : document.querySelector('[data-section-id="'+payload.sectionId+'"]')
    if (t) t.style[payload.prop] = payload.value
  }

  if (action === 'apply-content') {
    const el = document.querySelector('[data-element-id="'+payload.elementId+'"]')
    if (el) {
      if (payload.src && el.tagName.toLowerCase() === 'img') {
        el.src = payload.src;
      } else {
        el.innerHTML = payload.content;
        if (payload.href) el.href = payload.href;
      }
    }
  }

  if (action === 'apply-bg') {
    const sec = document.querySelector('[data-section-id="'+payload.sectionId+'"]')
    if (!sec) return
    sec.style.backgroundImage = `url('${payload.url}')`
    sec.style.backgroundSize = 'cover'
    sec.style.backgroundPosition = 'center'
    let ov = sec.querySelector('.ed-bg-overlay')
    if (!ov) {
      ov = document.createElement('div')
      ov.className = 'ed-bg-overlay'
      ov.style.cssText = 'position:absolute;inset:0;pointer-events:none;z-index:1'
      sec.style.position = 'relative'
      sec.prepend(ov)
    }
    ov.style.background = `rgba(13,17,23,${payload.opacity})`
  }

  if (action === 'toggle-vis') {
    const sec = document.querySelector('[data-section-id="'+payload.sectionId+'"]')
    if (sec) sec.style.display = payload.visible ? '' : 'none'
  }

  if (action === 'highlight-section') {
    document.querySelectorAll('[data-section-id]').forEach(s => {
      s.style.outlineColor = 'transparent'; s.classList.remove('ed-sel')
    })
    const sec = document.querySelector('[data-section-id="'+payload.sectionId+'"]')
    if (sec) {
      sec.scrollIntoView({ behavior: 'smooth', block: 'center' })
      sec.style.outlineColor = '#f59e0b'
      sec.classList.add('ed-sel')
    }
  }

  if (action === 'apply-anim') {
    const sec = document.querySelector('[data-section-id="'+payload.sectionId+'"]')
    if (!sec) return
    ['sr','sr-l','sr-r','sr-up','sr-bounce','sr-stagger'].forEach(c =>
      sec.classList.remove(c))
    if (payload.animClass) {
      sec.style.visibility = 'visible';
      sec.style.opacity = '1';
      sec.classList.remove('animated')
      sec.classList.add(payload.animClass)
      setTimeout(() => sec.classList.add('animated'), 10)
    }
  }

  if (action === 'reorder-sections') {
    const firstSec = document.querySelector('[data-section-id]');
    if (!firstSec) return;
    const parent = firstSec.parentElement;
    const fragment = document.createDocumentFragment();
    payload.order.forEach(id => {
      const sec = document.querySelector('[data-section-id="'+id+'"]');
      if (sec) fragment.appendChild(sec);
    });
    parent.appendChild(fragment);
    // Refresh ScrollReveal if active
    if (window.sr) window.sr.reveal('.sr-up, .sr-l, .sr-r, .sr-bounce', { interval: 100 });
  }
})

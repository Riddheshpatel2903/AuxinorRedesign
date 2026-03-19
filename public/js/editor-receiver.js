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
    'gap','letterSpacing','lineHeight'].reduce((o,p) => ({...o,[p]:cs[p]}),{})
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

// Make elements selectable
document.querySelectorAll('[data-element-id]').forEach(el => {
  el.style.cursor = 'text'
  el.addEventListener('mouseenter', () =>
    el.style.outlineColor = 'rgba(18,160,142,0.5)')
  el.addEventListener('mouseleave', () =>
    el.style.outlineColor = 'transparent')
  el.addEventListener('click', e => {
    e.stopPropagation()
    e.preventDefault()
    emit('element-click', {
      id: el.dataset.elementId, key: el.dataset.elementKey,
      type: el.dataset.elementType || 'text',
      content: el.innerText, tag: el.tagName.toLowerCase(),
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
    if (el) { el.innerHTML = payload.content; if (payload.href) el.href = payload.href }
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
      sec.classList.remove('animated')
      sec.classList.add(payload.animClass)
      setTimeout(() => sec.classList.add('animated'), 100)
    }
  }
})

// Parse delay from class like "data-delay-500" or attribute data-delay
function getDelay(el) {
  // Check for data-delay attribute
  if (el.dataset.delay) return parseInt(el.dataset.delay)
  // Check for data-delay-NNN class
  const match = [...el.classList].find(c => c.startsWith('data-delay-'))
  if (match) return parseInt(match.replace('data-delay-', ''))
  // Check inline style --ds variable used in product grid
  const ds = getComputedStyle(el).getPropertyValue('--ds')
  if (ds) return parseInt(ds)
  return 0
}

// Standard scroll reveal
const revealObs = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const delay = getDelay(entry.target)
      setTimeout(() => {
        entry.target.classList.add('animated')
      }, delay)
      revealObs.unobserve(entry.target)
    }
  })
}, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' })

document.querySelectorAll(
  '.sr, .sr-l, .sr-r, .sr-up, .sr-bounce, .sr-drop, .sr-product'
).forEach(el => revealObs.observe(el))

// Staggered grid children (.sr-stagger parent)
const staggerObs = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const children = entry.target.children
      Array.from(children).forEach((child, i) => {
        setTimeout(() => {
          child.style.transition =
            'opacity 0.65s ease, transform 0.75s cubic-bezier(0.16,1,0.3,1)'
          child.style.opacity = '1'
          child.style.transform = 'none'
        }, i * 100)
      })
      staggerObs.unobserve(entry.target)
    }
  })
}, { threshold: 0.08 })

document.querySelectorAll('.sr-stagger').forEach(el => {
  // Set initial hidden state on children
  Array.from(el.children).forEach(child => {
    child.style.opacity = '0'
    child.style.transform = 'translateY(28px)'
  })
  staggerObs.observe(el)
})

// Counter animation — targets elements with data-count attribute
// These already exist in home.blade.php hero and about section
function countUp(el, target) {
  let n = 0
  const step = Math.ceil(target / 50)
  const suffix = target > 10 ? '+' : ''
  const timer = setInterval(() => {
    n = Math.min(n + step, target)
    el.textContent = n + suffix
    if (n >= target) clearInterval(timer)
  }, 28)
}

const counterObs = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const target = parseInt(entry.target.dataset.count)
      if (!isNaN(target)) countUp(entry.target, target)
      counterObs.unobserve(entry.target)
    }
  })
}, { threshold: 0.4 })

document.querySelectorAll('[data-count]').forEach(el => {
  counterObs.observe(el)
})

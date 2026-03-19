// Counter animations using Intersection Observer
document.addEventListener('DOMContentLoaded', () => {
    const counters = document.querySelectorAll('[data-count]');
    const speed = 200; // The lower the slower

    const animateCounters = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const updateCount = () => {
                    const target = +counter.getAttribute('data-count');
                    let count = +counter.innerText.replace(/,/g, '');
                    // Some targets might end in '+' or 'K', let's strip that if present
                    const rawTargetStr = counter.getAttribute('data-count').replace(/[^0-9.]/g, '');
                    const rawTarget = parseFloat(rawTargetStr);
                    const suffix = counter.getAttribute('data-count').replace(/[0-9.]/g, '');

                    if (isNaN(rawTarget)) {
                        counter.innerText = counter.getAttribute('data-count');
                        return;
                    }

                    const inc = rawTarget / speed;

                    if (count < rawTarget) {
                        count = Math.ceil(count + inc);
                        counter.innerText = count + suffix;
                        setTimeout(updateCount, 15);
                    } else {
                        counter.innerText = (rawTarget % 1 !== 0 ? rawTarget : rawTarget) + suffix;
                    }
                };

                updateCount();
                observer.unobserve(counter);
            }
        });
    };

    const counterObserver = new IntersectionObserver(animateCounters, {
        root: null,
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    counters.forEach(counter => {
        counterObserver.observe(counter);
    });
});

// Filter animations
document.addEventListener('DOMContentLoaded', () => {
    const filterBtns = document.querySelectorAll('.pf');
    const productItems = document.querySelectorAll('.product-item');

    if (filterBtns.length === 0 || productItems.length === 0) return;

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active style from all buttons
            filterBtns.forEach(b => {
                b.classList.remove('active', 'bg-teal', 'text-white');
                b.classList.add('bg-white', 'text-ink', 'hover:bg-bg');
            });

            // Add active style to clicked button
            btn.classList.add('active', 'bg-teal', 'text-white');
            btn.classList.remove('bg-white', 'text-ink', 'hover:bg-bg');

            const filterValue = btn.getAttribute('data-filter');

            productItems.forEach(item => {
                // First fade out
                item.style.opacity = '0';
                item.style.transform = 'scale(0.95)';
                
                setTimeout(() => {
                    if (filterValue === 'all' || item.matches(filterValue)) {
                        item.style.display = 'flex';
                        // Small delay to allow display:flex to apply before fading in
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'scale(1)';
                        }, 50);
                    } else {
                        item.style.display = 'none';
                    }
                }, 300); // Wait for fade out animation
            });
        });
    });
});

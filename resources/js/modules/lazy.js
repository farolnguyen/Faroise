export const lazyMethods = {
    _resetChipVisibility() {
        document.querySelectorAll('[data-sound-chip]').forEach(el => {
            el.style.opacity   = '1';
            el.style.transform = 'translateX(0)';
        });
    },

    _initLazyChips() {
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.style.opacity   = '1';
                    e.target.style.transform = 'translateX(0)';
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.05, rootMargin: '0px 80px 0px 80px' });
        document.querySelectorAll('[data-sound-chip]').forEach(el => io.observe(el));
    },
};

export const keyboardMethods = {
    _initKeyboard() {
        window.addEventListener('keydown', (e) => {
            const tag = e.target.tagName;
            if (tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT') return;
            if (e.code === 'Space') {
                e.preventDefault();
                if (this.activeCount() > 0) this.stopAll();
            }
            if (e.key === 'Escape') {
                this.showTimer     = false;
                this.showSleepPanel = false;
                this.showSaveMix   = false;
                this.sleepMode     = false;
            }
            if ((e.key === 's' || e.key === 'S') && !e.ctrlKey && !e.metaKey) {
                if (this.activeCount() > 0) this.openSaveMix();
            }
        });
    },
};

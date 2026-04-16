export const sleepState = () => ({
    showSleepPanel: false,
    sleepMode: false,
    sleepColor: '#000000',
    sleepPresets: ['#000000', '#1e293b', '#7c3aed', '#be185d', '#dc2626', '#16a34a', '#ffffff'],
});

export const sleepMethods = {
    enterSleepMode() {
        this.sleepMode = true;
        this.showSleepPanel = false;
        this.$nextTick(() => {
            document.getElementById('sleep-overlay')?.requestFullscreen?.().catch(() => {});
        });
    },

    exitSleepMode() {
        this.sleepMode = false;
        if (document.fullscreenElement) document.exitFullscreen?.().catch(() => {});
    },

    openSleepWindow() {
        const url = `${window.location.origin}/screen?c=${encodeURIComponent(this.sleepColor)}`;
        window.open(url, '_blank', 'width=900,height=600,menubar=no,toolbar=no,location=no');
    },
};

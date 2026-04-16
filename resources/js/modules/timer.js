export const timerState = () => ({
    showTimer: false,
    timerTab: 'timer',
    timerPreset: 60,
    timerRemaining: 3600,
    timerRunning: false,
    _timerInterval: null,
    timerCustomH: '',
    timerCustomM: '',
});

export const timerMethods = {
    setPreset(minutes) {
        if (this.timerRunning) return;
        this.timerPreset = minutes;
        this.timerRemaining = minutes * 60;
    },

    setCustomTimer() {
        if (this.timerRunning) return;
        const h = Math.max(0, parseInt(this.timerCustomH) || 0);
        const m = Math.max(0, parseInt(this.timerCustomM) || 0);
        const secs = h * 3600 + m * 60;
        if (secs > 0) {
            this.timerPreset = secs / 60;
            this.timerRemaining = secs;
            this.timerCustomH = '';
            this.timerCustomM = '';
        }
    },

    startTimer() {
        if (this.timerRunning) return;
        if (this.timerRemaining <= 0) this.timerRemaining = this.timerPreset * 60;
        this.timerRunning = true;
        this._timerInterval = setInterval(() => {
            if (this.timerRemaining <= 0) { this.stopTimer(true); return; }
            this.timerRemaining--;
        }, 1000);
    },

    pauseTimer() {
        this.timerRunning = false;
        clearInterval(this._timerInterval);
        this._timerInterval = null;
    },

    stopTimer(finished = false) {
        this.pauseTimer();
        this.timerRemaining = 0;
        if (finished) {
            this.stopAll();
            this._beep();
        }
    },

    resetTimer() {
        this.pauseTimer();
        this.timerRemaining = this.timerPreset * 60;
    },

    timerDisplay() {
        const r = this.timerRemaining;
        if (r >= 3600) {
            const h = Math.floor(r / 3600).toString().padStart(2, '0');
            const m = Math.floor((r % 3600) / 60).toString().padStart(2, '0');
            const s = (r % 60).toString().padStart(2, '0');
            return `${h}:${m}:${s}`;
        }
        const m = Math.floor(r / 60).toString().padStart(2, '0');
        const s = (r % 60).toString().padStart(2, '0');
        return `${m}:${s}`;
    },

    timerProgress() {
        const total = this.timerPreset * 60;
        return total === 0 ? 0 : ((total - this.timerRemaining) / total) * 100;
    },

    _beep() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            [0, 0.3, 0.6].forEach(delay => {
                const osc  = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain); gain.connect(ctx.destination);
                osc.frequency.value = 880; osc.type = 'sine';
                gain.gain.setValueAtTime(0.4, ctx.currentTime + delay);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + delay + 0.6);
                osc.start(ctx.currentTime + delay);
                osc.stop(ctx.currentTime + delay + 0.6);
            });
        } catch {}
    },
};

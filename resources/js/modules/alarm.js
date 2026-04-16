export const alarmState = () => ({
    alarmTime: '',
    alarmTimezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
    alarmActive: false,
    _alarmInterval: null,
    alarmTick: 0,
    _alarmTickInterval: null,
});

export const alarmMethods = {
    setAlarm() {
        if (!this.alarmTime) return;
        this.alarmActive = true;
        this._checkAlarm();
        this._alarmInterval     = setInterval(() => this._checkAlarm(), 15000);
        this._alarmTickInterval = setInterval(() => { this.alarmTick++; }, 60000);
    },

    cancelAlarm() {
        this.alarmActive = false;
        clearInterval(this._alarmInterval);
        this._alarmInterval = null;
        clearInterval(this._alarmTickInterval);
        this._alarmTickInterval = null;
    },

    _checkAlarm() {
        if (!this.alarmActive || !this.alarmTime) return;
        const nowStr = new Date().toLocaleTimeString('en-GB', {
            timeZone: this.alarmTimezone,
            hour: '2-digit', minute: '2-digit', hour12: false,
        }).substring(0, 5);
        if (nowStr === this.alarmTime) {
            this._beep();
            window.dispatchEvent(new CustomEvent('toast', { detail: { message: '⏰ Alarm! Wake up!', type: 'success' } }));
            this.cancelAlarm();
        }
    },

    alarmCountdown() {
        void this.alarmTick;
        if (!this.alarmActive || !this.alarmTime) return '';
        const [ah, am] = this.alarmTime.split(':').map(Number);
        const now     = new Date();
        const nowInTZ = new Date(now.toLocaleString('en-US', { timeZone: this.alarmTimezone }));
        const alarm   = new Date(nowInTZ);
        alarm.setHours(ah, am, 0, 0);
        let diff = alarm - nowInTZ;
        if (diff < 0) diff += 86400000;
        const hh = Math.floor(diff / 3600000);
        const mm = Math.floor((diff % 3600000) / 60000);
        return `${hh}h ${mm}m`;
    },
};

import { Howl } from 'howler';

export const audioState = () => ({
    activeSounds: {},
    volumes: {},
    mutedSlots: {},
});

export const audioMethods = {
    _startHowl(soundId, url, volume, loopStart = 0) {
        if (this.isPlaying(soundId)) return;
        if (volume !== undefined) this.volumes[soundId] = volume;
        if (!this.volumes[soundId]) this.volumes[soundId] = 70;
        const vol = this.volumes[soundId] / 100;
        const onErr = () => {
            window.dispatchEvent(new CustomEvent('toast', { detail: { message: '❌ Could not load audio', type: 'error' } }));
            delete window._howls[soundId];
            delete this.activeSounds[soundId];
            this.activeSounds = { ...this.activeSounds };
            const idx = this.slots.findIndex(s => s && s.id === soundId);
            if (idx !== -1) this.clearSlot(idx);
        };
        const howl = new Howl({
            src: [url],
            volume: vol,
            format: ['mp3'],
            onload: loopStart > 0 ? function () {
                const totalMs = Math.floor(this.duration() * 1000);
                const startMs = Math.floor(loopStart * 1000);
                this._sprite = {
                    _intro: [0, startMs],
                    _main:  [startMs, totalMs - startMs, true],
                };
                const iid = this.play('_intro');
                this.once('end', () => this.play('_main'), iid);
            } : function () { this.play(); },
            onloaderror: onErr,
        });
        window._howls[soundId] = howl;
        this.activeSounds[soundId] = true;
    },

    _stopHowl(soundId) {
        const howl = window._howls[soundId];
        if (howl) {
            howl.fade(howl.volume(), 0, 300);
            setTimeout(() => { howl.stop(); howl.unload(); }, 350);
            delete window._howls[soundId];
        }
        delete this.activeSounds[soundId];
    },

    toggle(soundId, url) {
        this.isPlaying(soundId) ? this._stopHowl(soundId) : this._startHowl(soundId, url);
    },

    setVolume(soundId, val) {
        this.volumes[soundId] = parseInt(val);
        this.volumes = { ...this.volumes };
        if (window._howls[soundId]) window._howls[soundId].volume(val / 100);
        this._saveStage();
    },

    isPlaying(soundId) { return !!this.activeSounds[soundId]; },

    activeCount() { return this.slots.filter(s => s !== null).length; },

    stopAll() {
        Object.keys(this.activeSounds).forEach(id => this._stopHowl(parseInt(id)));
        this.activeSounds = {};
        this.mutedSlots  = {};
        this.slots = [null];
        try { localStorage.removeItem('faroise_stage'); } catch (e) {}
    },

    toggleMute(soundId) {
        const h = window._howls[soundId];
        if (this.isMuted(soundId)) {
            delete this.mutedSlots[soundId];
            this.mutedSlots = { ...this.mutedSlots };
            if (h) h.volume((this.volumes[soundId] ?? 70) / 100);
        } else {
            this.mutedSlots[soundId] = true;
            this.mutedSlots = { ...this.mutedSlots };
            if (h) h.volume(0);
        }
    },

    isMuted(soundId) { return !!this.mutedSlots[soundId]; },
};

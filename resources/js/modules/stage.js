export const stageState = () => ({
    slots: [null],
    draggingOver: null,
});

export const stageMethods = {
    isInSlot(soundId) {
        return this.slots.some(s => s && s.id === soundId);
    },

    dropToSlot(index, sound) {
        const existingIdx = this.slots.findIndex(s => s && s.id === sound.id);
        if (existingIdx !== -1 && existingIdx !== index) this.slots[existingIdx] = null;
        const current = this.slots[index];
        if (current && current.id !== sound.id) this._stopHowl(current.id);
        this.slots[index] = sound;
        if (!this.slots.includes(null)) this.slots.push(null);
        this.slots = [...this.slots];
        this._startHowl(sound.id, sound.url, undefined, sound.loopStart ?? 0);
        this._saveStage();
    },

    clearSlot(index) {
        const slot = this.slots[index];
        if (!slot) return;
        this._stopHowl(slot.id);
        delete this.mutedSlots[slot.id];
        this.mutedSlots = { ...this.mutedSlots };
        const filledCount = this.slots.filter(s => s !== null).length;
        if (filledCount > 0) {
            this.slots.splice(index, 1);
        } else {
            this.slots[index] = null;
        }
        if (!this.slots.includes(null)) this.slots.push(null);
        this.slots = [...this.slots];
        this._saveStage();
    },

    addToFirstSlot(sound) {
        if (this.isInSlot(sound.id)) return;
        const idx = this.slots.findIndex(s => s === null);
        if (idx !== -1) this.dropToSlot(idx, sound);
        else { this.slots.push(null); this.dropToSlot(this.slots.length - 1, sound); }
    },

    _saveStage() {
        try {
            const data = { slots: this.slots.filter(s => s !== null), volumes: this.volumes };
            localStorage.setItem('faroise_stage', JSON.stringify(data));
        } catch (e) {}
    },

    _restoreStage() {
        try {
            const raw = localStorage.getItem('faroise_stage');
            if (!raw) return;
            const { slots, volumes } = JSON.parse(raw);
            if (!slots?.length) return;
            this.volumes = { ...this.volumes, ...volumes };
            slots.forEach(s => this.addToFirstSlot(s));
            window.dispatchEvent(new CustomEvent('toast', { detail: { message: '↺ Restored last session', type: 'info' } }));
        } catch (e) {}
    },
};

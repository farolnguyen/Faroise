export const shareMethods = {
    _parseShareURL() {
        const s = new URLSearchParams(window.location.search).get('s');
        if (!s) return false;
        let loaded = 0;
        s.split(',').forEach(pair => {
            const [id, vol] = pair.split(':').map(Number);
            const meta = window.soundMeta?.[id];
            if (!meta) return;
            if (vol) this.volumes[id] = vol;
            this.addToFirstSlot({ id, url: meta.url, name: meta.name, icon: meta.icon, color: meta.color, loopStart: meta.loopStart ?? 0 });
            loaded++;
        });
        return loaded > 0;
    },

    copyShareLink() {
        const pairs = this.slots
            .filter(s => s !== null)
            .map(s => `${s.id}:${this.volumes[s.id] ?? 70}`)
            .join(',');
        const url = `${window.location.origin}/?s=${pairs}`;
        navigator.clipboard.writeText(url).then(() => {
            window.dispatchEvent(new CustomEvent('toast', { detail: { message: '🔗 Link copied!', type: 'success' } }));
        }).catch(() => {
            window.dispatchEvent(new CustomEvent('toast', { detail: { message: url, type: 'info' } }));
        });
    },

    stageGlow() {
        const colors = this.slots.filter(s => s !== null).map(s => s.color);
        if (!colors.length) return 'background: radial-gradient(ellipse, rgba(6,182,212,0.04) 0%, transparent 70%)';
        const hex = colors[0];
        const r = parseInt(hex.slice(1, 3), 16);
        const g = parseInt(hex.slice(3, 5), 16);
        const b = parseInt(hex.slice(5, 7), 16);
        return `background: radial-gradient(ellipse, rgba(${r},${g},${b},0.08) 0%, transparent 70%)`;
    },
};

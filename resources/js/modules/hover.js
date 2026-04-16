import { Howl } from 'howler';

export const hoverState = () => ({
    _hoverTimer: null,
    _previewHowl: null,
});

export const hoverMethods = {
    startHoverPreview(url) {
        this.cancelHoverPreview();
        this._hoverTimer = setTimeout(() => {
            if (this._previewHowl) { this._previewHowl.unload(); this._previewHowl = null; }
            const h = new Howl({
                src: [url], volume: 0.45, format: ['mp3'], html5: true,
                onloaderror: () => { this._previewHowl = null; },
            });
            h.play();
            this._previewHowl = h;
            setTimeout(() => {
                if (this._previewHowl === h) {
                    h.fade(0.45, 0, 400);
                    setTimeout(() => { if (this._previewHowl === h) { h.unload(); this._previewHowl = null; } }, 450);
                }
            }, 3000);
        }, 500);
    },

    cancelHoverPreview() {
        clearTimeout(this._hoverTimer);
        this._hoverTimer = null;
        if (this._previewHowl) {
            const h = this._previewHowl;
            h.fade(h.volume(), 0, 200);
            setTimeout(() => { h.unload(); }, 250);
            this._previewHowl = null;
        }
    },
};

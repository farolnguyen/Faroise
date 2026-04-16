import './bootstrap';
import Alpine from 'alpinejs';

import { toastManager }                    from './modules/toast.js';
import { audioState, audioMethods }        from './modules/audio.js';
import { hoverState, hoverMethods }        from './modules/hover.js';
import { stageState, stageMethods }        from './modules/stage.js';
import { shareMethods }                    from './modules/share.js';
import { filterState, filterMethods }      from './modules/filters.js';
import { timerState, timerMethods }        from './modules/timer.js';
import { alarmState, alarmMethods }        from './modules/alarm.js';
import { sleepState, sleepMethods }        from './modules/sleep.js';
import { mixSaveState, mixSaveMethods }    from './modules/mix-save.js';
import { keyboardMethods }                 from './modules/keyboard.js';
import { lazyMethods }                     from './modules/lazy.js';

window.Alpine  = Alpine;
window._howls  = {};

window.toastManager = toastManager;

window.soundPlayer = function () {
    return {
        // ─── State (each factory call returns fresh objects/arrays) ─
        ...audioState(),
        ...hoverState(),
        ...stageState(),
        ...filterState(),
        ...timerState(),
        ...alarmState(),
        ...sleepState(),
        ...mixSaveState(),

        // ─── Methods ───────────────────────────────────────────────
        ...audioMethods,
        ...hoverMethods,
        ...stageMethods,
        ...shareMethods,
        ...filterMethods,
        ...timerMethods,
        ...alarmMethods,
        ...sleepMethods,
        ...mixSaveMethods,
        ...keyboardMethods,
        ...lazyMethods,

        // ─── Init ──────────────────────────────────────────────────
        init() {
            this._initKeyboard();
            this.$nextTick(() => {
                this._initLazyChips();
                const restored = this._parseShareURL();
                if (!restored && this.slots.filter(s => s !== null).length === 0) {
                    this._restoreStage();
                }
            });
            this.$watch('activeCategory', () => this._resetChipVisibility());
            this.$watch('activeTags',     () => this._resetChipVisibility());
            this.$watch('soundSearch',    () => this._resetChipVisibility());
        },
    };
};

Alpine.start();

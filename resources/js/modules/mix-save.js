import Sortable from 'sortablejs';

export const mixSaveState = () => ({
    sortedSounds: [],
    showSaveMix: false,
    mixName: '',
    mixDesc: '',
    mixPublic: false,
    saving: false,
});

export const mixSaveMethods = {
    openSaveMix() {
        this.sortedSounds = this.slots
            .filter(s => s !== null)
            .map(s => ({ id: s.id, name: s.name, icon: s.icon, volume: this.volumes[s.id] ?? 70 }));
        this.showSaveMix = true;
        setTimeout(() => {
            const el = document.getElementById('sort-sounds-list');
            if (el) {
                Sortable.create(el, {
                    animation: 150, handle: '.drag-handle', ghostClass: 'opacity-40',
                    onEnd: (evt) => {
                        const moved = this.sortedSounds.splice(evt.oldIndex, 1)[0];
                        this.sortedSounds.splice(evt.newIndex, 0, moved);
                    },
                });
            }
        }, 50);
    },

    async saveMix() {
        if (!this.mixName.trim()) return;
        this.saving = true;
        const sounds = this.sortedSounds.length
            ? this.sortedSounds.map(s => ({ id: s.id, volume: s.volume }))
            : this.slots.filter(s => s !== null).map(s => ({ id: s.id, volume: this.volumes[s.id] ?? 70 }));
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        try {
            const res  = await fetch('/mixes', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({ name: this.mixName, description: this.mixDesc, is_public: this.mixPublic, sounds }),
            });
            const json = await res.json();
            this.mixName = ''; this.mixDesc = ''; this.mixPublic = false;
            this.sortedSounds = []; this.showSaveMix = false;
            window.dispatchEvent(new CustomEvent('toast', { detail: { message: json.message, type: 'success' } }));
        } catch {
            window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Có lỗi xảy ra, thử lại nhé.', type: 'error' } }));
        } finally {
            this.saving = false;
        }
    },
};

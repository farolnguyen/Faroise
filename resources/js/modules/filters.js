export const filterState = () => ({
    activeCategory: null,
    activeTags: [],
    soundSearch: '',
});

export const filterMethods = {
    soundFilter(soundId, categoryId, tagIds) {
        const meta = window.soundMeta?.[soundId];
        const name = meta?.name ?? '';
        const searchMatch   = !this.soundSearch || name.toLowerCase().includes(this.soundSearch.toLowerCase());
        const categoryMatch = this.activeCategory === null || categoryId === this.activeCategory;
        const tagMatch      = this.activeTags.length === 0 || this.activeTags.some(t => tagIds.includes(t));
        return searchMatch && categoryMatch && tagMatch;
    },

    toggleTag(tagId) {
        const idx = this.activeTags.indexOf(tagId);
        if (idx === -1) this.activeTags.push(tagId);
        else this.activeTags.splice(idx, 1);
        this.activeTags = [...this.activeTags];
    },
};

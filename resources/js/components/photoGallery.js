// Photo gallery with filter and lightbox (project plan 6.3).
export default (photos) => ({
    photos,
    filter: 'all',
    open: false,
    index: 0,

    shows(category) {
        return this.filter === 'all' || this.filter === category;
    },

    get visible() {
        return this.photos
            .map((photo, i) => i)
            .filter((i) => this.shows(this.photos[i].category));
    },

    openPhoto(i) {
        this.index = i;
        this.open = true;
        document.body.classList.add('overflow-hidden');
    },

    close() {
        this.open = false;
        document.body.classList.remove('overflow-hidden');
    },

    next() {
        const list = this.visible;
        this.index = list[(list.indexOf(this.index) + 1) % list.length];
    },

    previous() {
        const list = this.visible;
        this.index = list[(list.indexOf(this.index) - 1 + list.length) % list.length];
    },
});

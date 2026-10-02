// Fotogalerij met filter en lichtbak (PVA 6.3).
export default (fotos) => ({
    fotos,
    filter: 'alle',
    open: false,
    index: 0,

    toon(categorie) {
        return this.filter === 'alle' || this.filter === categorie;
    },

    get zichtbaar() {
        return this.fotos
            .map((foto, i) => i)
            .filter((i) => this.toon(this.fotos[i].categorie));
    },

    openFoto(i) {
        this.index = i;
        this.open = true;
        document.body.classList.add('overflow-hidden');
    },

    sluit() {
        this.open = false;
        document.body.classList.remove('overflow-hidden');
    },

    volgende() {
        const lijst = this.zichtbaar;
        this.index = lijst[(lijst.indexOf(this.index) + 1) % lijst.length];
    },

    vorige() {
        const lijst = this.zichtbaar;
        this.index = lijst[(lijst.indexOf(this.index) - 1 + lijst.length) % lijst.length];
    },
});

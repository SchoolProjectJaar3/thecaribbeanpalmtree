import { berekenPrijs, euro, datumTekst } from '../prijs';

// Datumkeuze in de kalender (PVA 6.4): alleen vrije dagen zijn te kiezen,
// bezette dagen tussen aankomst en vertrek zijn niet toegestaan.
export default (tarieven) => ({
    aankomst: null,
    vertrek: null,
    melding: '',
    euro,
    datumTekst,

    kies(iso) {
        this.melding = '';

        if (!this.aankomst || this.vertrek || iso <= this.aankomst) {
            this.aankomst = iso;
            this.vertrek = null;
            return;
        }

        if (this.bezetTussen(this.aankomst, iso)) {
            this.melding = 'Er zitten bezette dagen tussen deze data. Kies een andere vertrekdatum.';
            return;
        }

        this.vertrek = iso;
    },

    bezetTussen(van, tot) {
        return [...document.querySelectorAll('[data-date][data-status="bezet"]')]
            .some((cel) => cel.dataset.date > van && cel.dataset.date < tot);
    },

    sel(iso) {
        if (iso === this.aankomst) return 'start';
        if (iso === this.vertrek) return 'eind';
        if (this.aankomst && this.vertrek && iso > this.aankomst && iso < this.vertrek) return 'mid';
        return '';
    },

    get prijs() {
        return berekenPrijs(tarieven, this.aankomst, this.vertrek);
    },

    get compleet() {
        return Boolean(this.prijs && this.prijs.voldoetAanMinimum);
    },

    get reserveerUrl() {
        if (!this.aankomst || !this.vertrek) return '/reserveren';
        return `/reserveren?aankomst=${this.aankomst}&vertrek=${this.vertrek}`;
    },

    reset() {
        this.aankomst = null;
        this.vertrek = null;
        this.melding = '';
    },
});

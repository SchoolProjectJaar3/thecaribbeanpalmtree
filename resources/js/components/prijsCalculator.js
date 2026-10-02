import { berekenPrijs, euro } from '../prijs';

// Prijsindicatie op de pagina "Tarieven" en het reserveringsformulier.
export default (tarieven, begin = {}) => ({
    aankomst: begin.aankomst || '',
    vertrek: begin.vertrek || '',
    personen: begin.personen || 2,
    euro,

    get resultaat() {
        return berekenPrijs(tarieven, this.aankomst, this.vertrek, Number(this.personen));
    },

    get ongeldig() {
        return Boolean(this.aankomst && this.vertrek && !this.resultaat);
    },
});

import { calculatePrice, euro } from '../pricing';

// Price estimate on the rates page and the booking form.
export default (rates, initial = {}) => ({
    arrival: initial.arrival || '',
    departure: initial.departure || '',
    guests: initial.guests || 2,
    euro,

    get result() {
        return calculatePrice(rates, this.arrival, this.departure, Number(this.guests));
    },

    get invalid() {
        return Boolean(this.arrival && this.departure && !this.result);
    },
});

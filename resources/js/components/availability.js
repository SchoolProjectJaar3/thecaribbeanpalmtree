import { calculatePrice, euro, formatDate } from '../pricing';

// Date selection in the calendar (project plan 6.4): only available days can
// be selected, booked days between arrival and departure are not allowed.
export default (rates) => ({
    arrival: null,
    departure: null,
    message: '',
    euro,
    formatDate,

    select(iso) {
        this.message = '';

        if (!this.arrival || this.departure || iso <= this.arrival) {
            this.arrival = iso;
            this.departure = null;
            return;
        }

        if (this.bookedBetween(this.arrival, iso)) {
            this.message = 'Er zitten bezette dagen tussen deze data. Kies een andere vertrekdatum.';
            return;
        }

        this.departure = iso;
    },

    bookedBetween(from, to) {
        return [...document.querySelectorAll('[data-date][data-status="booked"]')]
            .some((cell) => cell.dataset.date > from && cell.dataset.date < to);
    },

    selection(iso) {
        if (iso === this.arrival) return 'start';
        if (iso === this.departure) return 'end';
        if (this.arrival && this.departure && iso > this.arrival && iso < this.departure) return 'mid';
        return '';
    },

    get price() {
        return calculatePrice(rates, this.arrival, this.departure);
    },

    get complete() {
        return Boolean(this.price && this.price.meetsMinimum);
    },

    get bookingUrl() {
        if (!this.arrival || !this.departure) return '/book';
        return `/book?arrival=${this.arrival}&departure=${this.departure}`;
    },

    reset() {
        this.arrival = null;
        this.departure = null;
        this.message = '';
    },
});

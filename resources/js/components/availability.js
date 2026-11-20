import { euro, formatDate, quoteLoader } from '../pricing';

// Date selection in the calendar (project plan 6.4): only available days can
// be selected, booked days between arrival and departure are not allowed.
export default (url) => ({
    arrival: null,
    departure: null,
    message: '',
    price: null,
    euro,
    formatDate,

    init() {
        const load = quoteLoader(url);
        const update = () => {
            load(this.arrival, this.departure, 2)
                .then((quote) => { this.price = quote; })
                .catch(() => {});
        };

        this.$watch('arrival', update);
        this.$watch('departure', update);
    },

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

    get complete() {
        return Boolean(this.price && this.price.complete && this.price.meets_minimum);
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

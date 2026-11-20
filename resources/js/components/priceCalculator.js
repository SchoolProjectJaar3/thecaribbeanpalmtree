import { euro, quoteLoader } from '../pricing';

// Price estimate on the rates page and the booking form.
export default (url, initial = {}) => ({
    arrival: initial.arrival || '',
    departure: initial.departure || '',
    guests: initial.guests || 2,
    result: null,
    euro,

    init() {
        const load = quoteLoader(url);
        const update = () => {
            load(this.arrival, this.departure, Number(this.guests))
                .then((quote) => { this.result = quote; })
                .catch(() => {});
        };

        this.$watch('arrival', update);
        this.$watch('departure', update);
        this.$watch('guests', update);
        update();
    },

    get invalid() {
        return Boolean(this.arrival && this.departure && this.departure <= this.arrival);
    },
});

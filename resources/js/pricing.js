// Price estimate based on the rates (project plan 6.5). The rates come from
// config/site.php and are passed in from the Blade page.

const DAY = 24 * 60 * 60 * 1000;

function toDate(iso) {
    const [y, m, d] = iso.split('-').map(Number);
    return new Date(Date.UTC(y, m - 1, d));
}

export function isHighSeason(rates, date) {
    return rates.high_season_months.includes(date.getUTCMonth() + 1);
}

export function calculatePrice(rates, arrival, departure, guests = 2) {
    if (!arrival || !departure) return null;

    const from = toDate(arrival);
    const to = toDate(departure);
    const nights = Math.round((to - from) / DAY);
    if (nights < 1) return null;

    let stay = 0;
    for (let i = 0; i < nights; i++) {
        const night = new Date(from.getTime() + i * DAY);
        stay += isHighSeason(rates, night) ? rates.price_high : rates.price_low;
    }

    const discountRule = [...rates.discounts]
        .sort((a, b) => b.from - a.from)
        .find((d) => nights >= d.from);
    const discountPercent = discountRule ? discountRule.percent : 0;
    const discount = (stay * discountPercent) / 100;
    const touristTax = rates.tourist_tax * guests * nights;
    const minNights = isHighSeason(rates, from) ? rates.min_nights_high : rates.min_nights_low;

    return {
        nights,
        stay,
        discountPercent,
        discount,
        cleaning: rates.cleaning,
        touristTax,
        total: stay - discount + rates.cleaning + touristTax,
        deposit: rates.deposit,
        minNights,
        meetsMinimum: nights >= minNights,
    };
}

export function euro(amount) {
    return new Intl.NumberFormat('nl-NL', { style: 'currency', currency: 'EUR' }).format(amount);
}

export function formatDate(iso) {
    return toDate(iso).toLocaleDateString('nl-NL', {
        weekday: 'short', day: 'numeric', month: 'long', year: 'numeric', timeZone: 'UTC',
    });
}

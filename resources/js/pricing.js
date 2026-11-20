// Price estimate (project plan 6.5). The calculation is done on the server by
// App\Services\PriceCalculator, with the rates from the database.

function toDate(iso) {
    const [y, m, d] = iso.split('-').map(Number);
    return new Date(Date.UTC(y, m - 1, d));
}

export async function fetchQuote(url, arrival, departure, guests = 2, signal) {
    const params = new URLSearchParams({ arrival, departure, guests });
    const response = await fetch(`${url}?${params}`, {
        headers: { Accept: 'application/json' },
        signal,
    });

    if (!response.ok) return null;

    return (await response.json()).quote;
}

// Keeps only the latest request, so an older answer cannot overwrite a newer one.
export function quoteLoader(url) {
    let controller = null;

    return async (arrival, departure, guests) => {
        controller?.abort();

        if (!arrival || !departure || departure <= arrival) return null;

        controller = new AbortController();

        try {
            return await fetchQuote(url, arrival, departure, guests, controller.signal);
        } catch (error) {
            if (error.name === 'AbortError') throw error;
            return null;
        }
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

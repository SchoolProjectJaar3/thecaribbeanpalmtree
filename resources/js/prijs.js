// Prijsindicatie op basis van de tarieven (PVA 6.5). De tarieven komen uit
// config/woning.php en worden vanuit de Blade-pagina doorgegeven.

const DAG = 24 * 60 * 60 * 1000;

function naarDatum(iso) {
    const [j, m, d] = iso.split('-').map(Number);
    return new Date(Date.UTC(j, m - 1, d));
}

export function isHoogseizoen(tarieven, datum) {
    return tarieven.hoog_maanden.includes(datum.getUTCMonth() + 1);
}

export function berekenPrijs(tarieven, aankomst, vertrek, personen = 2) {
    if (!aankomst || !vertrek) return null;

    const van = naarDatum(aankomst);
    const tot = naarDatum(vertrek);
    const nachten = Math.round((tot - van) / DAG);
    if (nachten < 1) return null;

    let verblijf = 0;
    for (let i = 0; i < nachten; i++) {
        const nacht = new Date(van.getTime() + i * DAG);
        verblijf += isHoogseizoen(tarieven, nacht) ? tarieven.prijs_hoog : tarieven.prijs_laag;
    }

    const kortingsregel = [...tarieven.kortingen]
        .sort((a, b) => b.vanaf - a.vanaf)
        .find((k) => nachten >= k.vanaf);
    const kortingProcent = kortingsregel ? kortingsregel.procent : 0;
    const korting = (verblijf * kortingProcent) / 100;
    const belasting = tarieven.toeristenbelasting * personen * nachten;
    const minNachten = isHoogseizoen(tarieven, van) ? tarieven.min_nachten_hoog : tarieven.min_nachten_laag;

    return {
        nachten,
        verblijf,
        kortingProcent,
        korting,
        schoonmaak: tarieven.schoonmaak,
        belasting,
        totaal: verblijf - korting + tarieven.schoonmaak + belasting,
        borg: tarieven.borg,
        minNachten,
        voldoetAanMinimum: nachten >= minNachten,
    };
}

export function euro(bedrag) {
    return new Intl.NumberFormat('nl-NL', { style: 'currency', currency: 'EUR' }).format(bedrag);
}

export function datumTekst(iso) {
    return naarDatum(iso).toLocaleDateString('nl-NL', {
        weekday: 'short', day: 'numeric', month: 'long', year: 'numeric', timeZone: 'UTC',
    });
}

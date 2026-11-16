// resources/js/app.ts
// Live search: as you type or change a filter, ask the API and redraw the list.
// Vite turns this file into plain JavaScript, and the browser runs that.

// An interface describes the shape of an object: which fields it has and
// their types. This one matches the JSON that /api/athletes sends. If you
// misspell a field or use it as the wrong type, tsc reports an error.
interface Athlete {
    id: number;
    name: string;
    sport: string;
    position: string;
    grad_year: number;
    state: string;
    gpa: number;
}

// Find the elements on the page by their id (the # means "id").
// <HTMLFormElement> tells TypeScript what kind of element to expect.
// Each one may be null (for example, on a profile page that has no list),
// which is why the code below checks before using them.
const form = document.querySelector<HTMLFormElement>('#filters');
const list = document.querySelector<HTMLUListElement>('#athlete-list');
const count = document.querySelector<HTMLParagraphElement>('#result-count');

// Rebuild the list on the page from an array of athletes.
// ": void" means this function doesn't return anything.
function renderAthletes(athletes: Athlete[]): void {
    // Stop here if the page doesn't have a list or a count
    if (!list || !count) return;

    // Backticks make a template string: ${...} inserts a value
    count.textContent = `${athletes.length} athletes`;

    // replaceChildren removes the old <li> items and puts in the new ones.
    // athletes.map(...) turns each athlete into an <li> element, and
    // "..." spreads that array out into separate arguments.
    list.replaceChildren(
        ...athletes.map((athlete) => {
            // Build the same HTML that index.blade.php makes on the server
            const item = document.createElement('li');
            item.className = 'py-2';

            const link = document.createElement('a');
            link.href = `/athletes/${athlete.id}`;
            link.className = 'font-semibold';
            // textContent (not innerHTML) inserts plain text, so a name
            // containing HTML can't run as code
            link.textContent = athlete.name;

            const meta = document.createElement('span');
            meta.className = 'text-sm text-gray-600';
            meta.textContent = ` ${athlete.sport} · ${athlete.position} · Class of ${athlete.grad_year} · ${athlete.state}`;

            // Put the link and the details inside the <li>
            item.append(link, meta);
            return item;
        }),
    );
}

// Ask the API for athletes that match the form, then redraw the list.
// "async" lets us use "await" to wait for the network without freezing the page.
async function search(): Promise<void> {
    if (!form) return;

    // Turn the form's fields into a query string like "q=maya&sport=Soccer",
    // leaving out empty fields
    const params = new URLSearchParams();
    for (const [key, value] of new FormData(form)) {
        if (typeof value === 'string' && value !== '') params.set(key, value);
    }

    // Call the JSON endpoint from routes/web.php and wait for the answer
    const response = await fetch(`/api/athletes?${params}`);
    // If the server answered with an error (like 500), keep the current list
    if (!response.ok) return;

    // Read the JSON body. The type annotation says we expect an array of Athlete.
    const athletes: Athlete[] = await response.json();
    renderAthletes(athletes);

    // Update the address bar to match the filters, without reloading the page,
    // so refreshing or bookmarking keeps the same results
    history.replaceState(null, '', `?${params}`);
}

// Debounce: wait until the user pauses typing before searching, instead of
// sending a request for every single keystroke.
let timer: number | undefined;

// "input" fires whenever any field in the form changes.
// The ?. means "only if form isn't null".
form?.addEventListener('input', () => {
    // Cancel the search scheduled by the previous keystroke...
    window.clearTimeout(timer);
    // ...and schedule a new one 250 milliseconds from now
    timer = window.setTimeout(search, 250);
});

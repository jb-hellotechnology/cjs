// ─── Config ──────────────────────────────────────────────
const CONCURRENCY = 4; // Max simultaneous requests — tune to taste

// ─── Helpers ─────────────────────────────────────────────

/**
 * Parses a `data-page-source` value like:
 *   "https://example.com/post article.h-entry.flow"
 * into { url, selector }
 */
function parseSource(src) {
  const spaceIndex = src.indexOf(' ');
  if (spaceIndex === -1) return { url: src, selector: null };
  return {
	url: src.slice(0, spaceIndex),
	selector: src.slice(spaceIndex + 1).trim()
  };
}

/**
 * Fetches a URL, optionally filters to a CSS selector,
 * injects HTML into `targetEl`, then recursively loads
 * any nested `.import` elements found inside.
 */
async function loadImport(el, queue) {
  const rawSrc = el.dataset.pageSource;
  if (!rawSrc) return;

  const target = el.querySelector(':scope > div');
  if (!target) return;

  const { url, selector } = parseSource(rawSrc);

  try {
	const response = await queue(() => fetch(url));
	if (!response.ok) throw new Error(`HTTP ${response.status} for ${url}`);

	const html = await response.text();
	const doc = new DOMParser().parseFromString(html, 'text/html');

	const content = selector ? doc.querySelector(selector) : doc.body;
	if (!content) {
	  console.warn(`Selector "${selector}" not found in ${url}`);
	  return;
	}

	target.innerHTML = content.innerHTML;

	// Recursively load any imports revealed by this content — in parallel
	const nested = [...target.querySelectorAll('.import')];
	if (nested.length) {
	  await Promise.all(nested.map(nestedEl => loadImport(nestedEl, queue)));
	}

  } catch (err) {
	console.error(`Failed to load import from ${url}:`, err);
	el.classList.add('import-error'); // lets you style failed imports
  }
}

/**
 * Creates a concurrency-limited queue.
 * Wraps a fetch call so only `limit` requests run at once.
 */
function createQueue(limit) {
  let active = 0;
  const pending = [];

  function run(fn, resolve, reject) {
	active++;
	fn().then(resolve, reject).finally(() => {
	  active--;
	  if (pending.length) {
		const next = pending.shift();
		run(next.fn, next.resolve, next.reject);
	  }
	});
  }

  return function queue(fn) {
	return new Promise((resolve, reject) => {
	  if (active < limit) {
		run(fn, resolve, reject);
	  } else {
		pending.push({ fn, resolve, reject });
	  }
	});
  };
}

// ─── Initialisation ───────────────────────────────────────

/**
 * The footer loads this file with `defer`, which already waits for the
 * document. Run against whatever state we find anyway: an `async` tag, or an
 * injected one, can execute after DOMContentLoaded has been and gone, and a
 * listener added then never fires — which left every import empty.
 */
function init() {
  const queue = createQueue(CONCURRENCY);

  // Load all top-level imports — limited concurrency, nested loads handled recursively
  const topLevelImports = [...document.querySelectorAll('.import')]
	.filter(el => !el.closest('.import > div')); // exclude already-nested ones

  Promise.all(topLevelImports.map(el => loadImport(el, queue)))
	.then(() => console.log('All imports loaded'));

  // Article imports — unchanged, but could also be queued if needed
  document.querySelectorAll('.import-articles').forEach(el => {
	const pTag = el.dataset.tag;
	const pCount = el.dataset.count;

	fetch('/explore-more', {
	  method: 'POST',
	  headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
	  body: new URLSearchParams({ tag: pTag, count: pCount })
	})
	  .then(r => r.text())
	  .then(data => { el.innerHTML = data; })
	  .catch(err => console.error('import-articles failed:', err));
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', init);
} else {
  init();
}
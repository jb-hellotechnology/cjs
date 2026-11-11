# cjs_feed_keys

Access keys for the XML job feeds.

The feeds carry the whole product — the advert copy employers pay to place, in
a form built for machines to read — and they were open to anyone who knew the
address. Nothing on the site links to them, so the people reading them are
partners, aggregators and whoever found them. This gives each consumer a key of
their own, so a feed can be attributed and turned off one subscriber at a time
instead of all at once.

## It never locks anyone out

There are two tiers, not two states:

| | full | summary |
| --- | --- | --- |
| job title, employer, location, closing date, link | yes | yes |
| the advert text | yes | **no** |

A **valid key** gets `full`. **No key** gets `summary` — the feed still works,
the items are still there, they just carry a line of detail and a link back
instead of the advert body. Only a key that is **present and wrong** is an
error, and that gets a 401: that is someone whose access was withdrawn, or a
typo worth telling them about rather than silently downgrading.

Until `CJS_Feed_Keys::ENFORCE_FROM` (currently **15 December 2026**) a request
with no key still gets `full`, with a notice element in the XML and a `Warning`
header naming the date. So nothing breaks on the day this goes live; existing
readers get a month of being told, then quietly get less.

Moving that date is the whole rollout control. Bring it forward to start
enforcing sooner, push it back to extend the grace period.

## Giving someone a key

Control panel → **Feed Keys**. Enter who it is for, press Create, send them the
address with `?key=…` on the end. The table shows last used, which feed and how
many fetches, so you can see which keys are live and who has stopped reading.

Revoke takes effect on the next request. Revoked is better than deleted —
deleting frees the key string and loses the usage history.

A key also works as `Authorization: Bearer …` or `X-Api-Key`, for anything
scripted. The query string is what feed readers and aggregators can actually
manage, so that is what to hand out.

Keys are not scoped per feed: one key opens all of them. Every consumer so far
reads one feed, and scoping would be a setting nobody changes.

## What is keyed

| Address | |
| --- | --- |
| `/jobs-feed-items` | all jobs, full text |
| `/jobs-xml-feed-aggregator` | all jobs, aggregator format |
| `/feed/sector/[sector]` | jobs in one sector |
| `/feed/region/[region]` | jobs in one region |
| `/feeds/jobs.php` | the old CJS Daily Online RSS address |
| `/RSS/jobs.xml` | → `/feeds/jobs_2.php`, the aggregator feed |

`/volunteers-xml-feed` is **not** keyed: it carries no advert text, so there is
nothing to protect. The news and training feeds are untouched.

### The last two matter

`/feeds/jobs.php` and `/feeds/jobs_2.php` are real files on disk, so Runway's
rewrite never sees them and a guard in a page template does not apply. They
used to fetch the Runway URL over HTTP and echo the result, which meant they
would have served the keyed feed to anyone who asked for the old address — the
fetch came from the server and carried no key. They render in process now,
through the same guard and the same templates.

That rewrite also fixed `/feeds/jobs.php` emitting invalid XML: the document it
fetched brought its own declaration and its own `<rss>` root, nested inside the
`<channel>` the file printed around it. No parser would read it.

## Adding the guard to another feed

Two lines in the page template, the first before any output:

```php
<?php
    $feed = function_exists('cjs_feed_guard')
                ? cjs_feed_guard('my-feed-id')
                : ['tier' => 'full', 'notice' => false, 'key' => false];
    header('Content-Type: application/xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8" ?>';
?>
```

then pass `$feed['tier']` to whatever renders the items, and drop
`cjs_feed_notice($feed)` inside the `<channel>`. The `function_exists` guard is
what makes the app removable: without it the feed behaves exactly as it did
before keys existed.

An item renderer picks its template through `CJS_Daily_Jobs::feed_template()`,
which appends `_summary` for the summary tier — so a new feed needs a
`..._summary.html` beside its full template.

## Deploying

Four things, and **three of them are not in the repository**:

1. `perch/addons/apps/cjs_feed_keys/` and the two new
   `perch/templates/cjs_daily/*_summary.html` templates — these deploy normally.
2. Add `'cjs_feed_keys'` to `$apps_list` in **`perch/config/apps.php`**. Not in
   the repo (`perch/config` is excluded), so add it by hand on each server.
3. Upload **`feeds/jobs.php`** and **`feeds/jobs_2.php`**. The whole `/feeds`
   folder is gitignored. Until these two are uploaded the old addresses keep
   serving the full feed to anyone — the hole this app exists to close.
4. Upload **`robots.txt`**. Also gitignored. It now names the feed paths, which
   the previous version said it would do "at the same time as they get an
   access key, not before".

The table is created on first visit to the Feed Keys admin page, so there is no
install step. Until the app is registered, `cjs_feed_guard()` does not exist and
every feed serves full text as before — nothing breaks either way.

## Checking it

With no key, during the grace period:

```bash
curl -sD- https://www.countryside-jobs.com/jobs-feed-items | head -20
```

Expect `200`, a `Warning:` header and a `<cjsFeed:notice>` element. With a bad
key, expect `401` and a `WWW-Authenticate` header. With a real key, neither the
header nor the notice, and `<description>` carrying the advert.

Rejected keys are written to the PHP error log, prefixed `[cjs_feed_keys]`.

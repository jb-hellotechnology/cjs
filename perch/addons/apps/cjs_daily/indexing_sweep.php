<?php
/**
 * The daily indexing sweep. Run from cron, once in the early morning:
 *
 *   /usr/local/bin/php /home/cjsjobs/public_html/perch/addons/apps/cjs_daily/indexing_sweep.php
 *
 * Saving an advert in the admin notifies the search engines there and then, but
 * two things happen with no save to hang off:
 *
 *   - An advert entered days ahead goes live when its posted date arrives.
 *   - A live advert closes when its closing date passes, and the page starts
 *     returning 410.
 *
 * This picks both up. Without it, a vacancy posted in advance would wait for a
 * crawler to find it in the sitemap, which is the gap a scraped copy needs to
 * get indexed first.
 *
 * Options:
 *   --dry-run   List what would be submitted, submit nothing.
 *
 * Exits non-zero on a bootstrap failure so a cron error is noticed.
 */

/* Web-facing by accident is the one way this could be abused: it would let
   anyone burn the day's Google quota. The addon folder is inside the document
   root, so refuse anything that is not a shell. */
if (PHP_SAPI !== 'cli') {
    header('HTTP/1.1 404 Not Found');
    exit(1);
}

$dry_run = in_array('--dry-run', $argv, true);

/* Perch's core raises a wall of deprecation notices on PHP 8.4. Cron mails
   anything written to output, so without this the sweep sends a page of noise
   every morning and a genuine problem goes unnoticed in it. Errors still show. */
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '1');

/* Perch reads these from the request. There is no request here, and its config
   picks production when SERVER_NAME is not the local development host. */
if (!isset($_SERVER['SERVER_NAME']))   $_SERVER['SERVER_NAME']   = 'www.countryside-jobs.com';
if (!isset($_SERVER['HTTP_HOST']))     $_SERVER['HTTP_HOST']     = 'www.countryside-jobs.com';
if (!isset($_SERVER['REQUEST_URI']))   $_SERVER['REQUEST_URI']   = '/';
if (!isset($_SERVER['DOCUMENT_ROOT'])) $_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__, 4);

$bootstrap = dirname(__DIR__, 3).'/runtime.php';

if (!is_readable($bootstrap)) {
    fwrite(STDERR, "Cannot find Perch runtime at $bootstrap\n");
    exit(1);
}

include($bootstrap);

if (!class_exists('CJS_Daily_Indexing')) {
    fwrite(STDERR, "CJS_Daily_Indexing did not load: is the cjs_daily app enabled?\n");
    exit(1);
}

$API = new PerchAPI(1.0, 'cjs_daily');
$DB  = $API->get('DB');

$today = date('Y-m-d');

/* A morning the cron missed can be made up by naming the day: --date=2026-11-08
   runs the sweep as though that were today. */
$date = $today;

foreach ($argv as $arg) {
    if (strpos($arg, '--date=') !== 0) continue;

    $date = substr($arg, 7);

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || $date !== date('Y-m-d', strtotime($date))) {
        fwrite(STDERR, "--date must be a real date in YYYY-MM-DD form\n");
        exit(1);
    }
}

$before = date('Y-m-d', strtotime($date.' -1 day'));

$table = PERCH_DB_PREFIX.'daily_jobs';

/*
 * Adverts that have gone live today. Matching on the posted date rather than
 * "anything live" keeps this to the handful that are new: re-submitting every
 * open vacancy daily would spend the Google quota and tell it nothing.
 */
$live = $DB->get_rows(
    'SELECT advertURL FROM '.$table.'
      WHERE advertStatus = "pub"
        AND advertURL   != ""
        AND postedDate   = '.$DB->pdb($date).'
        AND closingDate >= '.$DB->pdb($today).'
   ORDER BY daily_jobID DESC'
);

/*
 * Adverts whose closing date has just passed. The job page answers 410 for
 * these now, so the notification asks for the entry to be dropped instead of
 * sitting in the results until the next crawl.
 */
$closed = $DB->get_rows(
    'SELECT advertURL FROM '.$table.'
      WHERE advertStatus = "pub"
        AND advertURL   != ""
        AND closingDate  = '.$DB->pdb($before).'
   ORDER BY daily_jobID DESC'
);

$live_urls   = array_column(PerchUtil::count($live)   ? $live   : array(), 'advertURL');
$closed_urls = array_column(PerchUtil::count($closed) ? $closed : array(), 'advertURL');

printf("%s indexing sweep%s\n", $date, $dry_run ? ' (dry run)' : '');
printf("  newly live : %d\n", count($live_urls));
printf("  just closed: %d\n", count($closed_urls));

foreach ($live_urls   as $url) echo "  + $url\n";
foreach ($closed_urls as $url) echo "  - $url\n";

if ($dry_run) {
    echo "Nothing submitted.\n";
    exit(0);
}

if (count($live_urls))   CJS_Daily_Indexing::updated($live_urls);
if (count($closed_urls)) CJS_Daily_Indexing::deleted($closed_urls);

echo "Submitted. See perch/addons/apps/cjs_daily/indexing/indexing.log for the result of each call.\n";

exit(0);

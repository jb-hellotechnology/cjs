<?php
/**
 * Check the instant-indexing setup, one step at a time:
 *
 *   /usr/local/bin/php /home/cjsjobs/public_html/perch/addons/apps/cjs_daily/indexing_check.php
 *
 * Run it after changing anything in the configuration. It reads only: no advert
 * is submitted and none of the day's Google quota is spent, so it is safe to run
 * as often as you like.
 *
 * Every way this can be misconfigured looks identical from the outside - adverts
 * simply never get submitted - so this names the step that is wrong.
 *
 * Exits non-zero if any check failed, so cron or a deploy script can notice.
 */

if (PHP_SAPI !== 'cli') {
    header('HTTP/1.1 404 Not Found');
    exit(1);
}

error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '1');

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

$results = CJS_Daily_Indexing::self_test();
$failed  = 0;

echo "Instant indexing check\n\n";

foreach ($results as $r) {
    if (!$r['ok']) $failed++;
    printf("  %s  %-32s %s\n", $r['ok'] ? ' ok ' : 'FAIL', $r['label'], $r['detail']);
}

echo "\n";

if ($failed) {
    printf("%d check(s) failed. Adverts will not reach the service(s) above until that is fixed;\n", $failed);
    echo "anything already working carries on regardless.\n";
    exit(1);
}

echo "Everything is configured. New adverts will be submitted as they are published.\n";
exit(0);

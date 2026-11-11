<?php
	/* Access keys: cjs_feed_guard() must run before any output, because a key
	   that is present and wrong answers 401 and stops here. No key is not an
	   error - it returns the summary tier (and, until the enforcement date in
	   CJS_Feed_Keys, the full one with a notice), so nothing that reads this
	   feed today breaks. With the app removed the feed behaves as it always
	   did. See perch/addons/apps/cjs_feed_keys. */
	$feed = function_exists('cjs_feed_guard')
	            ? cjs_feed_guard('jobs-feed-items')
	            : ['tier' => 'full', 'notice' => false, 'key' => false];

	/* An XML feed served as text/html gets a Cloudflare JavaScript beacon
	   appended to it, which is HTML and makes the feed unparseable. The
	   right content type stops that, whatever the edge settings are. */
	header('Content-Type: application/xml; charset=utf-8');
 echo '<?xml version="1.0" encoding="UTF-8" ?>';?>
<rss version="2.0" xmlns:cjsFeed="http://countryside-jobs.com/feeds/cjsFeedModule/">

<channel>
  <title>CJS Jobs Feed - <?= ucwords(perch_get('sector')) ?></title>
  <link>https://www.countryside-jobs.com</link>
  <description>Countryside Jobs Specialists</description>
<?php if (function_exists('cjs_feed_notice')) echo cjs_feed_notice($feed); ?>
<?php cjs_jobs_feed($feed['tier']); ?>
</channel>
</rss>
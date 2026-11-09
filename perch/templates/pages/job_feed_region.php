<?php
	/* An XML feed served as text/html gets a Cloudflare JavaScript beacon
	   appended to it, which is HTML and makes the feed unparseable. The
	   right content type stops that, whatever the edge settings are. */
	header('Content-Type: application/xml; charset=utf-8');
 echo '<?xml version="1.0" encoding="UTF-8" ?>';?>
<rss version="2.0" xmlns:cjsFeed="http://countryside-jobs.com/feeds/cjsFeedModule/">

<channel>
  <title>CJS Jobs Feed - <?= ucwords(perch_get('region')) ?></title>
  <link>https://www.countryside-jobs.com</link>
  <description>Countryside Jobs Specialists</description>
<?php 
	if(perch_get('region')=='scotland-north'){$region = 'rScotlandNorth';}	
	if(perch_get('region')=='scotland-south'){$region = 'rScotlandSouth';}	
	cjs_jobs_region_feed($region); 
?>
</channel>
</rss>
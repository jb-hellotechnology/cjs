<?php echo '<?xml version="1.0" encoding="UTF-8" ?>';?>
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
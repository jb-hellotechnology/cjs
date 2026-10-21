<?php
header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8" ?>';?>
<rss version="2.0">

<channel>
  <title>CJS Environmental Education Feed</title>
  <link>https://www.countryside-jobs.com</link>
  <description>Countryside Jobs Specialists</description>
<?php environmental_education_feed(); ?>
</channel>
</rss>

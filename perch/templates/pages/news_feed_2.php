<?php
	/* An XML feed served as text/html gets a Cloudflare JavaScript beacon
	   appended to it, which is HTML and makes the feed unparseable. The
	   right content type stops that, whatever the edge settings are. */
	header('Content-Type: application/xml; charset=utf-8');
  
	$date = date('Y-m-d H:i:s');
	perch_blog_custom(array(
	  'count' => 200,
	  'sort'       => 'postDateTime',
	  'sort-order' => 'DESC',
	  'filter'  => 'postFeedDate',
      'match' => 'lte',
	  'value' => "$date",
      'template' => 'post_in_list-feed.html',
    ));
?>
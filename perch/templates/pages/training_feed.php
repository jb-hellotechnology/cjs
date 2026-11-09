<?php
	/* An XML feed served as text/html gets a Cloudflare JavaScript beacon
	   appended to it, which is HTML and makes the feed unparseable. The
	   right content type stops that, whatever the edge settings are. */
	header('Content-Type: application/xml; charset=utf-8');
	perch_collection('Training Feed Items', [
        'template'   => 'training_feed_item.html',
        'sort'       => 'date',
        'sort-order' => 'DESC',
    ]);
    
?>
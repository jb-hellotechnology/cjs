<?php
	/* An XML feed served as text/html gets a Cloudflare JavaScript beacon
	   appended to it, which is HTML and makes the feed unparseable. The
	   right content type stops that, whatever the edge settings are. */
	header('Content-Type: application/xml; charset=utf-8');
  
	perch_blog_custom(array(
      'blog' => 'news',
      'sort'       => 'postDateTime',
	  'sort-order' => 'DESC',
      'template' => 'post_in_list-feed-article.html',
    ));
?>
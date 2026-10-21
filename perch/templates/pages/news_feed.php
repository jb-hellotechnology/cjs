<?php echo '<?xml version="1.0" encoding="UTF-8" ?>';?>
<rss version="2.0" xmlns:cjsFeed="http://countryside-jobs.com/feeds/cjsFeedModule/">

<channel>
  <title>CJS News Feed</title>
  <link>https://www.countryside-jobs.com</link>
  <description>Countryside Jobs Specialists</description>
<?php  
	perch_blog_custom(array(
      'blog' => 'news',
      'sort'       => 'postDateTime',
	  'sort-order' => 'DESC',
      'template' => 'post_in_list-feed.html',
    ));
?>
</channel>
</rss>
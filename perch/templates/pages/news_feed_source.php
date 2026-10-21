<?php  
	$date = date('Y-m-d H:i:s');
	perch_blog_custom(array(
	  'count' => 200,
	  'sort'       => 'postDateTime',
	  'sort-order' => 'DESC',
	  'blog' => 'news',
      'template' => 'post_in_list-feed-source.html',
    ));
?>
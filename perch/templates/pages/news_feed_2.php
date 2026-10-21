<?php  
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
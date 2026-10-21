<?php
$date = date('Y-m-d');
perch_blog_custom(array(
  'blog' => 'focus-articles',
  'sort' => 'postDateTime',
  'sort-order' => 'DESC',
  'count' => $_POST['count'],
  'template' => 'focus_archive.html',
  'filter' => 'postDateTime',
  'match' => 'lte',
  'value' => "$date",
  'tag' => $_POST['tag']
));
?>
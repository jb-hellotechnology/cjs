<?php perch_layout('header'); ?>

<?php perch_content('Hero'); ?>

<main class="flow">
  <?php perch_content('Content'); ?>
  <div class="textarea flow">
  <?php
	  $end = date("Y-m-d", mktime(0, 0, 0, date('m')-6, date('d'), date('Y')));
	  $start = date('Y-m-d');
	  perch_blog_custom([
		'tag'   	 => 'education-funding',
		'template' => 'posts/news_post.html',
		'sort'       => 'postDateTime',
		'sort-order' => 'ASC',
		'filter' => 'postDateTime',
		'match' => 'eqbetween',
	    'value' => "$end, $start",
        ]);
	?>
  </div>
</main>

<?php perch_layout('footer'); ?>
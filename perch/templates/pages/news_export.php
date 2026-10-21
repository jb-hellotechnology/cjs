<?php
	$startText = date('j F Y 00:00:00',strtotime('last friday'));
	if(date('l')=='Thursday'){
		$endText = date('j F Y 23:59:59',strtotime('today'));
		$end = date('Y-m-d 00:00:00',strtotime('today'));
	}else{
		$endText = date('j F Y 00:00:00',strtotime('next friday'));
		$end = date('Y-m-d 00:00:00',strtotime('next friday'));
	}
	$start = date('Y-m-d 00:00:00',strtotime('last friday'));

	/**
	 * Output a single day's worth of content, in order:
	 *   1. News posts
	 *   2. News from Other Sources  (only if $includeOtherSources is true)
	 *   3. Scientific Publications
	 *
	 * Called once per day so the page reads chronologically as the week fills up.
	 *
	 * @param string $dayStart            'Y-m-d 00:00:00'
	 * @param string $dayEnd              'Y-m-d 23:59:59'
	 * @param string $newsTemplate        template to use for the main news blog
	 * @param bool   $includeOtherSources set true if this page should also show
	 *                                    the 'news-from-other-sources' blog
	 */
	function cjs_output_day($dayStart, $dayEnd, $newsTemplate = 'posts/news_post.html', $includeOtherSources = false){

		$hasContent = false;

		// ---------- 1. NEWS ----------
		$news = perch_blog_custom(array(
			'filter'        => 'postDateTime',
			'match'         => 'eqbetween',
			'value'         => "$dayStart, $dayEnd",
			'blog'          => 'news',
			'count'         => 100,
			'skip-template' => true,
		));

		if(is_array($news) && count($news) > 0){
			$hasContent = true;
			perch_blog_custom(array(
				'filter'     => 'postDateTime',
				'match'      => 'eqbetween',
				'value'      => "$dayStart, $dayEnd",
				'blog'       => 'news',
				'sort'       => 'postDateTime',
				'sort-order' => 'ASC',
				'count'      => 100,
				'template'   => $newsTemplate,
			));
		}

		// ---------- 2. NEWS FROM OTHER SOURCES ----------
		if($includeOtherSources){
			$other = perch_blog_custom(array(
				'filter'        => 'postDateTime',
				'match'         => 'eqbetween',
				'value'         => "$dayStart, $dayEnd",
				'blog'          => 'news-from-other-sources',
				'count'         => 100,
				'skip-template' => true,
			));

			if(is_array($other) && count($other) > 0){
				$hasContent = true;
				perch_blog_custom(array(
					'filter'     => 'postDateTime',
					'match'      => 'eqbetween',
					'value'      => "$dayStart, $dayEnd",
					'blog'       => 'news-from-other-sources',
					'sort'       => 'postDateTime',
					'sort-order' => 'ASC',
					'count'      => 100,
					'template'   => 'posts/other_sources_post.html',
				));
			}
		}

		// ---------- 3. SCIENTIFIC PUBLICATIONS ----------
		$scientific = perch_blog_custom(array(
			'filter'        => 'postDateTime',
			'match'         => 'eqbetween',
			'value'         => "$dayStart, $dayEnd",
			'blog'          => 'scientific-posts',
			'count'         => 100,
			'skip-template' => true,
		));

		if(is_array($scientific) && count($scientific) > 0){
			$hasContent = true;
			echo "<br /><br /><p class='alt'><strong>Scientific Publications</strong></p>";
			perch_blog_custom(array(
				'filter'     => 'postDateTime',
				'match'      => 'eqbetween',
				'value'      => "$dayStart, $dayEnd",
				'blog'       => 'scientific-posts',
				'sort'       => 'postDateTime',
				'sort-order' => 'ASC',
				'count'      => 100,
				'template'   => 'posts/scientific_post.html',
			));
		}

		return $hasContent;
	}
?>
<?php perch_layout('header'); ?>
<?php perch_content('Hero'); ?>
<main class="flow">
	<div class="textarea">
	  <?php echo "<h1>".$startText." - ".$endText."</h1>"; ?>
	  <?php perch_content('Content'); ?>
	  <?php
		PerchSystem::set_var('today', date('Y-m-d'));
	  ?>
	</div>
	  <?php
		  // ------------------------------------------------------------------
		  // Walk the week one day at a time (Friday through Thursday), oldest
		  // first. Each day gets its own News > Scientific Publications block
		  // so the page stays chronological as articles are added.
		  // ------------------------------------------------------------------
		  $firstDay = strtotime(date('Y-m-d', strtotime('last friday')));
		  $lastDay  = strtotime('+6 days', $firstDay); // the following Thursday

		  // Never run past today
		  $today = strtotime(date('Y-m-d'));
		  if($lastDay > $today){
			$lastDay = $today;
		  }

		  for($dayCursor = $firstDay; $dayCursor <= $lastDay; $dayCursor = strtotime('+1 day', $dayCursor)){
			$dayStart = date('Y-m-d 00:00:00', $dayCursor);
			$dayEnd   = date('Y-m-d 23:59:59', $dayCursor);

			// Set the last argument to true if this page should also output
			// the 'news-from-other-sources' blog.
			cjs_output_day($dayStart, $dayEnd, 'posts/news_post.html', true);
		  }
	  ?>
	  <div class="textarea">
	  <p class="center">Links to many of the articles on these pages are no longer current, please proceed with caution. </p>
	  <p class="center">CJS is not responsible for content of external sites. Details believed correct but given without prejudice.<br />
	  Disclaimer: the views expressed in these news pages do not necessarily reflect the views or policies of CJS.</p>
	  </div>
</main>
<?php perch_layout('footer'); ?>
<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
?>
<?php perch_layout('header'); ?>

<?php perch_content('Hero'); ?>

<main class="flow">
	      <?php perch_content('Content'); ?>
	      <hr />
	      <?php
	        // GET DATE OF PREVIOUS MONDAY
	        $end = date('Y-m-d 23:59:59');
	        $start = date('Y-m-d 00:00:01');	
	        
	
	        // GET COUNT OF ARTICLES PUBLISHED SINCE PREVIOUS MONDAY
	
	        $count = perch_blog_custom(array(
	          'filter' => 'postDateTime',
	          'match' => 'eqbetween',
	          'value' => "$start, $end",
	          'blog' => 'news',
	          'skip-template' => true,
	          'count' => 100
	        ));
	
	        $count = count($count);
	
	        // IF 0 GET DATE OF PREVIOUS MONDAY
	        if($count==0){
	          $start = date('Y-m-d 00:00:01',strtotime('Yesterday'));
	        }
	        
	        $count = perch_blog_custom(array(
	          'filter' => 'postDateTime',
	          'match' => 'eqbetween',
	          'value' => "$start, $end",
	          'blog' => 'news',
	          'skip-template' => true,
	          'count' => 100
	        ));
	
	        $count = count($count);
	
	        // IF 0 GET DATE OF PREVIOUS MONDAY
	        if($count==0){
	          $start = date('Y-m-d 00:00:01',strtotime('last Friday'));
	        }
	
	        // SHOW ARTICLES FROM PREVIOUS MONDAY TO PRESENT DAY
	        $count = perch_blog_custom(array(
	          'filter' => 'postDateTime',
	          'match' => 'eqbetween',
	          'value' => "$start, $end",
	          'blog' => 'news',
	          'skip-template' => true,
	          'count' => 100
	        ));

			$count = count($count);

	        // IF 0 GET DATE OF PREVIOUS MONDAY
	        if($count==0){
	          $start = date('Y-m-d 00:00:01',strtotime("-1 week Friday"));
	        }

	        // SHOW ARTICLES FROM PREVIOUS MONDAY TO PRESENT DAY
	        $count = perch_blog_custom(array(
	          'filter' => 'postDateTime',
	          'match' => 'eqbetween',
	          'value' => "$start, $end",
	          'blog' => 'news',
	          'skip-template' => true,
	          'count' => 100
	        ));
	        
	        $count = count($count);
	        
	         // IF 0 GET DATE OF PREVIOUS MONDAY
	        if($count==0){
	          $start = date('Y-m-d 00:00:01',strtotime("-3 weeks Friday"));
	        }

	        // SHOW ARTICLES FROM PREVIOUS MONDAY TO PRESENT DAY
	        $count = perch_blog_custom(array(
	          'filter' => 'postDateTime',
	          'match' => 'eqbetween',
	          'value' => "$start, $end",
	          'blog' => 'news',
	          'skip-template' => true,
	          'count' => 100
	        ));
	        
	        $count = count($count);

			perch_blog_custom(array(
	          'filter' => 'postDateTime',
	          'match' => 'eqbetween',
	          'value' => "$start, $end",
	          'sort' => 'postDateTime',
	          'sort-order' => 'ASC',
	          'blog' => 'news',
	          'template' => 'posts/news_post.html',
	          'count' => 100
	        ));

	        
	        $count = perch_blog_custom(array(
	          'filter' => 'postDateTime',
	          'match' => 'eqbetween',
	          'value' => "$start, $end",
	          'blog' => 'scientific-posts',
	          'skip-template' => true,
	          'count' => 100
	        ));
	        
	        $count = count($count);
	        echo '<div class="textarea flow">';
	        if($count>0){
		        echo "<br /><br /><p class='alt'><strong>Scientific Publications</strong></p>";
	        }
	        
	        perch_blog_custom(array(
	          'filter' => 'postDateTime',
	          'match' => 'eqbetween',
	          'value' => "$start, $end",
	          'blog' => 'scientific-posts',
	          'template' => 'posts/scientific_post.html',
	          'count' => 100
	        ));
			
			perch_blog_custom(array(
			  'filter' => 'postDateTime',
			  'match' => 'eqbetween',
			  'value' => "$start, $end",
			  'blog' => 'news-from-other-sources',
			  'template' => 'posts/other_sources_post.html',
			  'count' => 100
			));
	      ?>
	      <br /><br />
	      <p class="center">CJS is not responsible for content of external sites. Details believed correct but given without prejudice.<br />
			  Disclaimer: the views expressed in these news pages do not necessarily reflect the views or policies of CJS.</p>
		</div>
</main>

<?php perch_layout('footer'); ?>
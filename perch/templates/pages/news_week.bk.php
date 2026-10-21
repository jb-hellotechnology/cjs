<?php
	if(perch_get('weekDate')==''){
		if(date('l')=='Monday'){
			$startText = date('j F Y');
		}else{
        	$startText = date('j F Y',strtotime('last monday'));
        }
    }else{
	    $start = perch_get('weekDate');
	    $end = date('Y-m-d 00:00:00',strtotime("$start +7 days"));
        $dates = explode("-",$start);
        $startText = date("j F Y", mktime(0, 0, 0, $dates[1], $dates[2], $dates[0]));
        $posts = perch_blog_custom(array(
	        'filter' => 'postDateTime',
	        'match' => 'eqbetween',
	        'value' => "$start 00:00:00, $end",
	        'blog' => 'news',
	        'skip-template' => true,
	      ),true);
	   foreach($posts as $post){
		   $titles .= $post['postTitle'].', ';
	   }
	   $titles = substr($titles,0,-2);
    }
	$title = 'CJS News, Headlines from week beginning '.$startText;
	$description = "This week's countryside and wildlife news: ".$titles;	
?>

<?php perch_layout('header', array(
    'title'=>$title,
    'description'=>$description,
)); ?>

<?php perch_content('Hero'); ?>

<main>
    <div class="textarea flow">
	  <?php echo "<h1>".$title."</h1>"; ?>
    </div>
      <?php perch_content('Content'); ?>
      <?php
          PerchSystem::set_var('today', date('Y-m-d'));
        ?>
        <?php
          if(perch_get('weekDate')==''){
            // GET DATE OF PREVIOUS MONDAY
            $end = date('Y-m-d H:i:s');
            if(date('l')=='Monday'){
                $start = date('Y-m-d 00:00:00');
                $startText = date('jS F Y');
            }else{
                $start = date('Y-m-d 00:00:00',strtotime('last Monday'));
                $startText = date('jS F Y',strtotime('last Monday'));
            }
      
            // GET COUNT OF ARTICLES PUBLISHED SINCE PREVIOUS MONDAY
            $count = perch_blog_custom(array(
              'filter' => 'postDateTime',
              'match' => 'eqbetween',
              'value' => "$start, $end",
              'blog' => 'news',
              'count' => 100,
              'skip-template' => true
            ));
      
            $count = count($count);
      
            // IF 0 GET DATE OF PREVIOUS MONDAY
            if($count==0){
              $start = date('Y-m-d 00:00:00',strtotime('last monday'));
            }

            // SHOW ARTICLES FROM PREVIOUS MONDAY TO PRESENT DAY
            perch_blog_custom(array(
              'filter' => 'postDateTime',
              'match' => 'eqbetween',
              'value' => "$start, $end",
              'blog' => 'news',
              'sort' => 'postDateTime',
              'sort-order' => 'ASC',
              'count' => 100,
              'template' => 'posts/news_post.html',
            ));
            
            perch_blog_custom(array(
                'filter' => 'postDateTime',
                'match' => 'eqbetween',
                'value' => "$start, $end",
                'blog' => 'news-from-other-sources',
                'template' => 'posts/other_sources_post.html',
                'count' => 100
              ));

            $count = perch_blog_custom(array(
                'filter' => 'postDateTime',
                'match' => 'eqbetween',
                'value' => "$start, $end",
                'blog' => 'scientific-posts',
                'count' => 100,
                'skip-template' => true
              ));
              
              $count = count($count);
            
            if($count>0){
                echo "<br /><br /><p class='alt'><strong>Scientific Publications</strong></p>";
            }
              
            perch_blog_custom(array(
                'filter' => 'postDateTime',
                'match' => 'eqbetween',
                'value' => "$start, $end",
                'blog' => 'scientific-posts',
                'count' => 100,
                'template' => 'posts/scientific_post.html',
              ));
              
              
              
          }else{
            $start = perch_get('weekDate');
            $end = date('Y-m-d H:i:s',strtotime("$start +7 days"));
            $yearAgo = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d')-365, date('Y')));
            if($start<=$yearAgo){
              $template = 'post_news_no_images.html';
            }else{
              $template = 'posts/news_post.html';
            }
            perch_blog_custom(array(
              'filter' => 'postDateTime',
              'match' => 'eqbetween',
              'value' => "$start 00:00:00, $end",
              'blog' => 'news',
              'sort' => 'postDateTime',
              'sort-order' => 'ASC',
              'count' => 100,
              'template' => $template,
            ));
            
            $count = perch_blog_custom(array(
                'filter' => 'postDateTime',
                'match' => 'eqbetween',
                'value' => "$start 00:00:00, $end",
                'blog' => 'scientific-posts',
                'count' => 100,
                'skip-template' => true
              ));
              
              $count = count($count);
            
            if($count>0){
                echo "<br /><br /><p class='alt'><strong>Scientific Publications</strong></p>";
            }
              
            perch_blog_custom(array(
                'filter' => 'postDateTime',
                'match' => 'eqbetween',
                'value' => "$start 00:00:00, $end",
                'blog' => 'scientific-posts',
                'count' => 100,
                'template' => 'posts/scientific_post.html',
              ));
              
              perch_blog_custom(array(
                'filter' => 'postDateTime',
                'match' => 'eqbetween',
                'value' => "$start, $end",
                'blog' => 'news-from-other-sources',
                'template' => 'posts/other_sources_post.html',
                'count' => 100
              ));
          }
          
          //echo "<br /><br /><p>Articles Displayed Published Between $start and $end</p>";
        ?>
      <div class="textarea flow">
      <p class="center">Links to many of the articles on these pages are no longer current, please proceed with caution. </p>
    <p class="center">CJS is not responsible for content of external sites. Details believed correct but given without prejudice.<br />
    Disclaimer: the views expressed in these news pages do not necessarily reflect the views or policies of CJS.</p>
      </div>
</main>

<?php perch_layout('footer'); ?>
<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
?>
<?php perch_blog_check_preview(); ?>
<?php perch_blog_check_preview_date(perch_get('s')); ?>

<?php
    # get the post
    $post = perch_blog_custom(array(
          'filter'        => 'postSlug',
          'match'         => 'eq',
          'value'         => perch_get('s'),
          'skip-template' => 'true',
          'return-html'   => 'true',
      ));
      
      //print_r($post[0]['exploreTags']);

    # set up the variables
    $title      = $post['0']['postTitle'];
    $template	= $post['0']['postTemplate'];
    
    if($template=='posts/news_post.html'){
	    $bodyClass = 'olive-green';
	    $navgroup = 'news';
    }
    
    $json = json_decode($post['0']['postDynamicFields'],true);
    $description = $json['og_description'];
    $image_alt = $json['og_image_alt'];

    if (isset($post[0]['og_image'])) {
        $og_image = $post[0]['og_image'];
    } else {
        $og_image = '';
    }

    # use the variables in the array value
    perch_page_attributes_extend(array(
        'description'    => $description,
        'og_description' => $description,
        'og_title'       => $title,
        'og_type'        => 'article',
        'og_image'       => $og_image,
        'og_image_alt'   => $image_alt,
        'og_author'      => 'https://www.facebook.com/CountrysideJobsService/',
    ));
?>

<?php perch_layout('header', array(
	'og_title' => $title, 'bodyClass' => $bodyClass, 'navgroup' => $navgroup)); ?>

<?php perch_content('Hero'); ?>

<main class="flow">
    <?php 
      $date = date("Y-m-d H:i:s", mktime(0, 0, 0, date('m')-6, date('d'), date('Y')));
      PerchSystem::set_var('today', $date); 
    ?>
    <?php 
	    perch_blog_post(perch_get('s')); 
	?>
	<article>
	<?php 
		$dates = explode(" ",$post[0]['postDateTime']);
		$dates = explode("-",$dates[0]);
		$date = "$dates[2]/$dates[1]/$dates[0]";
		echo "<p><strong>Posted On:</strong> ".$date."</p>"; 
		if($template=='posts/news_post.html'){
			echo '<p><a href="https://www.countryside-jobs.com/news">Read today&rsquo;s news here.</a></p>';
		}
        
        $emPosts = false;
	    
        if($post[0]['exploreTags']){
            $articles = '';
            
            $tags = explode(",", $post[0]['exploreTags']);
            foreach($tags as $tag){

                $posts = perch_blog_custom(array(
                    'sort' => 'postDateTime',
                    'sort-order' => 'DESC',
                    'count' => 3,
                    'tag'    => trim($tag),
                    'skip-template' => true
                ));
                
                foreach($posts as $post){
                    if($post['postSlug']!==perch_get('s')){
 
                        $html .= '<h3><a href="/article/'.$post['postSlug'].'">'.$post['postTitle'].'</a></h3>';
                        $html .= "<p>".$post['og_description']."</p>";
                        $emPosts = true;
                    }
                }
            }
        }
        
        if($emPosts){
            echo '<h2>Explore More</h2>';
            echo $html;
        }
        
        perch_blog_post_tags(perch_get('s'), array(
	       'template' => '/post_tag_link.html',
	    )); 
    ?>
    </article>
</main>

<?php perch_log_blog_view(perch_get('s')); ?>
<?php perch_layout('footer'); ?>
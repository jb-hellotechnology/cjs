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

    /**
     * Output a single day's worth of content, in order:
     *   1. News posts
     *   2. News from Other Sources
     *   3. Scientific Publications
     *
     * Called once per day so the page reads chronologically as the week fills up.
     *
     * @param string $dayStart      'Y-m-d 00:00:00'
     * @param string $dayEnd        'Y-m-d 23:59:59'
     * @param string $newsTemplate  template to use for the main news blog
     */
    function cjs_output_day($dayStart, $dayEnd, $newsTemplate = 'posts/news_post.html'){

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
          // ------------------------------------------------------------------
          // Work out the date range and news template for this page, then walk
          // it one day at a time so each day is self-contained.
          // ------------------------------------------------------------------
          $template = 'posts/news_post.html';

          if(perch_get('weekDate')==''){

            // CURRENT WEEK: previous Monday through to now
            $end = date('Y-m-d H:i:s');
            if(date('l')=='Monday'){
                $start = date('Y-m-d 00:00:00');
                $startText = date('jS F Y');
            }else{
                $start = date('Y-m-d 00:00:00',strtotime('last Monday'));
                $startText = date('jS F Y',strtotime('last Monday'));
            }

            // IF NOTHING PUBLISHED YET THIS WEEK, FALL BACK TO THE WEEK BEFORE
            $count = perch_blog_custom(array(
              'filter'        => 'postDateTime',
              'match'         => 'eqbetween',
              'value'         => "$start, $end",
              'blog'          => 'news',
              'count'         => 100,
              'skip-template' => true
            ));

            if(count($count)==0){
              $start = date('Y-m-d 00:00:00',strtotime('last monday'));
            }

            $firstDay = strtotime(date('Y-m-d', strtotime($start)));
            $lastDay  = strtotime(date('Y-m-d')); // today

          }else{

            // ARCHIVE WEEK: the requested Monday plus the following six days
            $start = perch_get('weekDate');
            $end   = date('Y-m-d 23:59:59',strtotime("$start +6 days"));

            $yearAgo = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d')-365, date('Y')));
            if($start<=$yearAgo){
              $template = 'post_news_no_images.html';
            }else{
              $template = 'posts/news_post.html';
            }

            $firstDay = strtotime(date('Y-m-d', strtotime($start)));
            $lastDay  = strtotime(date('Y-m-d', strtotime("$start +6 days")));
          }

          // ------------------------------------------------------------------
          // Output day by day, oldest first. Each day gets its own
          // News > News from Other Sources > Scientific Publications block.
          // ------------------------------------------------------------------
          for($dayCursor = $firstDay; $dayCursor <= $lastDay; $dayCursor = strtotime('+1 day', $dayCursor)){
            $dayStart = date('Y-m-d 00:00:00', $dayCursor);
            $dayEnd   = date('Y-m-d 23:59:59', $dayCursor);

            cjs_output_day($dayStart, $dayEnd, $template);
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
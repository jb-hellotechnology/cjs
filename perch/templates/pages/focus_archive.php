<?php perch_layout('header'); ?>

<?php

if(perch_get('section')){
?>
<?php perch_content('Hero - '.perch_get('section')); ?>
<?php perch_content('Content - '.perch_get('section')); ?>
<main class="flow">
  <div class="textarea flow">
    <h2 id="search">Search by Tag</h2>
    <p>Enter keywords in the box below to be offered suggestions:</p>
    <form method="get" action="#" onkeydown="return event.key != 'Enter';">
      <section class="input">
        <input type="text" id="q" name="q" onkeyup="javascript:get_tags_2();" placeholder="countryside" />
      </section>
    </form>
      <?php
        // $tags = array();
        // $date = date('Y-m-d');
        // $data = perch_blog_custom(array(
        //   'blog' => 'focus-articles',
        //   'sort' => 'postDateTime',
        //   'sort-order' => 'DESC',
        //   'count' => 2000,
        //   'template' => 'focus_archive_list.html',
        //   'filter' => 'postDateTime',
        //   'match' => 'lte',
        //   'value' => "$date",
        //   'section' => perch_get('section'),
        //   'skip-template' => true
        // ));
        // foreach($data as $post){
        //   $postTags = explode(",", $post['postTags']);
        //   foreach($postTags as $tag){
        //     $tags[] = trim(strtolower($tag));
        //   }
        // }
        // print_r(array_unique($tags));
        $date = date('Y-m-d');
        perch_blog_custom(array(
          'blog' => 'focus-articles',
          'sort' => 'postDateTime',
          'sort-order' => 'DESC',
          'count' => 2000,
          'template' => 'focus_archive_list.html',
          'filter' => 'postFeedDate',
          'match' => 'lte',
          'value' => "$date",
          'section' => perch_get('section'),
        ));
        ?>
  </div>
</main>

<?php
}else{
?>

<?php perch_content('Hero'); ?>
<main class="flow">
    <?php perch_content('Content'); ?>
    <div class="textarea flow">
    <h2 id="search">Search by Tag</h2>
    <p>Enter keywords in the box below to be offered suggestions:</p>
    <form method="get" action="#" onkeydown="return event.key != 'Enter';">
	    <section class="input">
		    <input type="text" id="q" name="q" onkeyup="javascript:get_tags_2();" placeholder="countryside" />
	    </section>
    </form>
      <?php
	      $date = date('Y-m-d');
	      perch_blog_custom(array(
            'blog' => 'focus-articles',
            'sort' => 'postDateTime',
            'sort-order' => 'DESC',
            'count' => 2000,
            'template' => 'focus_archive.html',
            'filter' => 'postFeedDate',
		    'match' => 'lte',
		    'value' => "$date",
          ));
        ?>
    </div>
</main>

<?php
}
?>

<script>
$.expr[':'].contains = function(a, i, m) {
  return jQuery(a).text().toUpperCase()
      .indexOf(m[3].toUpperCase()) >= 0;
};
function get_tags_2(){
	var tag = $('#q').val();
	if(tag !== ''){
		$('.list li').hide();
		$('.list li:contains('+tag+')').show();
	}else{
		$('.list li').show();
	}
}	
</script>

<?php perch_layout('footer'); ?>
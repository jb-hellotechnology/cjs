<?php perch_layout('header'); ?>

<?php
	if(perch_get('s')){
		$organisation = perch_get('s');
		echo '<main>';
    	echo '<div class="textarea flow">';
        organisation_profile($organisation,'');
		echo '</div>';
		all_jobs_organisation($organisation);
		
		echo '<div class="textarea flow">';
		all_volunteers_organisation($organisation);
		long_courses_organisation($organisation);
		short_courses_organisation($organisation);
		events_organisation($organisation);
		
		$orgData = organisation($organisation,true);
		
		$tags = explode(",",$orgData['nameTag']);
		$newTags = array();
		foreach($tags as $tag){
			$tagClean = ltrim(rtrim($tag));
			array_push($newTags,$tagClean);
		}
	
		perch_blog_custom([
		    'tag'		 => $newTags,
			'blog'		 => 'focus-articles',
		    'template'	 =>'post_in_list-organisation-features.html',
		    'count'      => 20,
			'sort'       => 'postDateTime',
			'sort-order' => 'DESC',
		]);

		perch_blog_custom([
			'tag'		 => $newTags,
			'blog'		 => 'news',
			'template'	 =>'post_in_list-organisation-news.html',
			'count'      => 20,
			'sort'       => 'postDateTime',
			'sort-order' => 'DESC',
		]);
	echo '</div>';
	echo '</main>';
	}else{
?>
<?php perch_content('Hero'); ?>
<main class="flow">
    <?php perch_content('Content'); ?>
	<div class="textarea flow">
		<h2 id="search">Search by Name</h2>
		<p>Enter keywords in the box below to be offered suggestions:</p>
		<form method="get" action="#">
			<section class="input">
				<input type="text" id="q" name="q" onkeyup="javascript:get_employers();" placeholder="CJS" />
			</section>
		</form>
		<?php
			list_advertisers_all();
		?>
	</div>
</main>
<?php
	}
?>
<?php perch_layout('footer'); ?>
<?php perch_layout('header'); ?>

<?php perch_content('Hero'); ?>

<main class="flow">
    <?php perch_content('Content'); ?>
    <?php
		if($_POST){
			if($_POST['type']=='text'){
				echo "<h1>All Categories Text Search Results</h1><p><a href=\"/training/search/\">&larr; Search Again</a></p>";
				searchTraining($_POST);
			}elseif($_POST['type']=='shortcourse_advanced'){
				echo "<h1>Short Course Advanced Search Results</h1><p><a href=\"/training/search/\">&larr; Search Again</a></p>";
				searchShortCourse($_POST);
			}elseif($_POST['type']=='longcourse_advanced'){
				echo "<h1>Long Course Advanced Search Results</h1><p><a href=\"/training/search/\">&larr; Search Again</a></p>";
				searchLongCourse($_POST);
			}elseif($_POST['type']=='distance'){
				echo "<h1>Distance Learning Search Results</h1><p><a href=\"/training/search/\">&larr; Search Again</a></p>";
				searchDistance($_POST);
			}elseif($_POST['type']=='providers'){
				echo "<h1>Providers Search Results</h1><p><a href=\"/training/search/\">&larr; Search Again</a></p>";
				searchCentresProviders($_POST);
			}elseif($_POST['type']=='events'){
				echo "<h1>Events Search Results</h1><p><a href=\"/training/search/\">&larr; Search Again</a></p>";
				searchEvents($_POST);
			}elseif($_POST['type']=='specific_text'){
				echo "<h1>Search Term Section Results</h1><p><a href=\"/training/search/\">&larr; Search Again</a></p>";
				searchTrainingTerm($_POST);
			}
		}    
	?>
</main>

<?php perch_layout('footer'); ?>
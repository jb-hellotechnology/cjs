<?php perch_layout('header'); ?>
<main>
	<div class="textarea flow">
	<?php perch_content('Content'); ?>
	<?php
		if($_POST){
			if($_POST['type']=='text'){
				echo "<h2>Job Search Results For: ".strip_tags($_POST['textSearch'])."</h2>";
				dailyTextSearch($_POST);
			}elseif($_POST['type']=='daily'){
				echo "<h2>Job Advanced Search Results</h2>";
				dailySearch($_POST);
			}elseif($_POST['type']=='volunteers'){
				echo "<h2>Volunteer Advanced Search Results</h2>";
				dailySearchVolunteers($_POST);
			}
		}    
	?>
	</div>
</main>
<?php perch_layout('footer'); ?>
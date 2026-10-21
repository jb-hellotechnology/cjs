<?php
	$id = perch_get('advertID');
	$volunteer = perch_get('volunteerAdvertID');
	$free = perch_get('freeAdvertID');
	$title = "Advert Preview";
?>
    
<?php 
	perch_layout('header', array(
		'og_title' => $title
	)); 
?>

<main class="flow">
	<div class="textarea flow">
	  <p><strong>PREVIEW - PLEASE NOTE CJS MAY AMEND THIS ADVERT BEFORE PUBLICATION</strong></p>
    <?php
	    if($id){
			cjs_job_preview($id);
			echo "<br /><br /><p><a href=\"/advertise/$id\">Amend this Advert</a></p>";
		}else if($volunteer){
			cjs_volunteer_preview($volunteer);
			echo "<br /><br /><p><a href=\"/advertise/volunteers/$volunteer\">Amend this Advert</a></p>";
		}else if($free){
			cjs_free_preview($free);
			echo "<br /><br /><p><a href=\"/advertise/jobs-free-ads/$free\">Amend this Advert</a></p>";
		}
    ?>
    	<p class="right">Get jobs like this by email <a href="/jobs/cjs-newsletters">every day</a> or <a href="/jobs/cjs-weekly">weekly</a></p>
	</div>
</main>

<?php perch_layout('footer'); ?>
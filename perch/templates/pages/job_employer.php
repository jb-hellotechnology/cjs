<?php 
	$organisation = perch_get('organisation');
	$data = organisation_title($organisation);
	$title = $data['name']." Jobs, Training and News";
	perch_layout('header', array(
    'og_title'=>$title,
)); ?>

<?php 
  perch_content('Hero');
?>

<?php
	if(perch_get('view')=='profile'){
?>
<main class="flow">
    <?php 
        organisation_profile($organisation, '');
    ?>
</main>
<?php
	}else{
?>
<main>
  <div class="textarea flow">
    <?php 
        organisation_profile($organisation,'');
      echo '</div>';
        all_jobs_organisation($organisation);
        ?>
    <div class="textarea flow">
      <?php
        all_volunteers_organisation($organisation);
        ?>
  
  <p>To see everything from this organisation <a href="/in-depth/organisations/<?php echo $organisation; ?>">click here</a>.</p>
  </div>
</main>
<?php
	}
?>

<?php perch_layout('footer'); ?>
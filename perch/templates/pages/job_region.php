<?php perch_layout('header.jobs'); ?>

<?php 
  perch_content('Hero');
?>

<main class="flow">
  <?php 
    perch_content('Content');
  ?>
  <?php 
	  advertiser_messages();
      $region = perch_content('Region',true);
      region_jobs($region); 
      jobs_map();
  ?>
</main>

<?php perch_layout('footer'); ?>
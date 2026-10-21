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
      $grade = perch_content('Grade',true);
      grade_jobs($grade); 
  ?>
</main>

<?php perch_layout('footer'); ?>
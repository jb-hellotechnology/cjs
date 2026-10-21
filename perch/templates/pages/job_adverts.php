<?php perch_layout('header.jobs'); ?>

<?php 
  perch_content('Hero');
?>

<main class="flow jobs">
    <?php 
      perch_content('Content');
    ?>
    <?php 
	  advertiser_messages();
      all_jobs();
      jobs_map();
    ?>
</main>

<?php perch_layout('footer'); ?>
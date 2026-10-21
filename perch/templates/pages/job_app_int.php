<?php perch_layout('header.jobs'); ?>

<?php 
  perch_content('Hero');
?>

<main class="flow">
  <?php 
      perch_content('Content');
  ?>
  <?php 
      app_int_jobs(); 
  ?>
</main>

<?php perch_layout('footer'); ?>
<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

?>
<?php perch_layout('header'); ?>

<?php 
  perch_content('Hero');
?>

<main class="flow">
    <?php 
      perch_content('Content');
   ?>
   <div class="training-columns">
  <?php 
      $sector = perch_content('Sector',true);
      short_courses($sector);
  ?>
   </div>
  <?php
      perch_content('Secondary Content');
  ?>
  <?php training_map(); ?>
</main>

<?php perch_layout('footer'); ?>
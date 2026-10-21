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
  <?php
      get_centres_providers();
  ?>
  <div class="container margin-top">
  	<?php
      perch_content('Secondary Content');
   ?>
  </div>
</main>

<?php perch_layout('footer'); ?>
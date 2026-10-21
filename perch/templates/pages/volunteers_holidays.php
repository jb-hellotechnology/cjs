<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
?>
<?php perch_layout('header'); ?>
<?php perch_content('Hero'); ?>

<main class="flow">
    <?php
      perch_content('Content');
    ?>
</main>
<?php perch_layout('footer'); ?>
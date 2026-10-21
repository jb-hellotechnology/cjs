<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
?>
<?php perch_layout('header.volunteers'); ?>
<?php 
  if(perch_get('sector')==''){
      perch_content('Hero'); 
    }else{
      perch_content('Hero: '.perch_get('sector'));
    }
?>

<main class="flow">
    <?php
    if(perch_get('sector')==''){
      perch_content('Content');
    }else{
      perch_content('Sector: '.perch_get('sector').' Content');
      volunteers_adverts(perch_get('sector'));
      perch_content('Sector: '.perch_get('sector').' Secondary Content');
    }
    ?>
</main>

<?php perch_layout('footer'); ?>
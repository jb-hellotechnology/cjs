<?php perch_layout('header'); ?>

<?php 
  perch_content('Hero');
?>

<main class="flow">
  <?php 
    perch_content('Content');
    advertiser_messages();
    $sector = perch_content('Sector',true);
    
    sector_jobs($sector); 
    jobs_map();
  ?>
</main>

<?php perch_layout('footer'); ?>
<?php perch_layout('header'); ?>

<?php perch_content('Hero'); ?>

<main class="flow">
    <?php perch_content('Content'); ?>
  <?php
	  advertiser_message_volunteers();
	volunteer_additions();	  
  ?>
    <?php perch_content('Additional Content'); ?>
</main>

<?php perch_layout('footer'); ?>
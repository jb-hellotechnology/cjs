<?php perch_layout('header.volunteers'); ?>

<?php perch_content('Hero'); ?>

<main class="flow">
    <?php perch_content('Content'); ?>
  <?php
	  advertiser_message_volunteers();
	  volunteer_ad(perch_get('volunteerID'));	  
  ?>
</main>

<?php perch_layout('footer'); ?>
<?php perch_layout('header'); ?>

<?php perch_content('Hero'); ?>

<main class="flow">
    <?php perch_content('Content'); ?>
    <?php 
	    if(isset($_SESSION['error'])){
		    echo '<div class="error"><p>Error - account details not recognised</p></div>';
	    }
	    unset($_SESSION['error']);
	    	perch_content('Form'); 
	    if(isset($_SESSION['subscriber'])){
		    //perch_content('Subscribers Only');
	    }
	?>
    <?php perch_content('Additional Content'); ?>
</main>

<?php perch_layout('footer'); ?>
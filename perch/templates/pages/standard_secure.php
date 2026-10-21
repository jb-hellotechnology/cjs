<?php
	if($_SESSION['subscriber']<>1){
		header("Location: /restricted_access/");
	}
?>
<?php perch_layout('header'); ?>

<?php perch_content('Hero'); ?>

<main class="flow">
    <?php perch_content('Content'); ?>
    <?php
	    if(isset($_SESSION['subscriber'])){
		    perch_content('Subscribers Only');
	    }
	?>
    <?php perch_content('Additional Content'); ?>
</main>

<?php perch_layout('footer'); ?>
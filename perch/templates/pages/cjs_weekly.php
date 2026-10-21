<?php
	$hide = perch_page_attribute('hide_search',array(),true);
	if($hide=='yes'){
		header("location:/404");
	}
?>
<?php perch_layout('header'); ?>

<?php perch_content('Hero'); ?>

<main class="flow professional">
    <?php perch_content('Content'); ?>
    <?php perch_content('Form'); ?>
    <?php perch_content('Additional Content'); ?>
</main>

<script src="/assets/javascript/import.js"></script>
<?php perch_layout('footer'); ?>
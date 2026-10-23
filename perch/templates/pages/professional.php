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
</main>

<button onclick="storeProfessional();" id="store-professional"><i class="fa fa-floppy-disk"></i> Save content to cache</button>

<script>
	function storeProfessional(){
		if (window.confirm("Are you sure?")) {
			const html = document.documentElement.innerHTML;
			$.post( "/scripts/saveProfessional.php", { content: html }, function(){
				alert('Saved!');	
			});
		}
	}
</script>


<?php perch_layout('footer'); ?>
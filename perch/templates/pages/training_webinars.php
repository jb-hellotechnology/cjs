<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
?>
<?php perch_layout('header'); ?>

<?php 
  perch_content('Hero');
?>

<main class="flow">
    <?php 
      perch_content('Content');
   ?>
   <div class="textarea">
	<h3>Webinars</h3>
   </div>
	<div class="training-columns">
	<?php 
		webinars('webinars');
	?>
	</div>
	<div class="textarea">
		<h3>Online Learning - Short Courses</h3>
	</div>
	<div class="training-columns">
	<?php 
		webinars('short-courses');	
	?>
	</div>
	<div class="textarea">
		<h3>Online Learning - Longer Courses</h3>
	</div>
	<p>Also have a look at <a href="/training/distance-learning">Distance Learning</a> courses and providers.</p>
	<div class="training-columns">
	<?php 
		webinars('long-courses');	
	?>
	</div>
</main>

<?php perch_layout('footer'); ?>
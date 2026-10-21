<?php perch_layout('header'); ?>

<?php perch_content('Hero'); ?>

<main class="flow">
    <?php perch_content('Content'); ?>
    <?php 
	    $shortProviders = get_short_providers();
	    $longProviders = get_long_providers();
	    $distanceProviders = get_distance_providers();
	    PerchSystem::set_var('shortproviders', $shortProviders);
	    PerchSystem::set_var('longproviders', $longProviders);
	    PerchSystem::set_var('distanceproviders', $distanceProviders);
	    perch_form('search_cjs_training.html');
		//perch_content('Form');
	?>
    <?php perch_content('Additional Content'); ?>
</main>

<?php perch_layout('footer'); ?>
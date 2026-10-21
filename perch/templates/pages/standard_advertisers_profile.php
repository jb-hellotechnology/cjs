<?php perch_layout('header'); ?>

<?php perch_content('Hero'); ?>

<main class="flow">
    <?php 	    
	    if(perch_member_logged_in()){
		    perch_member_add_tag('active', '+24 months');
			perch_content('Secure Content');   
			echo "<h2>Hello ".perch_member_get('first_name')." of ".perch_member_get('organisation')."</h2>"; 
			echo "<p>Member ID: ".perch_member_get('memberID')."</p>";
			echo '<h2>Edit Your Profile or Password</h2><p><a href="/advertisers/">Return to account</a>.</p><h3>Profile</h3>';
		    perch_member_form('profile.html');
		    echo '<h3>Update Password</h3>';
		    perch_member_form('password.html');
		    echo '<h3>Delete Account?</h3>';
		    perch_content('Form');
	    }
	?>
</main>

<?php perch_layout('footer'); ?>
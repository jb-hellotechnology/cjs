<?php perch_layout('header'); ?>

<?php perch_content('Hero'); ?>

<main class="flow">
    <?php 
	    $organisations = organisation_list_full();
	    $orgs = '';
	    foreach($organisations AS $org){
		    $orgs .= "$org[name]|$org[organisationID],";
	    }
	    $orgs = substr($orgs,0,-1);
	    PerchSystem::set_var('member_organisation', $orgs);
	    
	    if(!perch_member_logged_in()){
		    perch_content('Content'); 
		    echo '<div class="split"><div><h3>Sign In</h3>';
		    perch_members_login_form();
		    perch_member_form('reset_password.html');
		    echo '</div><div><h3>Register</h3>';
		    perch_member_form('register.html');
		    echo '</div>';
	    }else{
		    if(!$_SESSION['basiccredits']){
			    $_SESSION['basiccredits'] = perch_member_get('basiccredits');
		    }
		    
		    if(!$_SESSION['standardcredits']){
			    $_SESSION['standardcredits'] = perch_member_get('standardcredits');
		    }
		    
		    if(!$_SESSION['unlimitedcredits']){
			    $_SESSION['unlimitedcredits'] = perch_member_get('unlimitedcredits');
		    }
		    echo "<h2>Hello ".perch_member_get('first_name')." of ".perch_member_get('organisation')."</h2>";
		    echo "<p style='font-weight:700;color:red;'>"; echo perch_member_get('notes'); echo "</p>";
			perch_content('Secure Content');  
			echo "<div class='split'><div>";
			echo '<p><a href="/advertisers/sign-out">Click here to sign out</a>.</p><p><a href="/advertisers/profile">Click here to edit your profile or password</a>.</p>';  
			if(perch_member_has_tag('approved')){
				echo '<h3>You\'re Approved</h3><p>You can submit your adverts for immediate publication.</p>';
			}
			if(perch_member_has_tag('package')){
				echo '<h3>You\'ve Got Credits</h3><p>You can submit your adverts for immediate publication using pre-paid credits. Here are the number of credits you have available:</p>
				<ul><li>Basic: '; echo $_SESSION['basiccredits']; echo '</li><li>Standard: '; echo $_SESSION['standardcredits']; echo '</li><li>Premium: '; echo $_SESSION['longcredits']; echo '</li></ul>';
			}
			echo '<h3>Adverts</h3>';
			cjs_member_daily_adverts(perch_member_get('memberID'));
			echo '<h3>Free Linage in CJS Weekly (not online)</h3>';
			cjs_member_free_adverts(perch_member_get('memberID'));
			echo '<h3>Volunteer Adverts</h3>';
			cjs_member_volunteer_adverts(perch_member_get('memberID'));
			echo "</div><div>";
			echo '<p><strong>Post a new advert</strong></p>';
			echo '<ul><li><a href="/advertise">Advertise</a></li><li><a href="/advertise/jobs-free-ads">Free Linage in CJS Weekly</a></li><li><a href="/advertise/volunteers">Volunteers</a></li></ul>';
			echo "</div></div>";
	    }
	?>
</main>

<?php perch_layout('footer'); ?>
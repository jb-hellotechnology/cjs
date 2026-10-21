<?php
	$hide = perch_page_attribute('hide_search',array(),true);
	if($hide=='yes'){
		header("location:/404");
	}
?>
<?php perch_layout('header'); ?>

<?php perch_content('Hero'); ?>

<main class="flow">
    <?php perch_content('Content'); ?>
    <?php 
	    if(perch_member_logged_in()){
		   
		    if(perch_get('advertID')){
			
				$memberID = perch_member_get('id');
				$advertID = perch_get('advertID');
				$advertData = cjs_free_data(perch_get('advertID'));
				$json = json_decode($advertData['free_advertsDynamicFields'],true);

			    PerchSystem::set_var('form_jobtitle', str_replace("\"","'",$advertData['title']));
			    PerchSystem::set_var('form_closingdate', str_replace("\"","'",$advertData['closingdate']));
			    PerchSystem::set_var('form_location', str_replace("\"","'",$advertData['location']));
			    PerchSystem::set_var('form_salary', str_replace("\"","'",$advertData['salary']));
			    PerchSystem::set_var('form_employer', str_replace("\"","'",$advertData['employer']));
			    PerchSystem::set_var('form_text', str_replace("\"","'",$advertData['advertText']));

			    PerchSystem::set_var('form_enhanced', $advertData['enhanced']);
			    
			    PerchSystem::set_var('form_logo1', $advertData['logo']);
			    
			    PerchSystem::set_var('form_name', str_replace("\"","'",$advertData['name']));
			    PerchSystem::set_var('form_organisation', str_replace("\"","'",$advertData['organisation']));
			    PerchSystem::set_var('form_memberID', $memberID);
			    PerchSystem::set_var('form_email', $advertData['email']);
			    PerchSystem::set_var('form_phone', $advertData['telephone']);
			    PerchSystem::set_var('form_message', str_replace("\"","'",$advertData['message']));
			    PerchSystem::set_var('form_howdidyoufindus', $advertData['referral']);
			    
			    PerchSystem::set_var('form_memberID', $memberID);
			    PerchSystem::set_var('form_advertID', $advertID);
			    PerchSystem::set_var('form_advertRef', $advertData['advertRef']);
	
			    PerchSystem::set_var('form_statusOptions', 'Ready for publication|pen,Save as draft|draft');
			    
		    }else{
			    
			    $name = perch_member_get('first_name').' '.perch_member_get('last_name');
			    $memberID = perch_member_get('id');
			    $organisation = organisation_id(perch_member_get('organisation'),true);
			    PerchSystem::set_var('form_name', $name);
			    PerchSystem::set_var('form_memberID', $memberID);
			    PerchSystem::set_var('form_email', perch_member_get('email'));
			    PerchSystem::set_var('form_phone', perch_member_get('telephone'));
			    PerchSystem::set_var('form_organisation', perch_member_get('organisation'));
			    PerchSystem::set_var('form_statusOptions', 'Ready for publication|pen,Save as draft|draft');
		    
		    }
	    }
	    perch_content_custom('Form');
	?>
    <?php perch_content('Additional Content'); ?>
</main>

<?php perch_layout('footer'); ?>
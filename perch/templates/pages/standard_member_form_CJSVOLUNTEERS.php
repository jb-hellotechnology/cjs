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
				$advertData = cjs_vol_data(perch_get('advertID'));
				$json = json_decode($advertData['volunteerDynamicFields'],true);

			    PerchSystem::set_var('form_jobtitle', $advertData['title']);
			    PerchSystem::set_var('form_closingdate', $advertData['closingDateT']);
			    PerchSystem::set_var('form_location', $advertData['location']);
			    PerchSystem::set_var('form_benefits', $advertData['payBenefits']);
			    PerchSystem::set_var('form_employer', $advertData['employerDisplay']);
			    PerchSystem::set_var('form_text', $advertData['advertText']);
			    
			    PerchSystem::set_var('form_role', $advertData['typeRole']);
			    
			    if($advertData['tcOccasional']=='1'){
				    $tC = 'Occasional';
			    }
			    if($advertData['tcOnceorTwiceWeek']=='1'){
				    $tC = 'Once or twice a week';
			    }
			    if($advertData['tcPartTime']=='1'){
				    $tC = 'Part time';
			    }
			    if($advertData['tcFullTime']=='1'){
				    $tC = 'Full time';
			    }
			    if($advertData['tcSixMonths']=='1'){
				    $tC = 'Six months';
			    }
			    if($advertData['tcSixMonthsYear']=='1'){
				    $tC = 'Six months to a year';
			    }
			    if($advertData['tcOneYearMore']=='1'){
				    $tC = 'One year or more';
			    }
			    
			    PerchSystem::set_var('form_time', $json['timecommitment']);
			    PerchSystem::set_var('form_enddate', $json['enddate']);
			    PerchSystem::set_var('form_enhanced', $json['enhanced']);
			    
			    PerchSystem::set_var('form_logo1', $advertData['imageLogo']);
			    PerchSystem::set_var('form_logo2', $advertData['imageLogo2']);
			    
			    PerchSystem::set_var('form_name', $json['name']);
			    PerchSystem::set_var('form_organisation', $json['organisation']);
			    PerchSystem::set_var('form_memberID', $memberID);
			    PerchSystem::set_var('form_email', $json['email']);
			    PerchSystem::set_var('form_phone', $json['telephone']);
			    
			    PerchSystem::set_var('form_message', $json['message']);
			    PerchSystem::set_var('form_howdidyoufindus', $json['howdidyoufindus']);
			    PerchSystem::set_var('form_memberID', $memberID);
			    PerchSystem::set_var('form_advertID', $advertID);
			    PerchSystem::set_var('form_advertRef', $advertData['advertRef']);
	
			    if($advertData['advertStatus']=='pub' OR $advertData['advertStatus']=='over'){
					if(perch_member_has_tag('approved') OR perch_member_has_tag('package')){
						PerchSystem::set_var('form_statusOptions', 'Immediate publication|pub');
					}else{
						PerchSystem::set_var('form_statusOptions', 'Update (note this action cannot be undone)|over'); 
					}  
			    }elseif($advertData['advertStatus']=='draft'){
				    if(perch_member_has_tag('approved') OR perch_member_has_tag('package')){
						PerchSystem::set_var('form_statusOptions', 'Immediate publication|pub,Save as draft|draft');
					}else{
						PerchSystem::set_var('form_statusOptions', 'Ready for publication|pen,Save as draft|draft');
					}
			    }elseif($advertData['advertStatus']=='pen'){
				    PerchSystem::set_var('form_statusOptions', 'Submit for approval|pen');
			    }else{
				    if(perch_member_has_tag('approved') OR perch_member_has_tag('package')){
						PerchSystem::set_var('form_statusOptions', 'Immediate publication|pub,Save as draft|draft');
					}else{
						PerchSystem::set_var('form_statusOptions', 'Ready for publication|pen,Save as draft|draft');
					}
			    }
			    
		    }else{
			    
			    $name = perch_member_get('first_name').' '.perch_member_get('last_name');
			    $memberID = perch_member_get('id');
			    $organisation = organisation_id(perch_member_get('organisation'),true);
			    PerchSystem::set_var('form_name', $name);
			    PerchSystem::set_var('form_memberID', $memberID);
			    PerchSystem::set_var('form_email', perch_member_get('email'));
			    PerchSystem::set_var('form_phone', perch_member_get('telephone'));
			    PerchSystem::set_var('form_organisation', perch_member_get('organisation'));
			    if(perch_member_has_tag('approved')){
					PerchSystem::set_var('form_statusOptions', 'Immediate publication|pub,Save as draft|draft');
				}else{
					PerchSystem::set_var('form_statusOptions', 'Ready for publication|pen,Save as draft|draft');
				}
		    
		    }
	    }
	    perch_content_custom('Form');
	?>
    <?php perch_content('Additional Content'); ?>
</main>

<?php perch_layout('footer'); ?>
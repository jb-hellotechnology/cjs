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
				$advertData = cjs_job_data(perch_get('advertID'));
				$json = json_decode($advertData['daily_jobDynamicFields'],true);
				if(!is_array($json)){ $json = array(); }

				// CHANGED: the DB stores dates as Y-m-d but the form fields and
				// the jQuery datepicker both expect dd/mm/yyyy. Passing the raw
				// column straight through is why the closing date looked wrong.
				$ukDate = function($d){
					if(empty($d) || $d=='0000-00-00'){ return ''; }
					$ts = strtotime($d);
					return $ts ? date('d/m/Y', $ts) : '';
				};

				PerchSystem::set_var('form_advertType', isset($json['advertType']) ? $json['advertType'] : '');
				
				PerchSystem::set_var('form_jobtitle', $advertData['title']);
				PerchSystem::set_var('form_contractlength', $advertData['contractLength']);
				PerchSystem::set_var('form_employer', $advertData['employerDisplay']);
				PerchSystem::set_var('form_closingdate', $ukDate($advertData['closingDate']));
				PerchSystem::set_var('form_location', $advertData['location']);

				// CHANGED: never set previously, so the hidden fields came back
				// empty and every map marker was lost on edit.
				PerchSystem::set_var('form_lat', $advertData['lat']);
				PerchSystem::set_var('form_lng', $advertData['lng']);

				// CHANGED: renamed from form_text. The form input is also called
				// form_text, and Perch resolves the field before the var, so the
				// saved copy never reached the editor.
				PerchSystem::set_var('form_advertText', $advertData['advertText']);

				// CHANGED: the 35 individual region/sector/contract set_var calls
				// that used to sit here have been replaced by this single list.
				// Their names collided with the form field ids, and nothing in
				// the template read them, so no checkbox was ever restored.
				$checkboxFields = array(
					'rNational','rInternational','rScotlandNorth','rScotlandSouth',
					'rWales','rNorthernIreland','rEngland','rAnglia','rMidlands',
					'rNorthEast','rNorthWest','rSouth','rSouthWest','rHome',
					'sAdminIT','sArbo','sComVol','sCountry','sEco','sEnvOE',
					'sHorti','sPubF','sRoW','sVisMan','sWiWoAnC','sSustainability',
					'sPolicy','sFieldwork','sPractical','sForestry',
					'cfullTime','cpartTime','cpermanent','cTempFixed','cseasonal',
				);
				$checked = array();
				foreach($checkboxFields as $f){
					if(!empty($advertData[$f])){ $checked[] = $f; }
				}
				PerchSystem::set_var('form_checkedFields', implode(',', $checked));
				
				PerchSystem::set_var('form_socialmediapromotion', isset($json['socialmediapromotion']) ? $json['socialmediapromotion'] : '');
				PerchSystem::set_var('form_video', isset($json['video']) ? $json['video'] : '');
				PerchSystem::set_var('form_unpaid', isset($json['unpaid']) ? $json['unpaid'] : '');
				PerchSystem::set_var('form_professional', isset($json['professional']) ? $json['professional'] : '');

				// CHANGED: form_weekly was never set, so the CJS Weekly box never
				// came back ticked.
				PerchSystem::set_var('form_weekly', isset($json['weekly']) ? $json['weekly'] : '');

				// CHANGED: none of these four were set, so the Job of the Day and
				// Social Shares options and their dates were lost on every edit.
				PerchSystem::set_var('form_jod', $advertData['jobOfDay']);
				PerchSystem::set_var('form_jodDate', $ukDate($advertData['jobOfDayDate']));
				PerchSystem::set_var('form_socialshares', $advertData['socialShares']);
				PerchSystem::set_var('form_socialsharesDate', $ukDate($advertData['socialSharesDate']));
				
				PerchSystem::set_var('form_logo1', $advertData['imageLogo']);
				PerchSystem::set_var('form_logo2', $advertData['imageLogo2']);
				PerchSystem::set_var('form_logo3', $advertData['imageLogo3']);
				PerchSystem::set_var('form_logo4', $advertData['imageLogo4']);
				PerchSystem::set_var('form_logo5', $advertData['imageLogo5']);
				
				PerchSystem::set_var('form_invoiceaddress', isset($json['invoiceaddress']) ? $json['invoiceaddress'] : '');
				PerchSystem::set_var('form_reference', isset($json['reference']) ? $json['reference'] : '');
				PerchSystem::set_var('form_payment', isset($json['payment']) ? $json['payment'] : '');
				
				PerchSystem::set_var('form_name', isset($json['name']) ? $json['name'] : '');
				PerchSystem::set_var('form_organisation', isset($json['organisation']) ? $json['organisation'] : '');
				PerchSystem::set_var('form_email', isset($json['email']) ? $json['email'] : '');
				PerchSystem::set_var('form_phone', isset($json['telephone']) ? $json['telephone'] : '');
				
				PerchSystem::set_var('form_message', isset($json['message']) ? $json['message'] : '');
				PerchSystem::set_var('form_howdidyoufindus', isset($json['howdidyoufindus']) ? $json['howdidyoufindus'] : '');
				PerchSystem::set_var('form_memberID', $memberID);
				PerchSystem::set_var('form_advertID', $advertID);
				PerchSystem::set_var('form_advertURL', $advertData['advertURL']);
	
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
					if(perch_member_has_tag('approved') OR perch_member_has_tag('package')){
						PerchSystem::set_var('form_statusOptions', 'Immediate publication|pub,Ready for publication|pen,Save as draft|draft');
					}else{
						PerchSystem::set_var('form_statusOptions', 'Ready for publication|pen');
					} 
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

				// CHANGED: set empty so the template's perch:content tags resolve
				// cleanly on a new advert rather than rendering the raw tag.
				PerchSystem::set_var('form_checkedFields', '');
				PerchSystem::set_var('form_advertText', '');

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
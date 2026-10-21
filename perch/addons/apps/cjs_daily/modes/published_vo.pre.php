<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $VOrganisations_wd = new CJS_Workdays_Vol_Orgs($API);

  $vorganisations_wd = array();

  $vorganisations_wd = $VOrganisations_wd->all();

  if($Form->submitted()){
    //FOR ITEMS PROGRAMMATICALLY ADDED TO FORM
    $postvars = array('checkDate','advertStatus');
    $data = $Form->receive($postvars);

    //GETS PROPER DATE STRUCTURE FOR DATE FIELDS      	
    $data['checkDate'] = $Form->get_date('checkDate');
    foreach ($vorganisations_wd as $VOrganisation_wd){
      if ($_POST['select_'.$VOrganisation_wd->id()]=='1'){
        $VOrganisation_wd->update($data);
      }
    }
    $vorganisations_wd = $VOrganisations_wd->all();
    $message = $HTML->success_message('Your Voluntary Organisations have been successfully updated. Return to %sVoluntary Organisations%s', '<a href="'.$API->app_path() .'/voluntary_organisations/">', '</a>');   
  }
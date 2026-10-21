<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $VOrganisations_wd = new CJS_Workdays_Vol_Orgs($API);

  $vorganisations_wd = array();

  $vorganisations_wd = $VOrganisations_wd->suspended();

  if($Form->submitted()){
    //FOR ITEMS PROGRAMMATICALLY ADDED TO FORM
    $postvar = array('useCheckDate');
    $data_test = $Form->receive($postvar);
    $postvars = array('checkDate','useCheckDate','advertStatus');
    $data = $Form->receive($postvars);
    foreach ($vorganisations_wd as $VOrganisation_wd){
      if ($_POST['select_'.$VOrganisation_wd['vorganisationID']]=='1'){
        $VOrganisations_wd->update_advert($data,$VOrganisation_wd['vorganisationID']);
      }
    }
    $vorganisations_wd = $VOrganisations_wd->suspended();
    $message = $HTML->success_message('Your Voluntary Organisations have been successfully updated. Return to %sVoluntary Organisations%s', '<a href="'.$API->app_path() .'/voluntary_organisations/">', '</a>');   
  }
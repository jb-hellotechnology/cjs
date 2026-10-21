<?php

  $HTML 		= $API->get('HTML');
  $Form 		= $API->get('Form');
  $Template = $API->get('Template');

  $Organisations = new CJS_Organisations_Orgs($API);
  
  $Daily = new CJS_Daily_Employers($API);
  $Training = new CJS_Training_Organisations($API);
  $Workdays = new CJS_Workdays_Organisations($API);

  $organisations = array();

  $organisations = $Organisations->all();

  $message = '';

  if($Form->submitted()){
    
    $postvars = array('organisationID','organisationID_2');
    $data = $Form->receive($postvars);
    
    if($data['organisationID']<>$data['organisationID_2']){

		//UPDATE DAILY EMPLOYERS
		$Daily->updateEmployers_Organisations($data['organisationID'],$data['organisationID_2']);
		
		//UPDATE WORKDAYS OFFICES
		$Training->updateOffices_Organisations($data['organisationID'],$data['organisationID_2']);
		
		//UPDATE TRAINING OFFICES
		$Workdays->updateOffices_Organisations($data['organisationID'],$data['organisationID_2']);
		
		//MOVE SLUG
		$Organisations->updateSlug($data['organisationID'],$data['organisationID_2']);
		
		//DELETE MERGED ORG
		$Organisation = $Organisations->find($data['organisationID'], true);
		$Organisation->delete();
		
		$message = $HTML->success_message('Organisations merged. Return to %sOrganisations%s', '<a href="'.$API->app_path() .'/organisations/">', '</a>'); 

    }else{
      $message = $HTML->warning_message('Please choose two different organisations'); 
    }
    
  }
<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $Organisations_wd = new CJS_Workdays_Organisations($API);

  $organisations = array();

  $organisations = $Organisations_wd->all();

  $message = '';

  if($Form->submitted()){
    
    $postvars = array('organisationID','organisationID_2');
    $data = $Form->receive($postvars);
    
    if($data['organisationID']<>$data['organisationID_2']){
    
      //UPDATE WORKDAYS
      $Organisations_wd->updateWorkdays($data['organisationID'],$data['organisationID_2']);

      //UPDATE SURVEYS
      $Organisations_wd->updateSurveys($data['organisationID'],$data['organisationID_2']);

      //UPDATE SURVEYS
      $Organisations_wd->updateVOrgs($data['organisationID'],$data['organisationID_2']);

      //DELETE MERGED ORG
      $Organisation = $Organisations_wd->find($data['organisationID'], true);
      $Organisation->delete();

      $message = $HTML->success_message('Organisations merged. Return to %sOrganisations%s', '<a href="'.$API->app_path() .'/organisations/">', '</a>'); 

    }else{
      $message = $HTML->warning_message('Please choose two different organisations'); 
    }
    
  }
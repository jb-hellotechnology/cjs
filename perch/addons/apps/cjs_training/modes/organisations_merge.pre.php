<?php

  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $OrganisationsT = new CJS_Training_Organisations($API);

  $organisations = array();

  $organisations = $OrganisationsT->all();

  $message = '';

  if($Form->submitted()){
    
    $postvars = array('organisationID','organisationID_2');
    $data = $Form->receive($postvars);
    
    //UPDATE PROVIDERS
    $OrganisationsT->updateProviders($data['organisationID'],$data['organisationID_2']);
    
    //UPDATE SHORT COURSES
    $OrganisationsT->updateShortCourses($data['organisationID'],$data['organisationID_2']);
    
    //UPDATE LONG COURSES
    $OrganisationsT->updateLongCourses($data['organisationID'],$data['organisationID_2']);
    
    //UPDATE EVENTS
    $OrganisationsT->updateEvents($data['organisationID'],$data['organisationID_2']);
    
    //DELETE MERGED ORG
    $Organisation = $OrganisationsT->find($data['organisationID'], true);
    $Organisation->delete();
    
    $message = $HTML->success_message('Organisations merged. Return to %sOrganisations%s', '<a href="'.$API->app_path() .'/organisations/">', '</a>'); 
    
  }
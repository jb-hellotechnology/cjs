<?php
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL); 
  $HTML 		= $API->get('HTML');
	$Form 		= $API->get('Form');
	$Template = $API->get('Template');

  $Volunteers = new CJS_Daily_Volunteers($API);

  $volunteers = array();

  $volunteers = $Volunteers->get_temp();

  if($Form->submitted()){
    
    foreach($volunteers as $volunteer){
	    
	    $volunteer['volunteerID'] = NULL;
	    
	    $Volunteers->import_temp_row($volunteer);
	    
    }
    
    $Volunteers->empty_temp();
    PerchUtil::redirect($API->app_path().'/volunteers');
    
  }